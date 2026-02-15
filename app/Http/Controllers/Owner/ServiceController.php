<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $salon = $request->user()->salon;

        abort_unless($salon, 404);

        $services = $salon->services()->orderBy('name')->get();

        return view('owner.services.index', [
            'salon' => $salon,
            'services' => $services,
        ]);
    }

    public function create(Request $request): View
    {
        $salon = $request->user()->salon;
        abort_unless($salon && $salon->status === 'approved', 403);

        return view('owner.services.create', ['salon' => $salon]);
    }

    public function store(Request $request): RedirectResponse
    {
        $salon = $request->user()->salon;
        abort_unless($salon && $salon->status === 'approved', 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_promoted' => ['sometimes', 'boolean'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
        ]);

        $service = $salon->services()->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'price' => $data['price'],
            'is_active' => $data['is_active'] ?? true,
            'is_promoted' => $data['is_promoted'] ?? false,
            'discount_price' => $data['discount_price'] ?? null,
        ]);

        return redirect()->route('owner.services.index')->with('status', 'Usluga je dodata.');
    }

    public function edit(Request $request, Service $service): View
    {
        $salon = $request->user()->salon;
        abort_unless($salon && $service->salon_id === $salon->id, 404);

        return view('owner.services.edit', [
            'salon' => $salon,
            'service' => $service,
        ]);
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $salon = $request->user()->salon;
        abort_unless($salon && $service->salon_id === $salon->id, 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_promoted' => ['sometimes', 'boolean'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
        ]);

        $service->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'duration_minutes' => $data['duration_minutes'],
            'price' => $data['price'],
            'is_active' => $data['is_active'] ?? true,
            'is_promoted' => $data['is_promoted'] ?? false,
            'discount_price' => $data['discount_price'] ?? null,
        ]);

        return redirect()->route('owner.services.index')->with('status', 'Usluga je ažurirana.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        $salon = $request->user()->salon;
        abort_unless($salon && $service->salon_id === $salon->id, 404);

        $service->delete();

        return redirect()->route('owner.services.index')->with('status', 'Usluga je obrisana.');
    }
}
