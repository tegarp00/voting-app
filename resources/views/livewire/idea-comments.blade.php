<div>
    @if (!$comments->isNotEmpty())
        <div class="mx-auto mt-12 w-70">
            <img src="{{ asset('img/no-idea.svg') }}" alt="No ideas" class="mx-auto mix-blend-luminosity">
            <div class="mt-6 font-bold text-center text-gray-400">
                No comments yet ...
            </div>
        </div>
    @else
        <div class="relative pt-4 my-8 mt-1 space-y-6 comments-container md:ml-22">
            @foreach ($comments as $comment)
                <livewire:idea-comment
                    :key="$comment->id"
                    :comment="$comment"
                    :ideaUserId="$idea->user->id"
                >
            @endforeach
        </div> <!-- end comments-container -->

        <div class="my-8 md:ml-22">
            {{ $comments->onEachSide(1)->links() }}
        </div>
    @endif
</div>