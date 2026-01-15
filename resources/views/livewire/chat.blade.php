<div class="flex h-full flex-col bg-gray-900 text-white rounded-xl overflow-hidden">
    <div class="bottom-0 left-0 w-full bg-gray-800 p-4 border-gray-700">
        <form wire:submit.prevent="sendMessage" class="flex">
            <input type="text" wire:model="message" class="flex-grow rounded-l-lg p-2 border-t mr-0 border-b border-l text-white border-gray-600 bg-gray-700 placeholder-gray-400" placeholder="Type a message..."/>
            <button type="submit" class="px-8 rounded-r-lg bg-blue-500 text-white font-bold p-2 uppercase border-blue-500 border-t border-b border-r">Send</button>
        </form>
    </div>
    <div wire:ignore class="flex-grow h-0 overflow-y-auto p-4 border-t" id="messages">
        @forelse ($messages as $message)
            <div>
                <strong class="text-blue-400">{{ $message->user->name ?? 'Guest' }}</strong>: <span class="text-gray-200">{{ $message->message }}</span>
            </div>
        @empty
            <p class="text-center text-gray-400">No messages yet.</p>
        @endforelse
    </div>

</div>