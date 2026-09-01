<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Actions\CreateTenantAndOwnerAction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
            'tenant_document' => ['nullable', 'string', 'cnpj'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'owner_cpf' => ['required', 'string', 'cpf'],
        ], [
            'owner_cpf.cpf' => 'O CPF informado é inválido.',
            'tenant_document.cnpj' => 'O CNPJ informado é inválido.',
            'owner_email.unique' => 'Este e-mail já está em uso.',
        ]);

        try {
            $action->execute(
                tenantData: [
                    'name' => $validated['tenant_name'],
                    'document' => preg_replace('/\D/', '', $validated['tenant_document'] ?? ''),
                ],
                ownerData: [
                    'name' => $validated['owner_name'],
                    'email' => $validated['owner_email'],
                    'cpf' => preg_replace('/\D/', '', $validated['owner_cpf']),
                ]
            );

            return redirect()->route('admin.tenants.index')->with('success', 'Cliente cadastrado com sucesso!');
        } catch (\Exception $e) {
            // Se houver erro de negócio
            return back()->with('error', 'Falha ao cadastrar cliente: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Tenant $tenant): Response
    {
        return Inertia::render('Admin/Tenants/Edit', [
            'tenant' => $tenant
        ]);
    }

    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'document' => ['nullable', 'string', 'cnpj'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ], [
            'document.cnpj' => 'O CNPJ informado é inválido.',
        ]);

        $tenant->update([
            'name' => $validated['name'],
            'document' => preg_replace('/\D/', '', $validated['document'] ?? ''),
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.tenants.index')->with('success', 'Dados do cliente atualizados com sucesso!');
    }
}
