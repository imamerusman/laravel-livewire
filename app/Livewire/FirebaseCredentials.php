<?php

namespace App\Livewire;

use App\Models\FirebaseCredential;
use App\Rules\ValidateFCMServiceAccountRule;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FirebaseCredentials extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithTable, InteractsWithForms, InteractsWithActions;

    #[Rule(new ValidateFCMServiceAccountRule())]
    public ?array $service_account_files = [];

    protected string $view = 'livewire.firebase-credentials';

    public function mount(): void
    {
        $this->form->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(auth()->user()->firebaseCredentials()->getQuery())
            ->columns([
                TextColumn::make('type')
                    ->label('Firebase Account Attached'),
                TextColumn::make('created_at')->since(),
                TextColumn::make('last_used_at')->since()
            ])
            ->actions([
                Action::make('delete')
                    ->action(fn(FirebaseCredential $credential) => $credential->delete())
                    ->label('')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hold Up!')
                    ->modalDescription('Are You Sure you want to detach this account?')
                    ->icon('heroicon-o-trash')
            ])->paginated(false)
            ->emptyStateHeading('No Firebase Credential');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('service_account_files')
                    ->label('Firebase Credential File')
                    ->acceptedFileTypes(['application/json'])
                    ->maxSize(1024)
            ]);
    }

    public function updateServiceAccountFile(): void
    {
        $this->validate();
        $user = auth('web')->user();
        $file = $this->getServiceAccountFile();

        if (isset($file)) {
            $data = json_decode(file_get_contents($file->getRealPath()), true);
            $user->firebaseCredentials()->updateOrCreate([
                'private_key' => @$data['private_key'],
                'private_key_id' => @$data['private_key_id'],
            ], [
                'type' => @$data['type'],
                'client_email' => @$data['client_email'],
                'project_id' => @$data['project_id'],
                'client_id' => @$data['client_id'],
                'auth_uri' => @$data['auth_uri'],
                'token_uri' => @$data['token_uri'],
                'auth_provider_x509_cert_url' => @$data['auth_provider_x509_cert_url'],
                'client_x509_cert_url' => @$data['client_x509_cert_url'],
            ]);

            Notification::make()->success()
                ->title('Service Account File Updated')
                ->body('The service account file has been updated.')
                ->send();
        } else {
            Notification::make()
                ->warning()
                ->title('Service Account File Not Found')
                ->body('The service account file not found.')
                ->send();
        }


    }
    public function getServiceAccountFile(): ?TemporaryUploadedFile
    {
        return collect($this->service_account_files)->first();
    }

    public static function canView()
    {
        return auth()->user()->hasRole('user');
    }

    public static function getSort () : string
    {
        return auth()->user()->id;
    }
}
