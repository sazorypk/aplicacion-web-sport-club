<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function index(Request $request)
    {
        $memberships = Membership::with(['plan', 'sede', 'payments'])
            ->where('user_id', $request->user()->id)->latest()->get();

        return view('memberships.index', compact('memberships'));
    }

    public function create(Request $request)
    {
        if ($request->user()->memberships()->whereIn('status', ['pending', 'active'])->exists()) {
            return redirect()->route('mi-membresia.index')->with('error', 'Ya tienes una inscripción pendiente o activa.');
        }
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $sedes = Sede::where('is_active', true)->orderBy('name')->get();

        return view('memberships.create', compact('plans', 'sedes'));
    }

    public function store(Request $request)
    {
        if ($request->user()->memberships()->whereIn('status', ['pending', 'active'])->exists()) {
            return redirect()->route('mi-membresia.index')->with('error', 'Ya tienes una inscripción pendiente o activa.');
        }
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'sede_id' => ['required', 'integer', 'exists:sedes,id'],
        ]);
        $plan = Plan::whereKey($data['plan_id'])->where('is_active', true)->firstOrFail();
        $sede = Sede::whereKey($data['sede_id'])->where('is_active', true)->firstOrFail();
        $membership = Membership::create([
            'user_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'sede_id' => $sede->id,
            'price_at_signup' => $plan->price,
            'status' => 'pending',
        ]);

        return redirect()->route('mi-membresia.index')->with('success', 'Inscripcion creada.');
    }

    public function simulatePayment(Request $request, Membership $membership)
    {
        abort_unless($membership->user_id === $request->user()->id, 403);
        if ($membership->status !== 'pending') {
            return back()->with('error', 'Solo se puede simular el pago de una inscripción pendiente.');
        }
        DB::transaction(function () use ($membership) {
            $membership = Membership::whereKey($membership->id)->lockForUpdate()->firstOrFail();
            if ($membership->status !== 'pending') {
                return;
            }
            $paidAt = now();
            Payment::create([
                'membership_id' => $membership->id,
                'amount' => $membership->price_at_signup,
                'status' => 'paid',
                'method' => 'simulated',
                'reference' => 'DEMO-'.Str::upper(Str::random(12)),
                'paid_at' => $paidAt,
            ]);
            $membership->update(['status' => 'active', 'start_date' => $paidAt->toDateString(), 'end_date' => $paidAt->copy()->addMonth()->toDateString()]);
        });

        return redirect()->route('mi-membresia.index');
    }
}
