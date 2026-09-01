<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";

const props = defineProps({
    tenant: Object,
});

const form = useForm({
    name: props.tenant.name,
    document: props.tenant.document || '',
    status: props.tenant.status,
});

const submit = () => {
    form.put(route("admin.tenants.update", props.tenant.id));
};
</script>

<template>
    <Head title="Editar Cliente" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Editar Cliente: {{ tenant.name }}
                </h2>
                <Link
                    :href="route('admin.tenants.index')"
                    class="text-gray-500 hover:text-gray-700 underline text-sm"
                >
                    Voltar para lista
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="bg-white p-8 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                                Dados da Conta / Empresa
                            </h3>

                            <div>
                                <InputLabel for="name" value="Nome da Conta (Ex: Grupo João)" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    :error="form.errors.name"
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="document" value="CNPJ da Empresa Mestre (Opcional)" />
                                <TextInput
                                    id="document"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.document"
                                    v-maska="'##.###.###/####-##'"
                                    placeholder="00.000.000/0000-00"
                                    :error="form.errors.document"
                                />
                                <InputError class="mt-2" :message="form.errors.document" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>
                        </div>

                        <div class="flex items-center justify-center space-x-4 mt-6 pt-6 border-t border-gray-100">
                            <Link
                                :href="route('admin.tenants.index')"
                                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-6 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
                            >
                                Cancelar
                            </Link>
                            <PrimaryButton :loading="form.processing">
                                Salvar Alterações
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
