<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SalonApprovalController extends Controller
{
    public function index(): View
    {
        $salons = Salon::query()
            ->with('owner')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.salons.pending', [
            'salons' => $salons,
        ]);
    }

    public function approve(Salon $salon): RedirectResponse
    {
        $salon->update([
            'status' => 'approved',
        ]);

        // Also approve the owner
        $salon->owner->update(['status' => 'active']);

        return back()->with('status', 'Salon je uspešno odobren.');
    }

    public function reject(Salon $salon): RedirectResponse
    {
        $salon->update([
            'status' => 'rejected',
        ]);

        return back()->with('status', 'Salon je odbijen.');
    }

    public function destroy(Salon $salon): RedirectResponse
    {
        $salon->delete();

        return back()->with('status', 'Salon je uspešno obrisan.');
    }
}

