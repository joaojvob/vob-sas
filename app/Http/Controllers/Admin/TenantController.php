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
            'tenant_plan' => ['required', Rule::in(['trial', 'monthly'])],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'owner_cpf' => ['required', 'string', 'cpf'],
        ], [
            'owner_cpf.cpf' => 'O CPF informado é inválido.',
            'tenant_document.cnpj' => 'O CNPJ informado é inválido.',
            'owner_email.unique' => 'Este e-mail já está em uso.',
        ]);

        $expiresAt = $validated['tenant_plan'] === 'trial' ? now()->addDays(7) : now()->addDays(30);

        try {
            $action->execute(
                tenantData: [
                    'name' => $validated['tenant_name'],
                    'document' => preg_replace('/\D/', '', $validated['tenant_document'] ?? ''),
                    'plan' => $validated['tenant_plan'],
                    'expires_at' => $expiresAt,
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
            'plan' => ['required', Rule::in(['trial', 'monthly'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ], [
            'document.cnpj' => 'O CNPJ informado é inválido.',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'document' => preg_replace('/\D/', '', $validated['document'] ?? ''),
            'plan' => $validated['plan'],
            'status' => $validated['status'],
        ];

        // Se o plano mudou manualmente pela edição, ajustamos o expires_at (opcional).
        // Aqui não vou forçar mudança de data só porque trocou o select,
        // a menos que o status mude pra active de um expirado. 
        // Simplificando: vamos manter o expires_at como está, a não ser que o usuário mude.
        // Se precisar de renovação, faremos via RenewAction no futuro.

        $tenant->update($updateData);

        return redirect()->route('admin.tenants.index')->with('success', 'Dados do cliente atualizados com sucesso!');
    }

    public function toggleStatus(Tenant $tenant): RedirectResponse
    {
        $newStatus = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->update(['status' => $newStatus]);

        $action = $newStatus === 'active' ? 'ativada' : 'desativada';
        return redirect()->route('admin.tenants.index')->with('success', "A conta do cliente foi {$action} com sucesso.");
    }
}
