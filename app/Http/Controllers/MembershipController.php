<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sede;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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

        return view('memberships.create', ['plans' => $plans, 'sedes' => $sedes, 'disciplines' => Membership::DISCIPLINES]);
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
        $disciplineData = $request->validate([
            'disciplines' => [Rule::excludeIf($plan->includes_all_disciplines), 'required', 'array', 'size:'.$plan->discipline_limit],
            'disciplines.*' => [Rule::excludeIf($plan->includes_all_disciplines), 'distinct', Rule::in(Membership::DISCIPLINES)],
        ], [
            'disciplines.required' => 'Selecciona las disciplinas que deseas practicar.',
            'disciplines.array' => 'Selecciona disciplinas válidas.',
            'disciplines.size' => 'Selecciona exactamente :size disciplina(s).',
            'disciplines.*.in' => 'Selecciona una disciplina válida.',
            'disciplines.*.distinct' => 'No puedes repetir una disciplina.',
        ]);
        $membership = Membership::create([
            'user_id' => $request->user()->id,
            'plan_id' => $plan->id,
            'sede_id' => $sede->id,
            'disciplines' => $disciplineData['disciplines'] ?? null,
            'price_at_signup' => $plan->price,
            'status' => 'pending',
        ]);

        return redirect()->route('mi-membresia.index')->with('success', 'Inscripcion creada.');
    }

    public function updateDisciplines(Request $request, Membership $membership): RedirectResponse
    {
        abort_unless($membership->user_id === $request->user()->id, 403);
        $membership->load('plan');
        $selectedDisciplines = $membership->disciplines ?? ($membership->discipline ? [$membership->discipline] : []);

        if ($membership->plan->includes_all_disciplines || ! in_array($membership->status, ['pending', 'active'], true) || count($selectedDisciplines) >= $membership->plan->discipline_limit) {
            return back()->with('error', 'Esta membresía no tiene disciplinas pendientes de selección.');
        }

        $data = $request->validate([
            'disciplines' => ['required', 'array', 'size:'.$membership->plan->discipline_limit],
            'disciplines.*' => ['distinct', Rule::in(Membership::DISCIPLINES)],
        ], [
            'disciplines.required' => 'Selecciona las disciplinas que deseas practicar.',
            'disciplines.size' => 'Selecciona exactamente :size disciplina(s).',
            'disciplines.*.distinct' => 'No puedes repetir una disciplina.',
            'disciplines.*.in' => 'Selecciona una disciplina válida.',
        ]);

        $membership->update(['disciplines' => $data['disciplines']]);

        return back()->with('success', 'Tus disciplinas se guardaron correctamente.');
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
