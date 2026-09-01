<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";

defineProps({
    tenants: Array,
});

const toggleStatus = (tenant) => {
    const action = tenant.status === 'active' ? 'desativar' : 'ativar';
    
    window.Swal.fire({
        title: `Confirmar Ação`,
        text: `Tem certeza que deseja ${action} a conta ${tenant.name}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sim, ${action}!`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('admin.tenants.toggle-status', tenant.id));
        }
    });
};
</script>

<template>
    <Head title="Gerenciar Clientes" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Clientes SaaS (Contas/Redes)
                </h2>
                <Link
                    :href="route('admin.tenants.create')"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
                >
                    Novo Cliente
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Conta / Empresa</th>
                                    <th class="p-3">Slug</th>
                                    <th class="p-3 text-center">Status</th>
                                    <th class="p-3">Cadastro</th>
                                    <th class="p-3 text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="tenant in tenants"
                                    :key="tenant.id"
                                    class="border-b hover:bg-gray-50"
                                >
                                    <td class="p-3">{{ tenant.id }}</td>
                                    <td class="p-3 font-semibold">
                                        {{ tenant.name }}
                                    </td>
                                    <td class="p-3 text-gray-500">
                                        {{ tenant.slug }}
                                    </td>
                                    <td class="p-3 text-center">
                                        <span
                                            class="px-2 py-1 rounded text-xs text-white"
                                            :class="
                                                tenant.status === 'active'
                                                    ? 'bg-green-500'
                                                    : 'bg-red-500'
                                            "
                                        >
                                            {{ tenant.status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        {{
                                            new Date(
                                                tenant.created_at,
                                            ).toLocaleDateString("pt-BR")
                                        }}
                                    </td>
                                    <td class="p-3 text-center flex justify-center space-x-3">
                                        <Link :href="route('admin.tenants.edit', tenant.id)" class="text-blue-600 hover:text-blue-800 font-medium">
                                            Editar
                                        </Link>
                                        <button @click="toggleStatus(tenant)" class="text-red-600 hover:text-red-800 font-medium" v-if="tenant.status === 'active'">
                                            Desativar
                                        </button>
                                        <button @click="toggleStatus(tenant)" class="text-green-600 hover:text-green-800 font-medium" v-else>
                                            Ativar
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="tenants.length === 0">
                                    <td
                                        colspan="6"
                                        class="p-6 text-center text-gray-500"
                                    >
                                        Nenhum cliente cadastrado ainda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
