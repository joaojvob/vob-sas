<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";

const form = useForm({
    tenant_name: "",
    tenant_document: "",
    tenant_plan: "trial",
    owner_name: "",
    owner_email: "",
    owner_cpf: "",
});

const submit = () => {
    form.post(route("admin.tenants.store"));
};
</script>

<template>
    <Head title="Novo Cliente" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Cadastrar Novo Cliente (Rede/Conta)
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white p-8 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Dados da Conta -->
                        <div>
                            <h3
                                class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                            >
                                Dados da Conta / Empresa
                            </h3>

                            <div>
                                <InputLabel
                                    for="tenant_name"
                                    value="Nome da Conta (Ex: Grupo João)"
                                />
                                <TextInput
                                    id="tenant_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.tenant_name"
                                    :error="form.errors.tenant_name"
                                    autofocus
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.tenant_name"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="tenant_document"
                                    value="CNPJ da Empresa Mestre (Opcional)"
                                />
                                <TextInput
                                    id="tenant_document"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.tenant_document"
                                    v-maska="'##.###.###/####-##'"
                                    placeholder="00.000.000/0000-00"
                                    :error="form.errors.tenant_document"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.tenant_document"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="tenant_plan" value="Plano de Assinatura" />
                                <select
                                    id="tenant_plan"
                                    v-model="form.tenant_plan"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    :class="{'border-red-500': form.errors.tenant_plan}"
                                >
                                    <option value="trial">Teste (7 dias gratuitos)</option>
                                    <option value="monthly">Mensal Premium</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.tenant_plan" />
                            </div>
                        </div>

                        <!-- Dados do Dono -->
                        <div class="pt-4">
                            <h3
                                class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                            >
                                Dados do Cliente Responsável (Login)
                            </h3>

                            <div>
                                <InputLabel
                                    for="owner_name"
                                    value="Nome Completo"
                                />
                                <TextInput
                                    id="owner_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.owner_name"
                                    :error="form.errors.owner_name"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.owner_name"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="owner_cpf" value="CPF" />
                                <TextInput
                                    id="owner_cpf"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.owner_cpf"
                                    v-maska="'###.###.###-##'"
                                    placeholder="000.000.000-00"
                                    :error="form.errors.owner_cpf"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.owner_cpf"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel
                                    for="owner_email"
                                    value="E-mail principal"
                                />
                                <TextInput
                                    id="owner_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.owner_email"
                                    :error="form.errors.owner_email"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.owner_email"
                                />
                            </div>

                            <p class="text-sm text-gray-500 mt-2">
                                A senha inicial será o CPF (somente números). No
                                primeiro login, o sistema exigirá a troca para
                                garantir a segurança.
                            </p>
                        </div>

                        <div
                            class="flex items-center justify-center space-x-4 mt-6 pt-6 border-t border-gray-100"
                        >
                            <Link
                                :href="route('admin.tenants.index')"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-6 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton :loading="form.processing">
                                Cadastrar Cliente
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
