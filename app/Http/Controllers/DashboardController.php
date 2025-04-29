<?php  

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact; // ejemplo
use App\Models\Review;  // ejemplo
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getStats()
    {
        $user = auth()->user();
        $businessId = $user->business->id;
    
        $contacts = \App\Models\Contact::where('business_id', $businessId)->count();
        $reviews = \App\Models\Review::where('business_id', $businessId)->count();
    
        $conversion = $contacts > 0 ? ($reviews / $contacts) * 100 : 0;
        $conversion = round($conversion, 2);
    
        return response()->json([
            'clients' => $contacts,
            'reviews' => $reviews,
            'conversion' => $conversion,
        ]);
    }
    public function getRecentActions()
    {
        $user = auth()->user();
        $businessId = $user->business->id;
    
        $contacts = Contact::where('business_id', $businessId)
                    ->latest()
                    ->take(10)
                    ->get();
    
                    $actions = $contacts->map(function($contact) {
                        $message = \App\Models\Message::where('contact_id', $contact->id)->latest()->first();
                    
                        if ($message) {
                            if ($message->status === 'sent') {
                                $result = "Mensaje enviado";
                                $resultClass = "positivo";
                            } elseif ($message->status === 'failed') {
                                $result = "Error en el envío";
                                $resultClass = "negativo";
                            } else {
                                $result = "Estado desconocido";
                                $resultClass = "neutral";
                            }
                        } else {
                            $result = "Sin intento de envío";
                            $resultClass = "neutral";
                        }
                    
                        return [
                            "business" => $contact->business->name ?? 'Negocio desconocido',
                            "client" => $contact->client_name,
                            "phone" => $contact->client_phone,
                            "response" => $contact->message ?? "Sin respuesta",
                            "result" => $result,
                            "resultClass" => $resultClass,
                            "date" => $contact->created_at->format('d/m/Y'),
                        ];
                    });
                    
    
        return response()->json($actions);
    }
    public function getMessages()
    {
        $user = auth()->user();
        $businessId = $user->business->id;
    
        $messages = \App\Models\Message::with('contact')
            ->where('business_id', $businessId)
            ->latest()
            ->take(10)
            ->get();
    
        return response()->json($messages);
    }
    public function getExtendedStats()
    {
        $user = auth()->user();
        $businessId = $user->business->id;

        // Actividad mensual
        $contactsPerMonth = Contact::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                            ->where('business_id', $businessId)
                            ->groupBy('month')
                            ->pluck('total', 'month')
                            ->toArray();

        $reviewsPerMonth = Review::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                            ->where('business_id', $businessId)
                            ->groupBy('month')
                            ->pluck('total', 'month')
                            ->toArray();

        // Distribución de reseñas
        $distribution = [
            '5 estrellas' => Review::where('business_id', $businessId)->where('rating', 5)->count(),
            '4 estrellas' => Review::where('business_id', $businessId)->where('rating', 4)->count(),
            '3 estrellas' => Review::where('business_id', $businessId)->where('rating', 3)->count(),
            '2 estrellas' => Review::where('business_id', $businessId)->where('rating', 2)->count(),
            '1 estrella' => Review::where('business_id', $businessId)->where('rating', 1)->count(),
        ];

        // Métricas clave
        $totalContacts = Contact::where('business_id', $businessId)->count();
        $totalReviews = Review::where('business_id', $businessId)->count();
        $positiveReviews = Review::where('business_id', $businessId)->where('rating', '>=', 4)->count();

        $conversion = $totalContacts > 0 ? round(($totalReviews / $totalContacts) * 100, 2) : 0;
        $positiveRate = $totalReviews > 0 ? round(($positiveReviews / $totalReviews) * 100, 2) : 0;
        $averageReviewsPerClient = $totalContacts > 0 ? round($totalReviews / $totalContacts, 2) : 0;

        return response()->json([
            'monthly' => [
                'contacts' => $contactsPerMonth,
                'reviews' => $reviewsPerMonth,
            ],
            'distribution' => $distribution,
            'metrics' => [
                'conversion' => $conversion,
                'positiveRate' => $positiveRate,
                'averageReviewsPerClient' => $averageReviewsPerClient,
                'retention' => 78, // Puedes calcularlo mejor o dejarlo fijo por ahora
            ],
        ]);
    }

}
