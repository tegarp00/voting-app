<div
    x-data="{ isOpen: false }"
    @keydown.escape.window="isOpen = false"
    x-init="
        Livewire.on('commentWasAdded', () => {
            isOpen = false;
        });

        Livewire.hook('message.processed', (message, component) => {
            {{-- Pagination --}}
            if (
                ['gotoPage', 'previousPage', 'nextPage'].includes(message.updateQueue[0].method)
            ) {
                firstComment = document.querySelector('.comment-container:first-child');
                firstComment.scrollIntoView({ behavior: 'smooth'});
            }

            {{-- Adding Comment --}}
            if (['commentWasAdded', 'statusWasUpdated'].includes(message.updateQueue[0].payload.event)
                && message.component.fingerprint.name === 'idea-comments'
            ) {
                lastComment = document.querySelector('.comment-container:last-child');
                lastComment.scrollIntoView({ behavior: 'smooth' });
                lastComment.classList.toggle('bg-green-50');
                lastComment.classList.toggle('bg-white');
                setTimeout(() => {
                    lastComment.classList.toggle('bg-green-50');
                    lastComment.classList.toggle('bg-white');
                }, 5000);
            }
        });

        @if (session('scrollToComment'))
            commentToScrollTp = document.querySelector('#comment-{{ session('scrollToComment') }}');
            commentToScrollTp.scrollIntoView({ behavior: 'smooth' });
            commentToScrollTp.classList.toggle('bg-green-50');
            commentToScrollTp.classList.toggle('bg-white');
            setTimeout(() => {
                commentToScrollTp.classList.toggle('bg-green-50');
                commentToScrollTp.classList.toggle('bg-white');
            }, 5000);
        @endif
    "
    class="relative"
>
    <button
        x-on:click="
            isOpen = !isOpen;
            if (isOpen) {
                $nextTick(() => $refs.comment.focus());
            }
        "
        type="button"
        class="flex items-center justify-center w-32 px-6 py-3 text-sm font-semibold text-white transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover"
    >
        Reply
    </button>
    <div
        x-cloak
        x-transition.origin.top.left
        x-show="isOpen"
        x-on:click.away="isOpen = false"
        x-on:keydown.escape.window="isOpen = false"
        class="absolute z-10 w-64 mt-2 text-sm font-semibold text-left bg-white md:w-104 shadow-dialog rounded-xl"
    >
        @auth
            <form wire:submit.prevent="addComment" action="" method="post" class="px-4 py-6 space-y-4">
                <div>
                    <textarea
                        x-ref="comment"
                        wire:model="comment"
                        name="post_comment"
                        id="post_comment"
                        cols="30"
                        rows="4"
                        class="w-full px-4 py-2 text-sm bg-gray-100 border-none rounded-xl placeholder:text-gray-900"
                        placeholder="Go ahead, don't be shy. Share your thoughts..."
                        required
                    ></textarea>

                    @error('comment')
                        <p class="text-red text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="flex flex-col items-center md:flex-row md:space-x-3">
                    <button
                    type="submit"
                    class="flex items-center justify-center w-full px-6 py-3 text-sm font-semibold text-white transition duration-150 ease-in border h-11 md:w-1/2 bg-blue rounded-xl border-blue hover:bg-blue-hover"
                    >
                        Post Comment
                    </button>
                    <button type="button" class="flex items-center justify-center w-full px-6 py-3 mt-2 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 md:w-32 h-11 rounded-xl hover:border-gray-400 md:mt-0">
                        <svg class="w-4 text-gray-600 -rotate-45" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" />
                        </svg>
                        <span class="ml-1"> Attach</span>
                    </button>
                </div>
            </form>
        @else
            <div class="px-4 py-6">
                <p class="font-normal">
                    Please login or create an account to post a comment.
                </p>
                <div class="flex items-center space-x-3 mt-8">
                    <a
                        wire:click.prevent="redirectToLogin"
                        class="w-1/2 h-11 text-sm text-center bg-blue text-white font-semibold rounded-xl hover:bg-blue-hover transition duration-150 ease-in px-6 py-3"
                    >
                        Login
                    </a>
                    <a
                        wire:click.prevent="redirectToRegister"
                        class="flex items-center justify-center w-1/2 h-11 text-xs text-center bg-gray-200 font-semibold border border-gray-200 rounded-xl hover:bg-gray-400 transition duration-150 ease-in px-6 py-3"
                    >
                        Register
                    </a>
                </div>
            </div>
        @endauth
    </div>
</div>