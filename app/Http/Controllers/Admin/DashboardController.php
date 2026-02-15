<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salon;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalSalons' => Salon::count(),
            'pendingSalons' => Salon::where('status', 'pending')->count(),
            'totalAppointments' => Appointment::count(),
            'completedAppointments' => Appointment::where('status', 'completed')->count(),
            'totalRevenue' => Appointment::where('appointments.status', 'completed')
                ->join('services', 'appointments.service_id', '=', 'services.id')
                ->sum('services.price'),
            'recentUsers' => User::latest()->take(5)->get(),
            'topSalons' => Salon::withCount('appointments')
                ->orderByDesc('appointments_count')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $stats);
    }
}
