<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Traits\WithAuthRedirect;
use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\Vote;
use Livewire\Component;
use Livewire\WithPagination;

class IdeasIndex extends Component
{
    use WithPagination, WithAuthRedirect;
    
    public $status;
    public $category;
    public $filter;
    public $search;

    protected $queryString = [
        'status',
        'category',
        'filter',
        'search',
    ];

    protected $listeners = [
        'queryStringUpdatedStatus',
    ];

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedFilter()
    {
        if ($this->filter === 'My Ideas') {
            if (auth()->guest()) {
                return $this->redirectToLogin();
            }
        }
    }

    public function queryStringUpdatedStatus($newStatus)
    {
        $this->resetPage();
        $this->status = $newStatus;
    }

    public function mount()
    {
        $this->status = request()->status ?? 'All';
    }

    public function render()
    {
        $statuses = Status::all()->pluck('id', 'name');
        $categories = Category::all();

        return view('livewire.ideas-index', [
                'ideas' => Idea::with('user', 'category', 'status')
                    ->when(
                        $this->status && $this->status !== 'All',
                        fn ($query) => $query
                            ->where('status_id', $statuses->get($this->status))
                    )
                    ->when(
                        $this->category && $this->category !== 'All Categories',
                        fn ($query) => $query
                            ->where('category_id', $categories->pluck('id', 'name')
                            ->get($this->category))
                    )
                    ->when(
                        $this->filter && $this->filter === 'Top Voted',
                        fn ($query) => $query
                            ->orderByDesc('votes_count')
                    )
                    ->when(
                        $this->filter && $this->filter === 'My Ideas',
                        fn ($query) => $query
                            ->where('user_id', auth()->id())
                    )
                    ->when(
                        $this->filter && $this->filter === 'Spam Ideas',
                        fn ($query) => $query
                            ->where('spam_reports', '>', 0)
                            ->orderByDesc('spam_reports')
                    )
                    ->when(
                        $this->filter && $this->filter === 'Spam Comments',
                        fn ($query) => $query
                            ->whereHas('comments', fn ($query) => $query
                                ->where('spam_reports', '>', 0)
                            )
                    )
                    ->when(
                        strlen($this->search) >= 3,
                        fn ($query) => $query
                            ->where('title', 'like', '%' . $this->search . '%')
                    )
                    ->addSelect([
                        'voted_by_user' => Vote::select('id')
                            ->where('user_id', auth()->id())
                            ->whereColumn('idea_id', 'ideas.id')        
                    ])
                    ->withCount('votes')
                    ->withCount('comments')
                    ->orderBy('created_at', 'desc')
                    ->simplePaginate()
                    ->withQueryString(),
                'categories' => $categories,
            ]);
    }
}
