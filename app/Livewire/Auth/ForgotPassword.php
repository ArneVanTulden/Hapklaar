<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function sendLink(): void
    {
        $key = 'forgot-password:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "Te veel pogingen. Probeer het over {$seconds} seconden opnieuw.");
            return;
        }

        RateLimiter::hit($key, 60);

        $this->validate();

        if (! User::where('email', $this->email)->exists()) {
            $this->addError('email', 'Geen account gevonden met dit e-mailadres.');
            return;
        }

        Password::sendResetLink(['email' => $this->email]);

        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
