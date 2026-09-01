<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    tenant_name: "",
    tenant_document: "",
    owner_name: "",
    owner_email: "",
    owner_cpf: "",
});

const submit = () => {
    form.post(route("admin.tenants.store"));
};
</script>

<template>
    <Head title="Novo Lojista" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Cadastrar Novo Lojista (Tenant)
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white p-8 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Dados da Loja -->
                        <div>
                            <h3
                                class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                            >
                                Dados do Estabelecimento
                            </h3>

                            <div>
                                <InputLabel
                                    for="tenant_name"
                                    value="Nome do Estabelecimento"
                                />
                                <TextInput
                                    id="tenant_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.tenant_name"
                                    required
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
                                    value="CNPJ (Opcional)"
                                />
                                <TextInput
                                    id="tenant_document"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.tenant_document"
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.tenant_document"
                                />
                            </div>
                        </div>

                        <!-- Dados do Dono -->
                        <div class="pt-4">
                            <h3
                                class="text-lg font-medium text-gray-900 border-b pb-2 mb-4"
                            >
                                Dados do Responsável (Login)
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
                                    required
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
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.owner_cpf"
                                />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="owner_email" value="E-mail" />
                                <TextInput
                                    id="owner_email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.owner_email"
                                    required
                                />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.owner_email"
                                />
                            </div>

                            <p class="text-sm text-gray-500 mt-2">
                                A senha inicial será o CPF (somente números). No
                                primeiro login, o sistema exigirá a troca.
                            </p>
                        </div>

                        <div
                            class="flex items-center justify-end mt-4 pt-4 border-t"
                        >
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Cadastrar Lojista
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
