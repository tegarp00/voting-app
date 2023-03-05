@props([
    'type' => 'success',
    'redirect' => false,
    'messageToDisplay' => '',
])

<div
    x-cloak
    x-data="{
        isOpen: false,
        isError: @if ($type === 'error') true @else false @endif,
        messageToDisplay: '{{ $messageToDisplay }}',
        showNotification(message) {
            this.isOpen = true;
            this.messageToDisplay = message;
            setTimeout(() => {
                this.isOpen = false;
            }, 5000);
        }
    }"
    x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-8"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-8"
    @keydown.escape.window="isOpen = false"
    x-init="
        @if($redirect)
            $nextTick(() => showNotification(messageToDisplay));
        @else
            [
                'ideaWasUpdated',
                'ideaWasMarkedAsSpam',
                'ideaWasMarkedAsNotSpam',
                'statusWasUpdated',
                'statusWasUpdatedError',
                'commentWasAdded',
                'commentWasUpdated',
                'commentWasDeleted',
                'commentWasMarkedAsSpam',
                'commentWasMarkedAsNotSpam',
            ].forEach((event) => {
                Livewire.on(event, (message) => {
                    isError = event === 'statusWasUpdatedError';
                    showNotification(message);
                });
            });
        @endif
    "
    class="flex justify-between z-20 max-w-xs sm:max-w-sm w-full fixed bottom-0 right-0 bg-white rounded-xl shadow-lg border px-4 py-5 mx-2 md:mx-6 my-8"
>
    <div class="flex items-center">
        <svg x-show="!isError" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-green w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    
        <svg x-show="isError" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-red w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="ml-2 font-semibold text-gray-500 text-sm smtext-base" x-text="messageToDisplay"></span>
    </div>
    <button x-on:click="isOpen = false" class="text-gray-400 hover:text-gray-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>