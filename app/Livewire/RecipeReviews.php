<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class RecipeReviews extends Component
{
    public int $recipeId;

    public string $sort = 'recent';

    public bool $showForm = false;

    #[Validate('required|integer|min:1|max:5')]
    public int $rating = 0;

    #[Validate('nullable|string|max:255')]
    public string $title = '';

    #[Validate('required|string|min:5|max:5000')]
    public string $body = '';

    public function mount(int $recipeId): void
    {
        $this->recipeId = $recipeId;
    }

    public function toggleForm(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }
        $this->showForm = ! $this->showForm;
    }

    public function setRating(int $value): void
    {
        $this->rating = max(0, min(5, $value));
    }

    public function setSort(string $sort): void
    {
        $this->sort = in_array($sort, ['recent', 'highest', 'lowest'], true) ? $sort : 'recent';
    }

    public function submit(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));
            return;
        }

        $this->validate();

        Review::create([
            'recipe_id' => $this->recipeId,
            'user_id'   => Auth::id(),
            'rating'    => $this->rating,
            'title'     => $this->title ?: null,
            'body'      => $this->body,
            'status'    => 'approved',
        ]);

        $this->recalcRecipeStats();

        $this->reset(['rating', 'title', 'body', 'showForm']);
        session()->flash('review_success', 'Review geplaatst.');
    }

    public function deleteReview(int $reviewId): void
    {
        $review = Review::find($reviewId);
        if (! $review) {
            return;
        }
        $user = Auth::user();
        if (! $user || ($review->user_id !== $user->id && ($user->role ?? 'user') !== 'admin')) {
            return;
        }
        $review->delete();
        $this->recalcRecipeStats();
    }

    protected function recalcRecipeStats(): void
    {
        $recipe = Recipe::find($this->recipeId);
        if (! $recipe) {
            return;
        }
        $approved = $recipe->reviews()->where('status', 'approved');
        $count    = (clone $approved)->count();
        $avg      = $count ? round((clone $approved)->avg('rating'), 2) : 0;
        $recipe->update([
            'avg_rating'   => $avg,
            'review_count' => $count,
        ]);
    }

    public function render()
    {
        $query = Review::with(['user', 'photos'])
            ->where('recipe_id', $this->recipeId)
            ->where('status', 'approved');

        $query = match ($this->sort) {
            'highest' => $query->orderByDesc('rating')->orderByDesc('created_at'),
            'lowest'  => $query->orderBy('rating')->orderByDesc('created_at'),
            default   => $query->orderByDesc('created_at'),
        };

        $reviews = $query->get();
        $total   = $reviews->count();
        $avg     = $total ? round($reviews->avg('rating'), 1) : 0;

        $buckets = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($reviews as $r) {
            $buckets[$r->rating] = ($buckets[$r->rating] ?? 0) + 1;
        }

        $hasOwn = Auth::check() && Review::where('recipe_id', $this->recipeId)
            ->where('user_id', Auth::id())
            ->exists();

        return view('livewire.recipe-reviews', [
            'reviews' => $reviews,
            'total'   => $total,
            'avg'     => $avg,
            'buckets' => $buckets,
            'hasOwn'  => $hasOwn,
        ]);
    }
}
