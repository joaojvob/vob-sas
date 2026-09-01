<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Actions\CreateTenantAndOwnerAction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    public function index(): Response
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/Tenants/Index', ['tenants' => $tenants]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Tenants/Create');
    }

    public function store(Request $request, CreateTenantAndOwnerAction $action): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_document' => ['nullable', 'string', 'max:50'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255'],
            'owner_cpf' => ['required', 'string', 'max:20'],
        ]);

        $action->execute(
            tenantData: [
                'name' => $validated['tenant_name'],
                'document' => $validated['tenant_document'],
            ],
            ownerData: [
                'name' => $validated['owner_name'],
                'email' => $validated['owner_email'],
                'cpf' => $validated['owner_cpf'],
            ]
        );

        return redirect()->route('admin.tenants.index')->with('success', 'Lojista cadastrado com sucesso!');
    }
}
