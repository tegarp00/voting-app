<div class="idea-and-buttons-container">
    <div class="flex mt-4 bg-white idea-container rounded-xl">
        <div class="flex flex-col flex-1 px-4 py-6 md:flex-row">
            <div class="flex-none mx-2">
                <a href="#">
                    <img src="{{ $idea->user->avatar }}" alt="avatar" class="w-14 h-14 rounded-xl">
                </a>
            </div>
            <div class="w-full mx-2 md:mx-4">
                <h4 class="mt-2 text-xl font-semibold md:mt-0">
                    {{ $idea->title }}
                </h4>
                <div class="mt-3 text-gray-600">
                    @admin
                        @if($idea->spam_reports > 0)
                            <div class="text-red mb-2">
                                Spam Reports: {{ $idea->spam_reports }}
                            </div>
                        @endif
                    @endadmin
                    {!! nl2br(e($idea->description)) !!}
                </div>

                <div class="flex flex-col justify-between mt-6 md:flex-row md:items-center">
                    <div class="flex items-center space-x-2 text-xs font-semibold text-gray-400">
                        <div class="hidden font-bold text-gray-900 md:block">{{ $idea->user->name }}</div>
                        <div class="hidden md:block">&bull;</div>
                        <div>{{ $idea->created_at->diffForHumans() }}</div>
                        <div>&bull;</div>
                        <div>{{ $idea->category->name }}</div>
                        <div>&bull;</div>
                        <div class="text-gray-900">{{ $idea->comments()->count() }} comments</div>
                    </div>
                    <div
                        x-data="{ isOpen: false }"
                        class="flex items-center mt-4 space-x-2 md:mt-0"
                    >
                        <div class="{{ 'status-' . Str::kebab($idea->status->name) }} text-xxs font-bold uppercase leading-none rounded-full text-center w-28 h-7 py-2 px-4">
                            {{ $idea->status->name }}
                        </div>
                        @auth
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
                                    class="absolute right-0 z-10 py-3 ml-8 font-semibold text-left bg-white top-6 w-44 shadow-dialog rounded-xl md:ml-8 md:top-6 md:left-0"
                                >
                                    @can('update', $idea)
                                        <li>
                                            <a
                                                @click.prevent="
                                                    isOpen = false;
                                                    $dispatch('custom-show-edit-modal');
                                                "
                                                href="#"
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >
                                                Edit Idea
                                            </a>
                                        </li>
                                    @endcan
                                    @can('delete', $idea)
                                        <li>
                                            <a
                                                @click.prevent="
                                                    isOpen = false;
                                                    $dispatch('custom-show-delete-modal');
                                                "
                                                href="#"
                                                class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                            >
                                                Delete Idea
                                            </a>
                                        </li>
                                    @endcan
                                    <li>
                                        <a
                                            @click.prevent="
                                                isOpen = false;
                                                $dispatch('custom-show-mark-idea-as-spam-modal');
                                            "
                                            href="#"
                                            class="block px-5 py-3 transition duration-150 ease-in hover:bg-gray-100"
                                        >
                                            Mark as Spam
                                        </a>
                                    </li>
                                    @admin
                                        @if($idea->spam_reports > 0)
                                            <li>
                                                <a
                                                    @click.prevent="
                                                        isOpen = false;
                                                        $dispatch('custom-show-mark-idea-as-not-spam-modal');
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
                        @endauth
                    </div>

                    <div class="flex mt-4 md:hidden md:mt-0">
                        <div class="h-10 px-4 py-2 pr-8 text-center bg-gray-100 rounded-xl">
                            <div class="text-sm font-bold leading-none @if ($has_voted) text-blue @endif">
                                {{ $votes_count }}
                            </div>
                            <div class="font-semibold leading-none text-gray-400 text-xxs">
                                Votes
                            </div>
                        </div>
                        @if ($has_voted)
                            <button
                                wire:click.prevent="vote"
                                class="w-20 px-4 py-3 -mx-5 font-bold text-white uppercase transition duration-150 ease-in border bg-blue border-blue text-xxs rounded-xl hover:bg-blue-hover">
                                Voted
                            </button>
                        @else
                            <button
                                wire:click.prevent="vote"
                                class="w-20 px-4 py-3 -mx-5 font-bold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 text-xxs rounded-xl hover:bg-gray-400">
                                Vote
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end idea-container -->

    <div class="flex items-center justify-between mt-6 buttons-container">
        <div class="flex flex-col gap-0 ml-6 md:flex-row md:items-center md:gap-4">
            <livewire:add-comment
                :idea="$idea"
            />
            @admin
                <livewire:set-status
                    :idea="$idea"
                />
            @endadmin
        </div>

        <div class="items-center hidden space-x-3 md:flex">
            <div class="px-3 py-2 font-semibold text-center bg-white rounded-xl">
                <div class="text-xl leading-snug @if ($has_voted) text-blue @endif">
                    {{ $votes_count }}
                </div>
                <div class="text-xs leading-none text-gray-400">Votes</div>
            </div>
            @if ($has_voted)
                <button
                    wire:click.prevent="vote"
                    type="button"
                    class="w-32 px-6 py-3 text-xs font-semibold text-white uppercase transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover"
                >
                    <span>Voted</span>
                </button>
            @else
                <button
                    wire:click.prevent="vote"
                    type="button"
                    class="w-32 px-6 py-3 text-xs font-semibold uppercase transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:bg-gray-400"
                >
                    <span>Vote</span>
                </button>
            @endif
        </div>
    </div> <!-- end buttons-container -->
</div> <!-- end idea-and-buttons-container -->