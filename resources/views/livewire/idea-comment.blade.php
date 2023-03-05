<div
    id="comment-{{ $comment->id }}"
    class="@if ($comment->is_status_update) is-status-update {{ 'status-' . Str::kebab($comment->status->name) }} @endif relative flex mt-4 bg-white comment-container rounded-xl transition duration-500 ease-in"
>
    <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
        <div class="flex-none">
            <a href="#">
                <img src="{{ $comment->user->avatar }}" alt="avatar" class="w-14 h-14 rounded-xl">
            </a>
            @if ($comment->user->isAdmin())
                <div class="mt-1 font-bold text-center uppercase text-blue text-xxs">Admin</div>
            @endif
        </div>
        <div class="w-full md:mx-4">
            <div class="text-gray-600">
                @admin
                    @if($comment->spam_reports > 0)
                        <div class="text-red mb-2">
                            Spam Reports: {{ $comment->spam_reports }}
                        </div>
                    @endif
                @endadmin
                @if ($comment->is_status_update)
                    <h4 class="text-xl font-semibold mb-3">
                        Status Changed to "{{ $comment->status->name }}"
                    </h4>
                @endif
                <div>
                    {!! nl2br(e($comment->body)) !!}
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                    <div class="text-gray-900 font-bold">
                        <span class="@if ($comment->is_status_update) text-blue @endif">{{ $comment->user->name }}</span>
                    </div>
                    <div>&bull;</div>
                    @if ($comment->user->id === $ideaUserId)
                        <div class="rounded-full border bg-gray-100 px-3 py-1">OP</div>
                        <div>&bull;</div>
                    @endif
                    <div>{{ $comment->created_at->diffForHumans() }}</div>
                </div>
                @auth
                    <div
                        x-data="{ isOpen: false }"
                        class="flex items-center space-x-2"
                    >
                        <div class="relative">
                            <button
                                x-on:click="isOpen = !isOpen"
                                class="relative flex items-center px-3 py-2 transition duration-150 ease-in bg-gray-100 border rounded-full hover:bg-gray-200 h-7"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                </svg>
                            </button>
                            <ul
                                x-cloak
                                x-transition.origin.top.left
                                x-show="isOpen"
                                x-on:click.away="isOpen = false"
                                x-on:keydown.escape.window="isOpen = false"
                                class="absolute right-0 z-10 py-3 ml-8 font-semibold text-left bg-white top-8 md:top-6 w-44 shadow-dialog rounded-xl md:ml-8 md:left-0"
                            >
                                @can('update', $comment)
                                    <li>
                                        <a
                                            @click.prevent="
                                                isOpen = false;
                                                Livewire.emit('setEditComment', {{ $comment->id }});
                                            "
                                            href="#"
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                        >
                                            Edit Comment
                                        </a>
                                    </li>
                                @endcan
                                @can('delete', $comment)
                                    <li>
                                        <a
                                            @click.prevent="
                                                isOpen = false;
                                                Livewire.emit('setDeleteComment', {{ $comment->id }});
                                            "
                                            href="#"
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                        >
                                            Delete Comment
                                        </a>
                                    </li>
                                @endcan
                                <li>
                                    <a
                                        @click.prevent="
                                            isOpen = false;
                                            Livewire.emit('setMarkAsSpamComment', {{ $comment->id }});
                                        "
                                        href="#"
                                        class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                    >
                                        Mark as Spam
                                    </a>
                                </li>
                                @admin
                                    @if ($comment->spam_reports > 0)
                                        <li>
                                            <a
                                                @click.prevent="
                                                    isOpen = false;
                                                    Livewire.emit('setMarkAsNotSpamComment', {{ $comment->id }});
                                                "
                                                href="#"
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >
                                                Not Spam
                                            </a>
                                        </li>
                                    @endif
                                @endadmin
                            </ul>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div> <!-- end comment-container --> 