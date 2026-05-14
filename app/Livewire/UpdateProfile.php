<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfile extends Component
{
    use WithFileUploads;

    public string $username = '';
    public string $bio = '';
    public $avatar;

    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();
        $this->username = $user->username;
        $this->bio = $user->bio ?? '';
    }

    public function updatedAvatar(): void
    {
        $this->validate(['avatar' => 'image|max:2048']);

        /** @var User $user */
        $user = Auth::user();
        $path = $this->avatar->store('avatars', 'public');
        $user->update(['avatar_url' => $path]);

        $this->redirect(route('profiel'));
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
        $this->redirectRoute('profiel');
    }

    public function render()
    {
        return view('livewire.update-profile');
    }
}
