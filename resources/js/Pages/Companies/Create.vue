<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { toast } from '@/utils/toast';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Building2, MapPin, Mail, Save, X, Info, Shield, Users } from 'lucide-vue-next';

const form = useForm({
    company_name: '',
    address: '',
    zip_code: '',
    city: '',
    province: '',
    region: '',
    email: '',
})

const emailDomainError = ref('');

// Funzione per validare email
function validateEmailDomain() {
    const email = form.email.toLowerCase();

    if (email && !email.endsWith('.it') && !email.endsWith('.com')) {
        emailDomainError.value = 'L\'email deve terminare con .it o .com';
        toast.warning('Email non valida', 'L\'email deve terminare con .it o .com');
        return false;
    } else {
        emailDomainError.value = '';
        return true;
    }
}

function submit() {
    // Resetta l'errore se l'utente corregge
    emailDomainError.value = '';

    // Controlla la validazione altrimenti blocca
    if (!validateEmailDomain()) {
        return;
    }

    form.post(route('companies.store'), {
        onSuccess: () => {
            toast.success('Azienda creata! 🎉', 'L\'azienda è stata registrata con successo');
        },
        onError: () => {
            toast.error('Errore!', 'Si è verificato un errore durante la creazione');
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
                            <Building2 class="h-6 w-6 text-blue-600" />
                            Aggiungi Nuova Azienda
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
                                <div class="text-sm text-red-600" v-if="form.errors.address">{{ form.errors.address
                                }}</div>
                            </div>

                            <!-- CAP -->
                            <div class="flex flex-col space-y-2">
                                <label class="text-sm font-medium text-gray-700">CAP</label>
                                <input v-model="form.zip_code" type="text"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="12345" />
                                <div class="text-sm text-red-600" v-if="form.errors.zip_code">{{
                                    form.errors.zip_code }}
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
                                <input v-model="form.email" type="email" @input="emailDomainError = ''"
                                    class="rounded-md border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-colors bg-white"
                                    placeholder="esempio@azienda.it" />
                                <div class="text-sm text-red-600" v-if="form.errors.email">{{ form.errors.email }}
                                </div>
                                <div class="text-sm text-red-600" v-if="emailDomainError">{{ emailDomainError }}
                                </div>
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
                                    {{ form.processing ? 'Salvataggio...' : 'Salva Azienda' }}
                                </button>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <!-- Cards Informative -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Info Card -->
                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <Info class="h-8 w-8 text-blue-600" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Informazioni</h3>
                                    <p class="text-sm text-gray-500 mt-1">Tutti i campi sono obbligatori per completare
                                        la registrazione dell'azienda.</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Privacy Card -->
                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <Shield class="h-8 w-8 text-green-600" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Privacy</h3>
                                    <p class="text-sm text-gray-500 mt-1">I dati inseriti sono protetti e utilizzati
                                        solo per fini aziendali.</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Support Card -->
                    <Card>
                        <CardContent class="p-6">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <Users class="h-8 w-8 text-purple-600" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Supporto</h3>
                                    <p class="text-sm text-gray-500 mt-1">Per assistenza contatta il team di supporto
                                        tecnico.</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Suggerimenti Card -->
                <Card>
                    <CardHeader>
                        <CardTitle class="text-lg">💡 Suggerimenti per la compilazione</CardTitle>
                    </CardHeader>
                    <CardContent class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Dati Aziendali</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                                        Inserisci la ragione sociale completa
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                                        Verifica l'indirizzo della sede legale
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                                        Usa l'abbreviazione per la provincia (es. RM, MI)
                                    </li>
                                </ul>
                            </div>
                            <div class="space-y-3">
                                <h4 class="font-medium text-gray-900">Email</h4>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        Accettiamo solo domini .it e .com
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        Preferibili email aziendali ufficiali
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                                        Verrà usata per comunicazioni importanti
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </template>
    </AuthenticatedLayout>
</template>