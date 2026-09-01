<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    tenant: Object,
    owner: Object,
});

const activeTab = ref('empresa');

const formTenant = useForm({
    name: props.tenant.name,
    document: props.tenant.document || "",
    plan: props.tenant.plan || "trial",
    status: props.tenant.status,
});

const formOwner = useForm({
    user_id: props.owner?.id || '',
    name: props.owner?.name || '',
    username: props.owner?.username || '',
    cpf: props.owner?.cpf || '',
    email: props.owner?.email || '',
    password: '',
});

const submitTenant = () => {
    formTenant.put(route("admin.tenants.update", props.tenant.id));
};

const submitOwner = () => {
    formOwner.put(route("admin.tenants.updateOwner", props.tenant.id), {
        preserveScroll: true,
        onSuccess: () => formOwner.reset('password'),
    });
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
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                
                <!-- Abas -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'empresa'"
                            :class="[activeTab === 'empresa' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Dados da Empresa / Conta
                        </button>
                        <button 
                            @click="activeTab = 'acesso'"
                            :class="[activeTab === 'acesso' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300', 'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm']">
                            Dados de Acesso (Dono)
                        </button>
                    </nav>
                </div>

                <div class="bg-white p-8 shadow-sm sm:rounded-lg">
                    <!-- Form Tenant -->
                    <form v-if="activeTab === 'empresa'" @submit.prevent="submitTenant" class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                                Configurações da Empresa
                            </h3>

                            <div>
                                <InputLabel for="name" value="Nome da Conta (Ex: Grupo João)" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="formTenant.name" :error="formTenant.errors.name" autofocus />
                                <InputError class="mt-2" :message="formTenant.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="document" value="CNPJ da Empresa Mestre (Opcional)" />
                                <TextInput id="document" type="text" class="mt-1 block w-full" v-model="formTenant.document" v-maska="'##.###.###/####-##'" placeholder="00.000.000/0000-00" :error="formTenant.errors.document" />
                                <InputError class="mt-2" :message="formTenant.errors.document" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="plan" value="Plano" />
                                <select id="plan" v-model="formTenant.plan" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" :class="{'border-red-500': formTenant.errors.plan}">
                                    <option value="trial">Teste (7 dias gratuitos)</option>
                                    <option value="monthly">Mensal Premium</option>
                                </select>
                                <InputError class="mt-2" :message="formTenant.errors.plan" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="status" value="Status" />
                                <select id="status" v-model="formTenant.status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <InputError class="mt-2" :message="formTenant.errors.status" />
                            </div>
                        </div>

                        <div class="flex items-center justify-center space-x-4 mt-6 pt-6 border-t border-gray-100">
                            <PrimaryButton :loading="formTenant.processing">
                                Salvar Empresa
                            </PrimaryButton>
                        </div>
                    </form>

                    <!-- Form Owner -->
                    <form v-if="activeTab === 'acesso'" @submit.prevent="submitOwner" class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                                Credenciais do Administrador da Loja
                            </h3>
                            
                            <div v-if="!owner" class="text-red-500 mb-4 text-sm">
                                Aviso: Nenhum usuário dono foi encontrado para esta conta.
                            </div>

                            <template v-else>
                                <div>
                                    <InputLabel for="owner_name" value="Nome Completo" />
                                    <TextInput id="owner_name" type="text" class="mt-1 block w-full" v-model="formOwner.name" :error="formOwner.errors.name" />
                                    <InputError class="mt-2" :message="formOwner.errors.name" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="owner_username" value="Usuário de Acesso (Login)" />
                                    <TextInput id="owner_username" type="text" class="mt-1 block w-full" v-model="formOwner.username" :error="formOwner.errors.username" />
                                    <InputError class="mt-2" :message="formOwner.errors.username" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="owner_cpf" value="CPF" />
                                    <TextInput id="owner_cpf" type="text" class="mt-1 block w-full" v-model="formOwner.cpf" v-maska="'###.###.###-##'" :error="formOwner.errors.cpf" />
                                    <InputError class="mt-2" :message="formOwner.errors.cpf" />
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="owner_email" value="E-mail" />
                                    <TextInput id="owner_email" type="email" class="mt-1 block w-full" v-model="formOwner.email" :error="formOwner.errors.email" />
                                    <InputError class="mt-2" :message="formOwner.errors.email" />
                                </div>

                                <div class="mt-6 border-t border-gray-100 pt-6">
                                    <h4 class="font-medium text-gray-900 mb-4">Redefinir Senha</h4>
                                    <p class="text-sm text-gray-500 mb-4">Deixe em branco caso não queira alterar a senha atual do usuário. Ao alterar, ele será forçado a definir uma nova no próximo acesso.</p>
                                    
                                    <div>
                                        <InputLabel for="owner_password" value="Nova Senha Provisória" />
                                        <TextInput id="owner_password" type="password" class="mt-1 block w-full" v-model="formOwner.password" :error="formOwner.errors.password" />
                                        <InputError class="mt-2" :message="formOwner.errors.password" />
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex items-center justify-center space-x-4 mt-6 pt-6 border-t border-gray-100" v-if="owner">
                            <PrimaryButton :loading="formOwner.processing">
                                Atualizar Acesso
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
