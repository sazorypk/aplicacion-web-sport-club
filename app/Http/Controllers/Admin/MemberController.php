<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::with(['memberships' => fn ($query) => $query->latest(), 'memberships.plan', 'memberships.sede'])
            ->orderBy('name')->paginate(15);

        return view('admin.members.index', compact('members'));
    }

    public function cancelMembership(Membership $membership): RedirectResponse
    {
        $cancelled = Membership::query()
            ->whereKey($membership->getKey())
            ->whereIn('status', ['pending', 'active'])
            ->update(['status' => 'cancelled']);

        if (! $cancelled) {
            return back()->with('error', 'Esta membresía ya no se puede cancelar.');
        }

        return back()->with('success', 'La membresía se canceló correctamente.');
    }
}
