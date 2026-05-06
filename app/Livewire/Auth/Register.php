<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Register extends Component
{
    #[Validate('required|min:3|max:30|unique:users,username')]
    public string $username = '';

    #[Validate('required|email|unique:users,email')]
    public string $email = '';

    #[Validate('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    public function register(): void
    {
        $this->validate();

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
