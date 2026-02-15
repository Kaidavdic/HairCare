<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $news = \App\Models\Notification::where('is_visible', true)->latest()->take(3)->get();
        $popularServices = \App\Models\Service::with('salon')->withCount('appointments')
            ->orderByDesc('appointments_count')
            ->take(4)
            ->get();

        // Fetch popular salons in the last month
        $popularSalons = \App\Models\Salon::with('images')
            ->withCount(['appointments' => function ($query) {
                $query->where('scheduled_at', '>=', now()->subMonth());
            }])
            ->orderByDesc('appointments_count')
            ->orderByDesc('average_rating')
            ->take(4)
            ->get();

        // Fetch promoted services
        $promotions = \App\Models\Service::with('salon')
            ->where('is_promoted', true)
            ->where('is_active', true)
            ->whereNotNull('discount_price')
            ->latest()
            ->take(6)
            ->get();

        // Salon Search Logic
        $query = \App\Models\Salon::query()
            ->where('status', 'approved')
            ->with('images')
            ->withCount('reviews');

        $sort = $request->input('sort', 'rating_desc');

        switch ($sort) {
            case 'rating_asc':
                $query->orderBy('average_rating');
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            case 'oldest':
                $query->orderBy('created_at');
                break;
            case 'rating_desc':
            default:
                $query->orderByDesc('average_rating')->orderByDesc('reviews_count');
                break;
        }

        if ($type = $request->string('type')->lower()->value()) {
            $query->where('type', $type);
        }

        if ($search = $request->string('q')->value()) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        if ($minRating = $request->float('min_rating')) {
            $query->where('average_rating', '>=', $minRating);
        }

        $salons = $query->paginate(9)->withQueryString();

        return view('welcome', [
            'news' => $news,
            'popularServices' => $popularServices,
            'popularSalons' => $popularSalons,
            'promotions' => $promotions,
            'salons' => $salons,
            'filters' => [
                'type' => $request->input('type'),
                'q' => $request->input('q'),
                'min_rating' => $request->input('min_rating'),
                'sort' => $request->input('sort'),
            ],
        ]);
    }
}
