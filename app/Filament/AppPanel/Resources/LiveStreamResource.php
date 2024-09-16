<?php

namespace App\Filament\AppPanel\Resources;

use App\Filament\AppPanel\Resources\LiveStreamResource\Pages;
use App\Models\LiveStream;
use App\Services\YouTubeOAuthService;
use App\Traits\Youtube\BroadcastStatus;
use Closure;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LiveStreamResource extends Resource
{
    protected static ?string $model = LiveStream::class;

    protected static ?string $slug = 'live-streams';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('title')
                        ->required(),
                    Forms\Components\TextInput::make('description')
                        ->required(),
                    Forms\Components\Select::make('products')
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->getSearchResultsUsing(fn(string $search): array => self::getProductsFromShopify($search))
                        ->getOptionLabelUsing(fn($value): ?string => self::getProductsFromShopify()[$value] ?? null),
                ])
            ]);
    }

    public static function getProductsFromShopify(string $handle = ''): array
    {
        $handle = strtolower(str_replace(' ', '_', $handle));
        $redis = Redis::connection()->client();
        $shopifyShopDomain = $redis->get('shopify_shop_domain');
        $shopifyAccessToken = $redis->get('shopify_access_token');
        $url = "https://$shopifyShopDomain/admin/api/2023-07/custom_collections.json";

        if (filled($handle)) {
            $url .= "?handle=$handle";
        }

        $response = self::fetchProducts($shopifyAccessToken, $url);
        try {
            self::checkErrors($response->json());
        } catch (Exception $e) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
        $results = $response->json()['products'] ?? [];
        // If results are empty and a handle was provided, get all collections
        if (empty($results) && filled($handle)) {
            $url = "https://$shopifyShopDomain/admin/api/2023-07/products.json";
            $response = self::fetchProducts($shopifyAccessToken, $url);
            $results = $response->json()['products'] ?? [];
        }

        return collect($results)->mapWithKeys(function ($item) {
            return [$item['admin_graphql_api_id'] => $item['title']];
        })->toArray();
    }

    public static function fetchProducts(mixed $shopifyAccessToken, string $url): Response|PromiseInterface
    {
        return Http::withHeaders([
            'X-Shopify-Access-Token' => $shopifyAccessToken,
        ])->get($url);
    }

    /**
     * @throws Exception
     */
    private static function checkErrors(array $response): void
    {
        if (isset($response['errors'])) {
            throw new Exception($response['errors']);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll()
            ->columns([
                Tables\Columns\TextColumn::make('title')->limit(20),
                Tables\Columns\TextColumn::make('description')->limit(25),
                Tables\Columns\TextColumn::make('stream_link')
                    ->copyable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('Go Live')
                    ->modalWidth('max-w-xl')
                    ->modalSubmitActionLabel('Got it')
                    ->modalFooterActionsAlignment(Alignment::Right)
                    ->modalContent(self::displayQrCode())
                    ->icon('heroicon-o-play')
                    ->visible(fn(Model $record) => isset($record->stream_key)),
                Tables\Actions\Action::make('Stream is Initializing ...')
                    ->icon('heroicon-o-play')
                    ->hidden(fn(Model $record) => isset($record->stream_key))
                    ->disabled(fn(Model $record) => blank($record->stream_key)),
                Tables\Actions\EditAction::make('Edit'),
                Tables\Actions\DeleteAction::make('Delete')
                    ->modalDescription('Hold up! This action is irreversible.'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function displayQrCode(): Closure
    {
        return function (LiveStream $liveStream) {
            $qr = self::createQrCode(
                config('services.api_video.stream_url'),
                $liveStream->stream_key
            );
            return view('filament.resources.live-streams.qr-code', compact('qr', 'liveStream'));
        };
    }

    private static function createQrCode(string $url, string $streamKey): HtmlString|string|null
    {
        return QrCode::size(250)
            ->generate(json_encode([
                'url' => $url,
                'stream_key' => $streamKey
            ]));
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiveStreams::route('/'),
            'create' => Pages\CreateLiveStream::route('/create'),
            'edit' => Pages\EditLiveStream::route('/{record}/edit'),
        ];
    }

    private static function goLiveFromYoutube(LiveStream $record): void
    {
        if (empty($record->broadcast_id)) {
            Notification::make()
                ->title('Error')
                ->danger()
                ->body('Please wait for the broadcast to be created.')
                ->send();
            return;
        }
        $service = new YouTubeOAuthService();
        try {
            $service->transitionBroadCast($record, BroadcastStatus::LIVE);
        } catch (Exception $e) {
            $err = json_decode($e->getMessage(), true);
            Notification::make()
                ->title($err['error']['message'] ?? 'Error')
                ->danger()
                ->send();
        }
    }
}
