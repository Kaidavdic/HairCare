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
        // Send notification to salon owner
        \App\Models\Notification::create([
            'user_id' => $salon->owner_id,
            'type' => 'warning',
            'title' => 'Salon odbijen',
            'content' => 'Vaš salon "' . $salon->name . '" je odbijen od strane administratora i uklonjen iz sistema.',
            'is_visible' => true,
        ]);

        // Delete the salon (cascade will handle related records)
        $salon->delete();

        return back()->with('status', 'Salon je odbijen i obrisan.');
    }

    public function destroy(Salon $salon): RedirectResponse
    {
        $salon->delete();

        return back()->with('status', 'Salon je uspešno obrisan.');
    }
}

