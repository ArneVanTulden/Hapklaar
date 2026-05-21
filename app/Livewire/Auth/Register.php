<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|min:3|max:15|unique:users,username')]
    public string $username = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $key = 'register:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', "Te veel pogingen. Probeer het over {$seconds} seconden opnieuw.");
            return;
        }

        RateLimiter::hit($key, 60);

        $this->validate();

        $domain = substr(strrchr($this->email, '@'), 1);
        if (! checkdnsrr($domain, 'MX') && ! checkdnsrr($domain, 'A')) {
            $this->addError('email', 'Dit e-mailadres bestaat niet.');
            return;
        }

        $user = User::create([
            'username' => $this->username,
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        Auth::login($user);

        $this->redirect(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
