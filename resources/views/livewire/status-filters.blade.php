<nav class="items-center justify-between hidden text-xs text-gray-400 md:flex">
    <ul class="flex pb-3 space-x-10 font-semibold uppercase border-b-4">
        <li>
            <a wire:click.prevent="setStatus('All')" href="{{ route('idea.index', ['status' => 'All']) }}" class="pb-3 border-b-4 hover:border-blue @if ($status === 'All') border-blue text-gray-900 @endif">All Ideas ({{ $statusCount['all_statuses'] }})</a>
        </li>
        <li>
            <a wire:click.prevent="setStatus('Considering')" href="{{ route('idea.index', ['status' => 'Considering']) }}" class="pb-3 transition duration-150 ease-in border-b-4 hover:border-blue @if ($status === 'Considering') border-blue text-gray-900 @endif">Considering ({{ $statusCount['considering'] }})</a>
        </li>
        <li>
            <a wire:click.prevent="setStatus('In Progress')" href="{{ route('idea.index', ['status' => 'In Progress']) }}" class="pb-3 transition duration-150 ease-in border-b-4 hover:border-blue @if ($status === 'In Progress') border-blue text-gray-900 @endif">In Progress ({{ $statusCount['in_progress'] }})</a>
        </li>
    </ul>
    <ul class="flex pb-3 space-x-10 font-semibold uppercase border-b-4">
        <li>
            <a wire:click.prevent="setStatus('Implemented')" href="{{ route('idea.index', ['status' => 'Implemented']) }}" class="pb-3 transition duration-150 ease-in border-b-4 hover:border-blue @if ($status === 'Implemented') border-blue text-gray-900 @endif">Implemented ({{ $statusCount['implemented'] }})</a>
        </li>
        <li>
            <a wire:click.prevent="setStatus('Closed')" href="{{ route('idea.index', ['status' => 'Closed']) }}" class="pb-3 transition duration-150 ease-in border-b-4 hover:border-blue @if ($status === 'Closed') border-blue text-gray-900 @endif">Closed ({{ $statusCount['closed'] }})</a>
        </li>
    </ul>
</nav>