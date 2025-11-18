<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

// Props Email inviata
defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

// Invio del dato
const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <!-- Layout per utenti non autenticati -->
    <GuestLayout>

        <Head title="Password Dimenticata" />

        <div class="mb-4 text-sm text-gray-900">
            Hai dimenticato la password? Nessun problema.
            Facci sapere il tuo indirizzo email e ti invieremo un link per reimpostare la password,
            che ti permetterà di sceglierne una nuova.
        </div>

        <!-- Mostra il messaggio solo se esiste -->
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <!-- Preveniene aggiornameto pagina -->
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput 
                    id="email" 
                    type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition"
                    v-model="form.email" 
                    required 
                    autofocus 
                    autocomplete="username" 
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
