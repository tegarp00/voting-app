<div>
    <div class="flex flex-col space-y-3 filters md:flex-row md:space-y-0 md:space-x-6">
        <div class="w-full md:w-1/3">
            <select
                wire:model="category"
                name="category"
                id="category"
                class="w-full px-4 py-2 border-none rounded-xl"
            >
                <option value="All Categories">All Categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/3">
            <select
            wire:model="filter"
                name="filter"
                id="filter"
                class="w-full px-4 py-2 border-none rounded-xl"
            >
                <option value="No Filter">No Filter</option>
                <option value="Top Voted">Top Voted</option>
                <option value="My Ideas">My Ideas</option>
                @admin
                    <option value="Spam Ideas">Spam Ideas</option>
                    <option value="Spam Comments">Spam Comments</option>
                @endadmin
            </select>
        </div>
        <div class="relative w-full md:w-2/3">
            <input
                wire:model="search"
                type="search"
                name="search"
                id="search"
                placeholder="Find an idea"
                class="w-full px-4 py-2 pl-8 bg-white border-none rounded-xl placeholder:text-gray-900"
            >
            <div class="absolute top-0 flex items-center h-full ml-2">
                <svg class="w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
        </div>
    </div> <!-- end filters -->
    
    <div class="my-6 space-y-6 ideas-container">
        @forelse ($ideas as $idea)
            <livewire:idea-index
                :key="$idea->id"
                :idea="$idea"
                :votes_count="$idea->votes_count"
            />
        @empty
            <div class="mx-auto mt-12 w-70">
                <img src="{{ asset('img/no-idea.svg') }}" alt="No ideas" class="mx-auto mix-blend-luminosity">
                <div class="mt-6 font-bold text-center text-gray-400">
                    No ideas were found ...
                </div>
            </div>
        @endforelse
    </div> <!-- end ideas-container -->
    
    <div class="my-8">
        {{ $ideas->links() }}
    </div>
</div>