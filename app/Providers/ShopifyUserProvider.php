<?php
/*
namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use Predis\Client;
use Spatie\Permission\Models\Role;

class ShopifyUserProvider extends EloquentUserProvider
{
    protected ?Client $redis;

    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);

        // Set up the Redis instance
        $this->redis = Redis::connection()->client();
    }
    public function retrieveByCredentials(array $credentials): User|UserContract|null
    {
        // Try to retrieve the user from the cache
        if ($user = $this->getUserFromCache()) {
            return $user;
        }

        $shopifyAccessToken = $this->redis->get('shopify_access_token');
        $shopifyShopDomain = $this->redis->get('shopify_shop_domain');

        if (filled($shopifyAccessToken) && filled($shopifyShopDomain)) {
            return $this->getUserFromShopify($shopifyAccessToken, $shopifyShopDomain);
        }

        return null;
    }

    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        // We'll validate based on the shopify token
        // (In a real-world scenario, you might want to validate with Shopify API)
        $shopifyAccessTokenFromRedis = $this->redis->get('shopify_access_token');

        if (isset($credentials['shopify_access_token']) && $shopifyAccessTokenFromRedis == $credentials['shopify_access_token']) {
            return true;
        }

        return false;
    }

    private function getUserFromShopify(string $accessToken, string $shopUrl): ?User
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get("https://{$shopUrl}/admin/api/2021-04/shop.json");

        if (!$response->successful()) {
            return null;
        }

        $shop = $response->json()['shop'];

        $user = User::updateOrCreate([
            'id' => $shop['id'],
            'email' => $shop['email'],
        ], [
            'name' => $shop['shop_owner']
        ]);

        $user->assignRole(Role::findByName('user'));

        // Cache the user data in Redis
        $this->cacheUser($user,360);
        return $user;
    }

    private function getUserFromCache(): ?User
    {
        // Retrieve the user data from cache if available
        $userId = $this->redis->get('shopify_user_id');

        if ($userId && $user = User::find($userId)) {
            return $user;
        }

        return null;
    }

    private function cacheUser(User $user, int $expirationTime = 300): void
    {
        // Cache the user data in Redis for a specified duration (e.g., 5 minutes)
        $this->redis->setex('shopify_user_id', $expirationTime, $user->id);
    }
}*/
