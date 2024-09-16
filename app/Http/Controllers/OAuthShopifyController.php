<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopifyCallbackRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Livewire\Features\SupportRedirects\Redirector;
use Shopify\Exception\InvalidArgumentException;
use Shopify\Utils;
use Spatie\Permission\Models\Role;


class OAuthShopifyController extends Controller
{

    public function appifyStore()
    {
        $redis = Redis::connection()->client();
        dd($redis->get('shopify_access_token'), $redis->get('shopify_shop_domain'), $redis->get('shopify_url'));
    }

    public function install(Request $request)
    {
        $shopifyApiKey = config('services.shopify.client_id');
        $shopifyScopes = config('services.shopify.scopes');
        $shopifyRedirectUri =   route('shopify.callback'); // The URL where Shopify will redirect after the installation process
        $shopUrl = $request->query('shop');

        return redirect()->away("https://$shopUrl/admin/oauth/authorize?client_id=$shopifyApiKey&scope=" . implode(',', $shopifyScopes) . "&redirect_uri=$shopifyRedirectUri");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function handleCallback(ShopifyCallbackRequest $request)
    {
        $clientId = config('services.shopify.client_id');
        $clientSecret = config('services.shopify.client_secret');
        $shop = $request->query('shop');
        $hmac = $request->query('hmac');
        $code = $request->query('code');
        $params = array_diff_key($request->query(), ['hmac' => '']);
        ksort($params);
        $computedHmac = hash_hmac('sha256', http_build_query($params), $clientSecret);
        if (!hash_equals($hmac, $computedHmac)) {
            return response()->json(['error' => 'Invalid HMAC'], 400);
        }

        $response = Http::post("https://$shop/admin/oauth/access_token", [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'code' => $code,
        ]);
        $responseData = $response->json();
        // Store the access token securely in your database associated with the shop URL
        $accessToken = $responseData['access_token'] ?? '';
        $redis = Redis::connection()->client();
        $redis->del(['shopify_access_token', 'shopify_shop_domain', 'shopify_url']);
        $redis->set('shopify_access_token', $accessToken);
        $redis->set('shopify_shop_domain', $shop);

        if (blank($accessToken)) {
            return response()->json(['error' => 'Invalid access token'], 400);
        }
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://$shop/admin/api/2021-04/shop.json");
        if (!$response->ok()) {
            return response()->json(['error' => 'Invalid shop'], 400);
        }

        $shop = $response->json()['shop'];
        $user = User::updateOrCreate([
            'id' => @$shop['id'],
            'email' => @$shop['email'],
        ], [
            'name' => @$shop['shop_owner']
        ]);
        /*$user->assignRole(Role::findByName('user'));*/
        $is_embedded = true ?? 'true';
        if($is_embedded === true) {
            $user = User::where('id', $user->id)->first();
            Auth::login($user);
            Filament::auth()->login($user);
            $embeddedUrl = Utils::getEmbeddedAppUrl($request->query("host", null));
            /*$redis->set('shopify_url', $embeddedUrl."appify-25");*/
            $redis->set('shopify_url', $embeddedUrl);
            return redirect()->to('app');
        }
    }
    public function shopUrl(Request $request) : RedirectResponse|Redirector
    {
         $this->validate($request, [
             'shop_url' => 'required'
         ]);
        $shop_url = request()->get('shop_url');
        return redirect()->route('shopify.install', ['shop' => $shop_url]);
    }

}
