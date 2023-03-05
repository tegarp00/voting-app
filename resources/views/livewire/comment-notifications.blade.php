<div
    wire:poll="getNotificationCount"
    class="relative"
    x-data="{ isOpen: false }"
>
    <button
        x-on:click="
            isOpen = !isOpen;
            if (isOpen) {
                Livewire.emit('getNotifications');
            }
        "
    >
        <svg viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 text-gray-400">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z" clip-rule="evenodd" />
        </svg>
        @if ($notificationCount > 0)
            <div class="absolute rounded-full bg-red text-white text-xxs w-6 h-6 flex justify-center items-center -top-1.5 -right-1.5 border-2">
                {{ $notificationCount }}
            </div>
        @endif
    </button>
    <ul
        x-cloak
        x-transition.origin.top
        x-show="isOpen"
        x-on:click.away="isOpen = false"
        x-on:keydown.escape.window="isOpen = false"
        class="absolute z-10 ml-8 text-left bg-white top-8 w-76 md:w-96 shadow-dialog rounded-xl -right-32 md:-right-12 max-h-128 overflow-y-auto text-gray-700"
    >
        @if ($notifications->isNotEmpty() && !$isLoading)
            @foreach ($notifications as $notification)
                <li>
                    <a
                        x-on:click.prevent="
                            isOpen = false;
                        "
                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                        class="cursor-pointer flex px-5 py-3 transition duration-150 text-xs ease-in hover:bg-gray-100"
                    >
                        <img src="{{ $notification->data['user_avatar'] }}" alt="avatar" class="w-10 h-10 rounded-full">
                        <div class="ml-4">
                            <div>
                                <span class="font-semibold">
                                    {{ $notification->data['user_name'] }}
                                </span> commented on 
                                <span class="font-semibold">
                                    {{ $notification->data['idea_title'] }}
                                </span>:
                                <span class="line-clamp-3">"{{ $notification->data['comment_body'] }}"</span>
                            </div>
                            <div class="text-xs text-gray-500 mt-2">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                </li>
            @endforeach
            <li class="border-t border-gray-300 text-center">
                <button
                    wire:click="markAllAsRead"
                    x-on:click="isOpen = false"
                    class="block font-semibold px-5 py-4 transition duration-150 text-xs ease-in hover:bg-gray-100 w-full"
                >
                    Mark all as read
                </button>
            </li>
        @elseif ($isLoading)
            @foreach (range(1, 3) as $item)
                <li class="animate-pulse flex items-center transition duration-150 ease-in px-5 py-3">
                    <div class="bg-gray-200 rounded-xl w-10 h-10"></div>
                    <div class="ml-4 flex-1 space-y-2">
                        <div class="bg-gray-200 w-full rounded h-2"></div>
                        <div class="bg-gray-200 w-full rounded h-2"></div>
                        <div class="bg-gray-200 w-1/3 rounded h-1.5"></div>
                    </div>
                </li>
            @endforeach
        @else
            <li class="mx-auto py-6 w-40">
                <img src="{{ asset('img/no-idea.svg') }}" alt="No ideas" class="mx-auto mix-blend-luminosity">
                <div class="mt-6 font-bold text-center text-gray-400">
                    No new notifications
                </div>
            </li>
        @endif
    </ul>
</div>