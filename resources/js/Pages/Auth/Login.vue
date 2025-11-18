<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { toast } from '@/utils/toast';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Building2, Mail, Lock, LogIn, Shield, Users, CheckCircle } from 'lucide-vue-next';

// Props password dimenticata
defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// Campi d'inserimento con casella remember
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

// Convalida utente
const submit = () => {
    form.post(route('login'), {
        onSuccess: () => {
            toast.success('Login effettuato! 🔑', 'Benvenuto nel sistema');
        },
        onError: () => {
            toast.error('Login fallito! ❌', 'Controlla le tue credenziali');
        },
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">

        <Head title="Accedi - WebAppGAD" />

        <!-- Titolo -->
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 h-12 w-12 bg-blue-600 rounded-xl flex items-center justify-center">
                        <Building2 class="h-8 w-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">WebAppGAD</h1>
                        <p class="text-sm text-gray-600">Gestione Aziende e Dati</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Login -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-lg">
            <Card class="shadow-xl">
                <CardHeader class="text-center">
                    <CardTitle class="flex items-center justify-center gap-2 text-2xl">
                        <LogIn class="h-6 w-6 text-blue-600" />
                        Accedi al Sistema
                    </CardTitle>
                    <p class="text-sm text-gray-600 mt-2">Inserisci le tue credenziali per continuare</p>
                </CardHeader>

                <CardContent class="p-6">
                    <!-- Messaggi di stato -->
                    <div v-if="status" class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-2">
                            <CheckCircle class="h-5 w-5 text-green-600" />
                            <p class="text-sm font-medium text-green-800">{{ status }}</p>
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Campo Email -->
                        <div>
                            <label for="email" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <Mail class="h-4 w-4 text-gray-500" />
                                Indirizzo Email
                            </label>
                            <input id="email" type="email" v-model="form.email" required autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3 border border-gray-300 bg-white rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="esempio@azienda.it" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Campo Password -->
                        <div>
                            <label for="password"
                                class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <Lock class="h-4 w-4 text-gray-500" />
                                Password
                            </label>
                            <input id="password" type="password" v-model="form.password" required
                                autocomplete="current-password"
                                class="w-full px-4 py-3 border border-gray-300 bg-white rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Inserisci la tua password" />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <!-- Campo Ricordami -->
                        <div class="flex items-center">
                            <input id="remember" type="checkbox" v-model="form.remember"
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                            <label for="remember" class="ml-2 text-sm text-gray-700">
                                Ricordami
                            </label>
                        </div>

                        <!-- Tasto Accedi -->
                        <div class="flex flex-col space-y-4">
                            <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="form.processing">
                                <LogIn class="h-4 w-4" />
                                {{ form.processing ? 'Accesso in corso...' : 'Accedi' }}
                            </button>

                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="text-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                            Hai dimenticato la password?
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>

        <!-- Cards estetiche inferiori -->
        <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-4xl">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
                <Card>
                    <CardContent class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <Shield class="h-10 w-10 text-green-600" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Sicurezza</h3>
                        <p class="text-sm text-gray-600">Sistema protetto con crittografia avanzata e autenticazione
                            sicura</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <Building2 class="h-10 w-10 text-blue-600" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Gestione</h3>
                        <p class="text-sm text-gray-600">Amministra facilmente le aziende e i dati con strumenti
                            intuitivi</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardContent class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <Users class="h-10 w-10 text-purple-600" />
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Collaborazione</h3>
                        <p class="text-sm text-gray-600">Lavora in team con permessi personalizzati e controllo accessi
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500">
                © 2024 WebAppGAD. Sistema di gestione aziendale.
            </p>
        </div>
    </div>
</template>
