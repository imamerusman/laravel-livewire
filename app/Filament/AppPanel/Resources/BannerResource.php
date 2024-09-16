<?php

namespace App\Filament\AppPanel\Resources;

use App\Filament\AppPanel\Resources\BannerResource\Pages\CreateBanner;
use App\Filament\AppPanel\Resources\BannerResource\Pages\EditBanner;
use App\Filament\AppPanel\Resources\BannerResource\Pages\ListBanners;
use App\Models\Banner;
use Exception;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $slug = 'banners';
    protected static ?string $recordTitleAttribute = 'id';

/*    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Banners';*/
    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-photo';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    TextInput::make('name'),
                    Select::make('link')
                        ->searchable()
                        ->preload()
                        ->getSearchResultsUsing(fn(string $search): array => self::getCategoriesFromShopify($search))
                        ->getOptionLabelUsing(fn($value): ?string => self::getCategoriesFromShopify()[$value] ?? null),
                    SpatieMediaLibraryFileUpload::make('media')
                ])
            ]);
    }

    public static function getCategoriesFromShopify(string $handle = ''): array
    {
        $handle = strtolower(str_replace(' ', '_', $handle));
        $redis = Redis::connection()->client();
        $shopifyShopDomain = $redis->get('shopify_shop_domain');
        $shopifyAccessToken = $redis->get('shopify_access_token');
        $url = "https://$shopifyShopDomain/admin/api/2023-07/custom_collections.json";

        if (filled($handle)) {
            $url .= "?handle=$handle";
        }

        $response = self::fetchCollections($shopifyAccessToken, $url);
        $results = $response->json()['custom_collections'] ?? [];

        // If results are empty and a handle was provided, get all collections
        if (empty($results) && filled($handle)) {
            $url = "https://$shopifyShopDomain/admin/api/2023-07/custom_collections.json";
            $response = self::fetchCollections($shopifyAccessToken, $url);
            $results = $response->json()['custom_collections'] ?? [];
        }

        return collect($results)->mapWithKeys(function ($item) {
            return [$item['admin_graphql_api_id'] => $item['title']];
        })->toArray();
    }

    /**
     * @param mixed $shopifyAccessToken
     * @param string $url
     * @return PromiseInterface|Response
     */
    public static function fetchCollections(mixed $shopifyAccessToken, string $url): Response|PromiseInterface
    {
        return Http::withHeaders([
            'X-Shopify-Access-Token' => $shopifyAccessToken,
        ])->get($url);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('link'),
                SpatieMediaLibraryImageColumn::make('media'),
                TextColumn::make('created_at')->date()->since()
            ])->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])->bulkActions([
                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->action(fn(Collection $records) => $records->each(
                        fn(Banner $customer) => $customer->delete()
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBanners::route('/'),
            'create' => CreateBanner::route('/create'),
            'edit' => EditBanner::route('/{record}/edit'),
        ];
    }

   /* public static function can(string $action, ?Model $record = null): bool
    {
        return !auth()->user()->is_admin;
    }*/
}
