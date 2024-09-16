<?php

namespace App\Http\Controllers;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        $appId = config('services.facebook.client_id');
        $redirectUrl = urlencode(config('services.facebook.redirect'));

        $facebookLoginUrl = "https://www.facebook.com/v17.0/dialog/oauth?" .
            "client_id=$appId" .
            "&redirect_uri=$redirectUrl";

        return redirect($facebookLoginUrl);
    }

    /**
     * @throws Exception
     */
    public function handleFacebookCallback(Request $request)
    {
        $appId = config('services.facebook.client_id');
        $appSecret = config('services.facebook.client_secret');
        $redirectUrl = config('services.facebook.redirect');
        $code = $request->input('code');

        // Exchange the code for an access token
        $tokenUrl = "https://graph.facebook.com/v12.0/oauth/access_token?client_id=$appId&redirect_uri=$redirectUrl&client_secret=$appSecret&code=$code";
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get($tokenUrl)->json();

        // Use the access token to fetch user data
        $redis = Redis::connection()->client();

        if(isset($response['error'])){
            throw new Exception($response['error']['message']);
        }

        if (array_key_exists('access_token', $response)) {
            $redis->set('facebook_access_token', json_encode($response));

            Notification::make()
                ->title('Facebook Authenticated')
                ->body('You are now authenticated with Facebook.')
                ->send();

            return redirect('/app');
        }
        throw new Exception('Failed to authenticate with Facebook.');
    }
}
