<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class MarkCommentAsSpam extends Component
{
    public $comment;

    protected $listeners = [
        'setMarkAsSpamComment',
    ];

    public function setMarkAsSpamComment($commentId)
    {
        $this->comment = Comment::findOrFail($commentId);

        $this->emit('markAsSpamCommentWasSet');
    }

    public function markAsSpam()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports++;
        $this->comment->save();

        $this->emit('commentWasMarkedAsSpam', 'Comment was marked as spam!');
    }
    
    public function render()
    {
        return view('livewire.mark-comment-as-spam');
    }
}
