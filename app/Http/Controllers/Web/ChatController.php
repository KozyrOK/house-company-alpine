<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\Chat\ChatHistoryService;
use App\Services\Chat\ChatService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    public function __construct(private ChatService $chat, private ChatHistoryService $history) {}

    public function index() { return view('pages.chat'); }
    public function store(Request $request) { return response()->json($this->chat->start($request->user(), $request->string('agent', 'support'))->load('messages')); }
    public function conversations(Request $request) { return response()->json($this->history->conversations($request->user())); }
    public function show(Request $request, Conversation $conversation) { return response()->json($this->history->get($request->user(), $conversation)); }
    public function message(Request $request, Conversation $conversation) { $data = $request->validate(['message' => ['required', 'string', 'max:4000']]); return response()->json($this->chat->send($request->user(), $conversation, $data['message'])); }

    public function stream(Request $request, Conversation $conversation): StreamedResponse
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:4000']]);
        $payload = $this->chat->send($request->user(), $conversation, $data['message']);

        return response()->stream(function () use ($payload) {
            echo 'data: '.json_encode($payload)."\n\n";
            flush();
        }, 200, ['Content-Type' => 'text/event-stream', 'Cache-Control' => 'no-cache']);
    }
}