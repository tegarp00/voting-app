<div
    x-data="{ isOpen: false }"
    x-init="
        Livewire.on('statusWasUpdated', () => {
            isOpen = false;
        });

        Livewire.on('statusWasUpdatedError', () => {
            isOpen = false;
        });
    "
    class="relative"
>
    <button
        x-on:click="isOpen = !isOpen"
        type="button"
        class="flex items-center justify-center px-6 py-3 mt-2 text-sm font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 w-36 h-11 rounded-xl hover:border-gray-400 md:mt-0"
    >
        <span>Set Status</span>
        <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div
        x-cloak
        x-transition.origin.top.left
        x-show="isOpen"
        x-on:click.away="isOpen = false"
        x-on:keydown.escape.window="isOpen = false"
        class="absolute z-20 w-64 mt-2 text-sm font-semibold text-left bg-white md:w-76 shadow-dialog rounded-xl"
    >
        <form wire:submit.prevent="setStatus" action="" method="post" class="px-4 py-6 space-y-4">
            <div class="space-y-2">
                <div>
                    <label class="inline-flex items-center">
                        <input
                            wire:model="status"
                            type="radio"
                            class="text-gray-600 bg-gray-200 border-none"
                            name="status"
                            value="1"
                            checked
                        >
                        <span class="ml-2">Open</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input
                            wire:model="status"
                            type="radio"
                            class="bg-gray-200 border-none text-purple"
                            name="status"
                            value="2"
                        >
                        <span class="ml-2">Considering</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input
                            wire:model="status"
                            type="radio"
                            class="bg-gray-200 border-none text-yellow"
                            name="status"
                            value="3"
                        >
                        <span class="ml-2">In Progress</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input
                            wire:model="status"
                            type="radio"
                            class="bg-gray-200 border-none text-green"
                            name="status"
                            value="4"
                        >
                        <span class="ml-2">Implemented</span>
                    </label>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input
                            wire:model="status"
                            type="radio"
                            class="bg-gray-200 border-none text-red"
                            name="status"
                            value="5"
                        >
                        <span class="ml-2">Closed</span>
                    </label>
                </div>
            </div>

            <div>
                <textarea wire:model="comment" name="update_comment" id="update_comments" cols="30" rows="3" class="w-full px-4 py-2 text-sm placeholder-gray-900 bg-gray-100 border-none rounded-xl" placeholder="Add an update comment (optional)"></textarea>
            </div>

            <div class="flex items-center justify-between space-x-3">
                <button
                    type="button"
                    class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold transition duration-150 ease-in bg-gray-200 border border-gray-200 h-11 rounded-xl hover:border-gray-400"
                >
                    <svg class="w-4 text-gray-600 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    <span class="ml-1">Attach</span>
                </button>
                <button
                    type="submit"
                    class="flex items-center justify-center w-1/2 px-6 py-3 text-xs font-semibold text-white transition duration-150 ease-in border h-11 bg-blue rounded-xl border-blue hover:bg-blue-hover disabled:opacity-50"
                >
                    <span class="ml-1">Update</span>
                </button>
            </div>

            <div>
                <label class="inline-flex items-center font-normal">
                    <input wire:model="notifyAllVoters" type="checkbox" name="notify_voters" class="bg-gray-200 rounded">
                    <span class="ml-2">Notify all voters</span>
                </label>
            </div>
        </form>
    </div>
</div>