<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Campi d'inserimento 
const form = useForm({
    name: '',
    surname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

// Messaggio di errore per l'email
const emailError = ref('');

// Funzione di validazione email
const validateEmail = () => {
    const email = form.email;
    if (email && !email.endsWith('.it') && !email.endsWith('.com')) {
        emailError.value = 'L\'email deve terminare con .it o .com';
        return false;
    }
    emailError.value = '';
    return true;
};

// Funzione di invio dei dati
const submit = () => {
    if (!validateEmail()) {
        return;
    }

    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <!-- Layout per utenti non registrati -->
    <GuestLayout>

        <Head title="Registrati" />

        <!-- Preveniene aggiornameto pagina -->
        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nome" />

                <TextInput id="name" type="text"
                    class="mt-1 block w-full rounded-lg bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition border-2 border-black"
                    v-model="form.name" required autofocus autocomplete="name" />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="surname" value="Cognome" />

                <TextInput id="surname" type="text"
                    class="mt-1 block w-full rounded-lg bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition border-2 border-black"
                    v-model="form.surname" required autocomplete="family-name" />

                <InputError class="mt-2" :message="form.errors.surname" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email"
                    class="mt-1 block w-full rounded-lg bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition border-2 border-black"
                    v-model="form.email" required autocomplete="username" @blur="validateEmail" />

                <InputError class="mt-2" :message="form.errors.email" />
                <InputError class="mt-2" :message="emailError" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password"
                    class="mt-1 block w-full rounded-lg bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition border-2 border-black"
                    v-model="form.password" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Conferma Password" />

                <TextInput id="password_confirmation" type="password"
                    class="mt-1 block w-full rounded-lg bg-white px-4 py-2 text-gray-900 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition border-2 border-black"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>


            <div class="mt-4 flex items-center justify-end">
                <!-- Link per tornare al login -->
                <Link :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sei già registrato?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrati
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
