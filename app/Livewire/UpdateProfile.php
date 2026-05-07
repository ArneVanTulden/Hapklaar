<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateProfile extends Component
{
    public string $username = '';
    public string $bio = '';
    public bool $saved = false;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->username = $user->username;
        $this->bio = $user->bio ?? '';
    }

    public function save(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->validate([
            'username' => 'required|min:3|max:30|unique:users,username,' . $user->id,
            'bio'      => 'nullable|max:255',
        ]);

        $user->update([
            'username' => $this->username,
            'bio'      => $this->bio,
        ]);

        session()->flash('profile_saved', true);
        $this->redirect(route('profiel'));
    }

    public function render()
    {
        return view('livewire.update-profile');
    }
}
