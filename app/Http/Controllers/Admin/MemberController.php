<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

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

    public function destroyMembership(Membership $membership): RedirectResponse
    {
        $deleted = DB::transaction(function () use ($membership): bool {
            $membership = Membership::whereKey($membership->getKey())->lockForUpdate()->firstOrFail();

            if ($membership->status !== 'cancelled') {
                return false;
            }

            $membership->payments()->delete();
            $membership->delete();

            return true;
        });

        if (! $deleted) {
            return back()->with('error', 'Cancela la membresía antes de eliminarla.');
        }

        return back()->with('success', 'La membresía y sus pagos asociados se eliminaron.');
    }

    public function destroy(User $member): RedirectResponse
    {
        if ($member->role === 'master') {
            return back()->with('error', 'No se pueden eliminar cuentas maestras desde esta sección.');
        }

        $deleted = DB::transaction(function () use ($member): bool {
            $member = User::whereKey($member->getKey())->lockForUpdate()->firstOrFail();

            if ($member->memberships()->exists()) {
                return false;
            }

            $member->delete();

            return true;
        });

        if (! $deleted) {
            return back()->with('error', 'Elimina todas las membresías del usuario antes de eliminar su cuenta.');
        }

        return back()->with('success', 'El usuario se eliminó correctamente.');
    }
}
