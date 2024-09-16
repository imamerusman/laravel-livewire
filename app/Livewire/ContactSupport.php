<?php

namespace App\Livewire;

use App\Models\Contact;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Livewire\Component;

/**
 * @property Form $form
 */
class ContactSupport extends Component
{
    public string $email = '';

    public function submit(): void
    {
        Contact::create([
            'email' => $this->email,
            'message' => "I'm interested in learning more about your services.",
            'name' => \Str::before($this->email, '@'),
            'phone' => '555-555-5555',
            'surname' => \Str::before($this->email, '@'),
        ]);
        Notification::make()
            ->title('Welcome!')
            ->body("We'll be in touch with you shortly at $this->email.")
            ->info()
            ->send();
        $this->email = '';
    }
    public function render()
    {
        return view('livewire.contact-support');
    }
}
