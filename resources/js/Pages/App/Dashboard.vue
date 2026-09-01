<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div
                        class="p-6 text-gray-900 flex flex-col items-center justify-center text-center"
                    >
                        <div
                            class="bg-indigo-100 text-indigo-600 rounded-full p-4 mb-4"
                        >
                            <svg
                                class="h-12 w-12"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                            Bem-vindo(a) ao seu Painel de Controle!
                        </h3>
                        <p class="text-gray-500 max-w-lg">
                            Sua conta foi ativada com sucesso. Em breve, este
                            espaço exibirá todas as métricas e resumos de vendas
                            das suas lojas. Estamos preparando o terreno para
                            você.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
