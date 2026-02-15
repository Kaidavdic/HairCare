<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $salon = $user->salon;

        $stats = [
            'totalRevenue' => 0,
            'monthlyRevenue' => 0,
            'monthlyAppointments' => 0,
            'monthlyTrend' => 0,
            'averageRating' => 0,
            'totalReviews' => 0,
            'recentAppointments' => collect(),
            'popularServices' => collect(),
        ];

        if ($salon) {
            // Stats
            $stats['totalRevenue'] = $salon->appointments()
                ->where('status', 'completed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');

            $stats['monthlyRevenue'] = $salon->appointments()
                ->where('status', 'completed')
                ->where('appointments.scheduled_at', '>=', Carbon::now()->startOfMonth())
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price');

            $stats['monthlyAppointments'] = $salon->appointments()
                ->where('scheduled_at', '>=', Carbon::now()->startOfMonth())
                ->count();

            // Previous month for trend
            $lastMonthAppointments = $salon->appointments()
                ->whereBetween('scheduled_at', [
                    Carbon::now()->subMonth()->startOfMonth(),
                    Carbon::now()->subMonth()->endOfMonth()
                ])
                ->count();
            
            if ($lastMonthAppointments > 0) {
                $stats['monthlyTrend'] = round((($stats['monthlyAppointments'] - $lastMonthAppointments) / $lastMonthAppointments) * 100);
            } else {
                $stats['monthlyTrend'] = $stats['monthlyAppointments'] > 0 ? 100 : 0;
            }

            $stats['averageRating'] = $salon->average_rating;
            $stats['totalReviews'] = $salon->reviews_count;

            // lists
            $stats['recentAppointments'] = $salon->appointments()
                ->with(['client', 'service'])
                ->where('scheduled_at', '>=', Carbon::now())
                ->where('status', 'scheduled')
                ->orderBy('scheduled_at')
                ->take(5)
                ->get();

            $stats['popularServices'] = $salon->services()
                ->withCount('appointments')
                ->orderByDesc('appointments_count')
                ->take(5)
                ->get();
        }

        return view('owner.dashboard', array_merge(['salon' => $salon], $stats));
    }
}
