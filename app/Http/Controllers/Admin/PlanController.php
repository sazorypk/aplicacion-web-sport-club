<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('memberships')->orderBy('name')->paginate(10);

        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['is_active'] = $request->boolean('is_active');
        Plan::create($data);

        return redirect()->route('admin.planes.index')->with('success', 'Plan creado correctamente.');
    }

    public function edit(Plan $plane)
    {
        return view('admin.plans.edit', ['plan' => $plane]);
    }

    public function update(Request $request, Plan $plane)
    {
        $data = $this->validatedData($request, $plane);
        $data['is_active'] = $request->boolean('is_active');
        $plane->update($data);

        return redirect()->route('admin.planes.index')->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plane)
    {
        if ($plane->memberships()->exists()) {
            return back()->with('error', 'No se puede eliminar: el plan ya tiene inscripciones. Puedes desactivarlo.');
        }
        $plane->delete();

        return redirect()->route('admin.planes.index')->with('success', 'Plan eliminado.');
    }

    private function validatedData(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('plans', 'name')->ignore($plan?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ]);
    }
}
