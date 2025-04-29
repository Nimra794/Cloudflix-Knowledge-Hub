<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ChatbotService;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    protected $chatbot;

    public function __construct(ChatbotService $chatbot)
    {
        $this->chatbot = $chatbot;
    }

    public function index()
    {
        return view('chatbot.index');
    }

    public function sendMessage(Request $request)
    {
        try {
            Log::info('Chatbot request received', ['message' => $request->input('message')]);
            
            $message = $request->input('message');
            if (empty($message)) {
                Log::error('Empty message received');
                return response()->json(['response' => 'Please enter a message.'], 400);
            }

            $response = $this->chatbot->send($message);
            Log::info('Chatbot response', ['response' => $response]);

            return response()->json([
                'response' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Chatbot error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'response' => 'Sorry, there was an error processing your request.'
            ], 500);
        }
    }
} 