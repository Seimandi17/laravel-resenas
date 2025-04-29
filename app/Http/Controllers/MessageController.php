<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->business) {
            return response()->json([], 200);
        }

        $messages = $user->business->messages()
        ->with('contact') 
        ->latest()
        ->get();


        return response()->json($messages);
    }
}
