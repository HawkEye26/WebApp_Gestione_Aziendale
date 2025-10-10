<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { useForm } from '@inertiajs/vue3';
import { toast } from '@/utils/toast';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Building2, MapPin, Mail, Save, X, Edit } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    company: {
        type: Object,
        required: true,
    }
})

const form = useForm({
    company_name: props.company.company_name,
    address: props.company.address,
    zip_code: props.company.zip_code,
    city: props.company.city,
    province: props.company.province,
    region: props.company.region,
    email: props.company.email,
})

function submit() {
    form.put(route('companies.update', props.company.id), {
        onSuccess: () => {
            toast.success('Azienda aggiornata! ✏️', 'Le modifiche sono state salvate con successo');
        },
        onError: () => {
            toast.error('Errore!', 'Si è verificato un errore durante l\'aggiornamento');
        }
    });
}

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="space-y-6">
                <!-- Header Card -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Edit class="h-6 w-6 text-blue-600" />
                            Modifica Azienda
                        </CardTitle>
                    </CardHeader>
                </Card>

                <!-- Form Card -->
                <Card>
                    <CardContent class="p-8">
                        <form @submit.prevent="submit" class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                            <!-- Nome Azienda -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <Building2 class="h-4 w-4 text-gray-500" />
                                    Nome Azienda
                                </label>
                                <input v-model="form.company_name" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="Inserisci il nome dell'azienda" />
                                <div class="text-sm text-red-600" v-if="form.errors.company_name">{{
                                    form.errors.company_name }}</div>
                            </div>

                            <!-- Indirizzo -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <MapPin class="h-4 w-4 text-gray-500" />
                                    Indirizzo
                                </label>
                                <input v-model="form.address" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="Via, Piazza, etc." />
                                <div class="text-sm text-red-600" v-if="form.errors.address">{{ form.errors.address }}
                                </div>
                            </div>

                            <!-- CAP -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700">CAP</label>
                                <input v-model="form.zip_code" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="12345" />
                                <div class="text-sm text-red-600" v-if="form.errors.zip_code">{{ form.errors.zip_code }}
                                </div>
                            </div>

                            <!-- Città -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700">Città</label>
                                <input v-model="form.city" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="Nome della città" />
                                <div class="text-sm text-red-600" v-if="form.errors.city">{{ form.errors.city }}
                                </div>
                            </div>

                            <!-- Provincia -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700">Provincia</label>
                                <input v-model="form.province" type="text" maxlength="2"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="RM" />
                                <div class="text-sm text-red-600" v-if="form.errors.province">{{
                                    form.errors.province }}
                                </div>
                            </div>

                            <!-- Regione -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700">Regione</label>
                                <input v-model="form.region" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="Nome della regione" />
                                <div class="text-sm text-red-600" v-if="form.errors.region">{{ form.errors.region }}
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex flex-col space-y-2 sm:col-span-2 lg:col-span-3">
                                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                                    <Mail class="h-4 w-4 text-gray-500" />
                                    Email
                                </label>
                                <input v-model="form.email" type="email"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="esempio@azienda.it" />
                                <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}</div>
                            </div>

                            <!-- Bottoni -->
                            <div
                                class="sm:col-span-2 lg:col-span-3 flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                                <Link :href="route('companies.index')"
                                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <X class="h-4 w-4" />
                                Annulla
                                </Link>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                    :disabled="form.processing">
                                    <Save class="h-4 w-4" />
                                    {{ form.processing ? 'Salvataggio...' : 'Salva Modifiche' }}
                                </button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </template>
    </AuthenticatedLayout>
</template>