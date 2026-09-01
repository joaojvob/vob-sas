<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

defineProps({
    metrics: Object,
    recent_tenants: Array,
});
</script>

<template>
    <Head title="Painel Gerencial" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Visão Geral do Sistema (Super Admin)
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Cards de Indicadores -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Contas (Lojistas)</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ metrics.total_tenants }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Contas Ativas</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ metrics.active_tenants }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Lojas Físicas Cadastradas</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-2xl font-semibold text-gray-900">{{ metrics.total_stores }}</div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de Últimos Clientes -->
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Últimos Clientes Cadastrados</h3>
                        <Link :href="route('admin.tenants.index')" class="text-sm text-blue-600 hover:text-blue-900 font-medium">Ver todos &rarr;</Link>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Conta</th>
                                    <th class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                                    <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="p-4 text-xs font-medium text-gray-500 uppercase tracking-wider">Cadastrado em</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="tenant in recent_tenants" :key="tenant.id" class="hover:bg-gray-50">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="font-medium text-gray-900">{{ tenant.name }}</div>
                                        <div class="text-sm text-gray-500">{{ tenant.slug }}</div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              :class="tenant.plan === 'monthly' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800'">
                                            {{ tenant.plan === 'monthly' ? 'Mensal' : 'Teste' }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                              :class="tenant.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                            {{ tenant.status === 'active' ? 'Ativo' : 'Inativo' }}
                                        </span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(tenant.created_at).toLocaleDateString('pt-BR') }}
                                    </td>
                                </tr>
                                <tr v-if="recent_tenants.length === 0">
                                    <td colspan="4" class="p-6 text-center text-gray-500 text-sm">
                                        Nenhum cliente recente encontrado.
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
