<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On; 

class Chat extends Component
{
    public string $message = '';
    public $messages;

    public function sendMessage()
    {
        error_log("msg livewire");
        // If the logic would get any more complex than this we should separate it to a controller. Or if we want strict MVC.
        Message::create([
            'user_id' => Auth::id(),
            'message' => $this->message,
        ]);
        $this->reset('message');
    }

    public function mount()
    {
        $this->messages = Message::with('user')->latest()->take(50)->get()->reverse();
    }

    public function render()
    {
        return view('livewire.chat');
    }

    #[On('test')] 
    // We should use dispatch event with Livewire, but i'll leave it for now
    public function test() {
        error_log($this->messages->toJson());
    }
}