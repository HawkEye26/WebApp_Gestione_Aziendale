<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import StatCard from '@/components/StatCard.vue';
import SimpleChart from '@/components/SimpleChart.vue';
import { Building2, Plus, Upload, TrendingUp, Users, MapPin, Calendar, Clock } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        required: true
    }
});

// Formattazione data ultimo accesso
const lastUpdate = new Date().toLocaleString('it-IT', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
});

// Prepara i dati per il grafico delle regioni
const chartData = computed(() => {
    return props.stats.byRegion.map(region => ({
        label: region.region || 'Non specificata',
        value: region.total
    }));
});
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Dashboard
                </h2>
                <div class="flex items-center text-sm text-gray-500">
                    <Clock class="w-4 h-4 mr-1" />
                    Ultimo aggiornamento: {{ lastUpdate }}
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Banner di benvenuto -->
                <div class="mb-8 overflow-hidden bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg sm:rounded-lg">
                    <div class="p-8 text-white">
                        <h1 class="text-3xl font-bold mb-2">Benvenuto nel Portale Aziendale! 🏢</h1>
                        <p class="text-blue-100 text-lg">
                            Gestisci facilmente tutte le informazioni delle tue aziende in un unico posto.
                        </p>
                    </div>
                </div>

                <!-- Banner statistiche -->
                <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">

                    <StatCard title="Totale Aziende" :value="stats.total" description="Nel database" :icon="Building2"
                        variant="blue" />

                    <StatCard title="Questo Mese" :value="stats.thisMonth" description="Nuove aggiunte"
                        :icon="TrendingUp" variant="green" />

                    <StatCard title="Regioni Coperte" :value="stats.byRegion.length"
                        description="Distribuzione geografica" :icon="MapPin" variant="purple" />

                    <!-- Azioni rapide cards-->
                    <Card
                        class="hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium text-orange-800">
                                Azioni Rapide
                            </CardTitle>
                            <Users class="h-6 w-6 text-orange-600" />
                        </CardHeader>
                        <CardContent>
                            <div class="flex flex-col space-y-2">
                                <Link :href="route('companies.create')"
                                    class="inline-flex items-center px-3 py-1 bg-orange-600 text-white text-xs rounded-md hover:bg-orange-700 transition">
                                <!-- Icona '+' -->
                                <Plus class="w-3 h-3 mr-1" />
                                Aggiungi
                                </Link>
                                <!-- Mostra solo se l'utente è admin -->
                                <Link v-if="$page.props.isAdmin" :href="route('companies.importPreview')"
                                    class="inline-flex items-center px-3 py-1 bg-orange-500 text-white text-xs rounded-md hover:bg-orange-600 transition">
                                <Upload class="w-3 h-3 mr-1" />
                                Importa
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Banner tabella percentuali -->
                <div class="grid gap-6 mb-8 md:grid-cols-2">
                    <!-- Top regioni -->
                    <Card class="hover:shadow-lg transition-shadow duration-300">
                        <CardHeader>
                            <CardTitle class="flex items-center">
                                <!-- Icona -->
                                <MapPin class="w-5 h-5 mr-2 text-indigo-600" />
                                Top 10 Regioni per Aziende
                            </CardTitle>
                            <CardDescription>
                                Distribuzione geografica delle aziende registrate
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <SimpleChart :data="chartData" />
                            <div v-if="stats.byRegion.length === 0" class="text-center py-8 text-gray-500">
                                <MapPin class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                                <p>Nessuna azienda trovata</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Aziende Recenti -->
                    <Card class="hover:shadow-lg transition-shadow duration-300">
                        <CardHeader>
                            <CardTitle class="flex items-center">
                                <Calendar class="w-5 h-5 mr-2 text-green-600" />
                                Aziende Aggiunte di Recente
                            </CardTitle>
                            <CardDescription>
                                Le ultime 7 aziende registrate nel sistema
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div v-for="company in stats.recent" :key="company.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center">
                                        <!-- Stampa dell'iniziale del nome in grande dentro un pallino verde -->
                                        <div
                                            class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">
                                            {{ company.company_name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ company.company_name }}</p>
                                            <p class="text-sm text-gray-500">{{ company.city }}, {{ company.province }}
                                            </p>
                                        </div>
                                    </div>
                                    <!-- Data creazione azienda formattata -->
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500">
                                            {{ new Date(company.created_at).toLocaleDateString('it-IT') }}
                                        </span>
                                    </div>
                                </div>
                                <!-- Messaggio nel caso non ci siano nuove aziende -->
                                <div v-if="stats.recent.length === 0" class="text-center py-8 text-gray-500">
                                    <Building2 class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                                    <p>Nessuna azienda recente</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Banner inferiore -->
                <Card class="bg-gradient-to-r from-slate-50 to-gray-100 border-gray-200 mb-8">
                    <CardContent class="p-8">
                        <div class="text-center">
                            <Building2 class="w-16 h-16 mx-auto mb-4 text-gray-400" />
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Inizia a Gestire le Tue Aziende</h3>
                            <p class="text-gray-600 mb-6">
                                Aggiungi, modifica e organizza tutte le informazioni aziendali in modo semplice ed
                                efficace.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link :href="route('companies.index')"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg hover:shadow-xl">
                                <Building2 class="w-5 h-5 mr-2" />
                                Visualizza Tutte le Aziende
                                </Link>
                                <Link :href="route('companies.create')"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl">
                                <Plus class="w-5 h-5 mr-2" />
                                Aggiungi Nuova Azienda
                                </Link>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Footer con informazioni -->
                <div class="mt-8 text-center text-sm text-gray-500">
                    <div
                        class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-6">
                        <div class="flex items-center">
                            <Building2 class="w-4 h-4 mr-1" />
                            <span>Sistema di Gestione Aziendale</span>
                        </div>
                        <div class="flex items-center">
                            <Clock class="w-4 h-4 mr-1" />
                            <span>Aggiornato in tempo reale</span>
                        </div>
                        <div class="flex items-center">
                            <Users class="w-4 h-4 mr-1" />
                            <span>{{ $page.props.auth.user.name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
