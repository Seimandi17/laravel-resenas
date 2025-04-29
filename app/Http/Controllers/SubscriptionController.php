<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'plan' => 'required|string|in:free,basic,pro,premium',
            'payment_email' => 'nullable|email',
            'payment_method' => 'nullable|string',
            'amount' => 'nullable|numeric',
        ]);

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'plan' => $request->plan,
            'payment_email' => $request->payment_email,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'start_date' => now(),
        ]);

        return response()->json([
            'message' => 'Suscripción registrada correctamente.',
            'subscription' => $subscription,
        ]);
    }
    public function show(Request $request)
    {
        $subscription = $request->user()->subscription;

        if (!$subscription) {
            return response()->json(null, 404);
        }

        return response()->json($subscription);
    }
    public function payments(Request $request)
    {
        $subscriptions = $request->user()->subscriptions()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($sub) {
                return [
                    'date' => $sub->created_at->format('d/m/Y'),
                    'amount' => $sub->amount,
                    'status' => $sub->payment_method ? 'Pagado' : 'Fallido',
                ];
            });

        return response()->json($subscriptions);
    }

}
