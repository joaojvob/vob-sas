<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    tenants: Array,
});
</script>

<template>
    <Head title="Gerenciar Lojistas" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Lojistas (Tenants)
                </h2>
                <Link
                    :href="route('admin.tenants.create')"
                    class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700"
                >
                    Novo Lojista
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="p-3">ID</th>
                                    <th class="p-3">Nome da Loja</th>
                                    <th class="p-3">Slug</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Data de Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tenant in tenants" :key="tenant.id" class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ tenant.id }}</td>
                                    <td class="p-3 font-semibold">{{ tenant.name }}</td>
                                    <td class="p-3 text-gray-500">{{ tenant.slug }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-1 rounded text-xs text-white" :class="tenant.status === 'active' ? 'bg-green-500' : 'bg-red-500'">
                                            {{ tenant.status }}
                                        </span>
                                    </td>
                                    <td class="p-3">{{ new Date(tenant.created_at).toLocaleDateString('pt-BR') }}</td>
                                </tr>
                                <tr v-if="tenants.length === 0">
                                    <td colspan="5" class="p-6 text-center text-gray-500">
                                        Nenhum lojista cadastrado ainda.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
