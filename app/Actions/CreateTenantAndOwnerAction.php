<?php

namespace App\Actions;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateTenantAndOwnerAction
{
    /**
     * Cria um Tenant e seu Usuário dono, com senha inicial.
     * O usuário será forçado a alterar a senha no primeiro login.
     *
     * @param array $tenantData Dados da loja (name, document, slug)
     * @param array $ownerData Dados do dono (name, email, cpf)
     * @return Tenant
     * @throws \Exception
     */
    public function execute(array $tenantData, array $ownerData): Tenant
    {
        return DB::transaction(function () use ($tenantData, $ownerData) {
            // 1. Criar o Tenant
            $tenant = Tenant::create([
                'name' => $tenantData['name'],
                'document' => $tenantData['document'] ?? null,
                'slug' => $tenantData['slug'] ?? Str::slug($tenantData['name']),
                'status' => 'active',
                'plan' => $tenantData['plan'] ?? 'trial',
                'expires_at' => $tenantData['expires_at'] ?? now()->addDays(7),
            ]);

            // 2. Criar o Usuário Dono (Owner)
            // A senha inicial será a fornecida, ou o CPF sem formatação, ou uma padrão.
            $initialPassword = $ownerData['password'] ?? ($ownerData['cpf'] ? preg_replace('/\D/', '', $ownerData['cpf']) : 'mudar123');

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $ownerData['name'],
                'username' => $ownerData['username'],
                'email' => $ownerData['email'],
                'cpf' => $ownerData['cpf'],
                'password' => Hash::make($initialPassword),
                'must_change_password' => true,
            ]);

            // 3. Atribuir a Role de Dono da Loja
            // O Spatie Permission usa a tabela roles. Precisamos garantir que a role existe
            // para o tenant_id correto (pois a feature teams está ativada).
            // Definimos o team_id no escopo atual.
            setPermissionsTeamId($tenant->id);

            $role = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'store_owner',
                'guard_name' => 'web',
                'team_id' => $tenant->id,
            ]);

            $user->assignRole($role);

            return $tenant;
        });
    }
}
