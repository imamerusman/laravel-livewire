<?php

namespace App\Livewire;

use App\Models\FigmaCategory;
use App\Models\FigmaDesign;
use App\Models\SplashScreen;
use App\Models\UserAppPreference;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Onboarding extends Component implements HasForms
{
    use InteractsWithForms;
    use WithPagination;
    public $figmaCategories;
    public $perPage = 8;
    public $splashScreens;

    /*public ?array $data = [];*/
/*    #[Rule('required| string', onUpdate: false)]
    public $splash = '';*/
    #[Rule('required| string', onUpdate: false)]
    public $primaryColor = '';

    #[Rule('required| string', onUpdate: false)]
    public $secondaryColor = '';

    #[Rule('required| string', onUpdate: false)]
    public $theme = '';


    #[Rule('required| string')]
    public $splashId = '';

    public array|null $media = null;

    public bool $editingLogo = false;


    public function loadMore()
    {
        $this->perPage = $this->perPage + 8;
    }
    public function mount(): void
    {
        $this->form->fill();
        //all figma categories
        $this->figmaCategories = FigmaCategory::query()->OrderBy('id', 'asc')->get();
      /*  //all Splash Screen
        $this->splashScreens = SplashScreen::query()->OrderBy('id', 'asc')->get();*/

        $currentUser = auth()->user();
        $userAppPreference = UserAppPreference::query()?->where('user_id', $currentUser->id)->first();
        $logoImage = $userAppPreference?->getFirstMediaUrl(UserAppPreference::MEDIA_COLLECTION);
        if (filled($logoImage)) {
            $this->editingLogo = true;
        }
        if ($userAppPreference) {
            $this->primaryColor = $userAppPreference->primary_color;
            $this->secondaryColor = $userAppPreference->secondary_color;
            $this->theme = $userAppPreference->theme;
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('media')
                    ->label('Upload your logo')
                    ->image()
                    ->avatar()
                    ->collection(UserAppPreference::MEDIA_COLLECTION)
            ])->statePath('media');
    }


    public function completeSteps(): void
    {
        //Todo: save the selected figma design and figma category
        $currentUser = auth()->user();
        $userPreference =  UserAppPreference::updateOrCreate(
            ['user_id' => $currentUser->id],
            [
                'theme' => $this->figmaDesigns->firstWhere('name', $this->theme)->id,
                'primary_color' => $this->primaryColor,
                'secondary_color' => $this->secondaryColor,
                'is_completed' => true,
            ]
        );

        $firstMedia = array_shift($this->media);
        $media = array_shift($firstMedia);
        if (!empty($media))
        {
            try {
                $userPreference->addMedia($media)
                    ->toMediaCollection(UserAppPreference::MEDIA_COLLECTION);
            } catch (FileDoesNotExist|FileIsTooBig $e) {
                Notification::make()
                    ->title('Uh oh!')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        }

        Notification::make()
            ->title('Congratulations!')
            ->body('You have completed the onboarding steps.')
            ->success()
            ->send();
        $this->goToPricing();
    }
    public function goToPricing()
    {

        return redirect()->route('pricing');
    }

    public function render() : View
    {
        $figmaDesigns = FigmaDesign::query()->OrderBy('id', 'asc')->paginate($this->perPage);
        return view('livewire.onboarding',
        ['figmaDesigns' => $figmaDesigns]
        );
    }

    public function placeholder()
    {
        return <<<'HTML'

        <div>
        <style>
            .custom-loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #F4B41A;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
                margin: 0 auto;
                margin-top: 20%;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

            <!-- Loading spinner... -->
           <div class="custom-loader"></div>
        </div>
        HTML;
    }
}
