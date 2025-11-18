<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "./Pagination.vue";
import { usePage, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Building2, Search, Plus, Edit, Trash2, Download, Upload, Filter, CheckSquare, Square } from 'lucide-vue-next';
import { filter } from "lodash";
import { toast } from '@/utils/toast';

const props = defineProps({
    companies: Object,
    filters: Object,
});

const page = usePage()

// variabile per la ricerca
const searchTerm = ref('');

// variabili per selezione multipla
const selectedCompanies = ref([]);
const selectAll = ref(false);

// se la variabile è vuota mostra tutto
const filteredCompanies = computed(() => {
    if (!searchTerm.value) {
        return props.companies.data;
    }

    return props.companies.data.filter(company => {
        // trasformiamo cosa inserito in minuscolo
        const search = searchTerm.value.toLowerCase();
        // includes serve per confrontare le parole
        return company.company_name.toLowerCase().includes(search) ||
            company.address.toLowerCase().includes(search) ||
            company.zip_code.toLowerCase().includes(search) ||
            company.city.toLowerCase().includes(search) ||
            company.province.toLowerCase().includes(search) ||
            company.region.toLowerCase().includes(search) ||
            company.email.toLowerCase().includes(search);
    });
});

// Gestione selezione multipla
function toggleSelectAll() {
    // Inverte il valore
    selectAll.value = !selectAll.value;
    if (selectAll.value) {
        // Seleziona tutte le aziende filtrate visibili
        selectedCompanies.value = filteredCompanies.value.map(company => company.id);
    } else {
        // Deseleziona tutto
        selectedCompanies.value = [];
    }
}

// Gestione selezione singola
function toggleCompanySelection(companyId) {
    // Cerca l'id se presente deseleziona altrimenti seleziona
    const index = selectedCompanies.value.indexOf(companyId);
    if (index > -1) {
        selectedCompanies.value.splice(index, 1);
    } else {
        selectedCompanies.value.push(companyId);
    }

    // Aggiorna selectAll basato sulla selezione corrente
    selectAll.value = selectedCompanies.value.length === filteredCompanies.value.length && filteredCompanies.value.length > 0;
}

// Popup di eliminazione tramite "Elimina selezionati"
function deleteSelectedCompanies() {

    // Controlla se ce almeno una selezione
    if (selectedCompanies.value.length === 0) {
        toast.warning('Nessuna selezione', 'Seleziona almeno un\'azienda da eliminare');
        return;
    }

    // Conta aziende e crea messaggio
    const count = selectedCompanies.value.length;
    const message = count === 1
        ? 'Vuoi davvero eliminare l\'azienda selezionata?'
        : `Vuoi davvero eliminare le ${count} aziende selezionate?`;

    // Popup di conferma eliminazione
    if (confirm(message)) {

        // Passa id da eliminare e gestisce i casi
        router.delete(route('companies.bulkDestroy'), {
            data: {
                ids: selectedCompanies.value
            },
            // Svuota array, deseleziona checkbox e mostra messaggio
            onSuccess: () => {
                selectedCompanies.value = [];
                selectAll.value = false;
                toast.success(`${count} aziend${count === 1 ? 'a eliminata' : 'e eliminate'}! 🗑️`,
                    `L${count === 1 ? '\'azienda è stata rimossa' : 'e aziende sono state rimosse'} con successo`);
            },
            // Stampa messaggio
            onError: () => {
                toast.error('Errore!', 'Impossibile eliminare le aziende selezionate');
            }
        });
    }
}

// Popup di eliminazione tramite tasto riga
function deleteCompany(id) {
    if (confirm("Vuoi davero eliminare l'azienda?")) {
        router.delete(route('companies.destroy', id), {
            onSuccess: () => {
                toast.success('Azienda eliminata! 🗑️', 'L\'azienda è stata rimossa con successo');
            },
            onError: () => {
                toast.error('Errore!', 'Impossibile eliminare l\'azienda');
            }
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="space-y-6">
                <!-- Titolo -->
                <Card>
                    <CardHeader>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <CardTitle class="flex items-center gap-2">
                                <Building2 class="h-6 w-6 text-blue-600" />
                                Gestione Aziende
                            </CardTitle>
                        </div>
                    </CardHeader>
                </Card>

                <!-- Barra di ricerca -->
                <Card>
                    <CardContent class="p-6">
                        <div>
                            <label for="search" class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-2">
                                <Search class="h-4 w-4 text-gray-500" />
                                Ricerca Aziende
                            </label>
                            <div class="relative">
                                <input id="search" type="text" v-model="searchTerm"
                                    placeholder="Cerca per nome, città, email, indirizzo..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" />
                                <!-- Componenete che mostra icona lente -->
                                <Search
                                    class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                            </div>
                        </div>

                        <!-- Box selezione -->
                        <div v-if="selectedCompanies.length > 0"
                            class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <CheckSquare class="h-5 w-5 text-blue-600" />
                                    <!-- Mostra il numero di selezione e stampa messaggio -->
                                    <span class="text-sm font-medium text-blue-900">
                                        {{ selectedCompanies.length }} {{ selectedCompanies.length === 1 ? 'azienda selezionata' : 'aziende selezionate' }}
                                    </span>
                                </div>
                                <button @click="deleteSelectedCompanies"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    <!-- Bottone -->
                                    <Trash2 class="h-4 w-4" />
                                    Elimina Selezionate
                                </button>
                            </div>
                        </div>

                        <!-- Messaggio flesh -->
                        <div v-if="$page.props.flash.message" class="mt-4">
                            <Alert class="border-blue-200 bg-blue-50">
                                <Building2 class="h-4 w-4 text-blue-600" />
                                <AlertTitle class="text-blue-800">Operazione completata!</AlertTitle>
                                <AlertDescription class="text-blue-700">
                                    {{ $page.props.flash.message }}
                                </AlertDescription>
                            </Alert>
                        </div>
                    </CardContent>
                </Card>

                <!-- Tabella Compagnie -->
                <Card>
                    <CardContent class="p-0">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <button @click="toggleSelectAll" class="flex items-center gap-2">
                                                <!-- Mostra la casella piena o vuota della colonna Seleziona -->
                                                <component :is="selectAll ? CheckSquare : Square"
                                                    class="h-4 w-4 text-blue-600 hover:text-blue-800 transition-colors" />
                                                <span>Seleziona</span>
                                            </button>
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            ID</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Azienda</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Indirizzo</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            CAP</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Città</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Provincia</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Regione</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Azioni</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!-- Cicla su tutte le compagnie per gestire il rendering quando ci troviamo sopra una righa o contiene l'id dell'azienda che abbiamo selezionato -->
                                    <tr v-for="company in filteredCompanies" :key="company.id"
                                        class="hover:bg-gray-50 transition-colors"
                                        :class="{ 'bg-blue-50': selectedCompanies.includes(company.id) }">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button @click="toggleCompanySelection(company.id)"
                                                class="flex items-center">
                                                <component
                                                    :is="selectedCompanies.includes(company.id) ? CheckSquare : Square"
                                                    class="h-4 w-4 text-blue-600 hover:text-blue-800 transition-colors" />
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ company.id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <Building2 class="h-4 w-4 text-blue-600" />
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ company.company_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.address }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.zip_code }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.city }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.province }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.region }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ company.email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center gap-2">
                                                <Link :href="route('companies.edit', company.id)"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition-colors">
                                                <Edit class="h-3 w-3" />
                                                Modifica
                                                </Link>
                                                <button @click="deleteCompany(company.id)"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded hover:bg-red-100 transition-colors">
                                                    <Trash2 class="h-3 w-3" />
                                                    Elimina
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Messaggio ricerca fallita -->
                            <div v-if="filteredCompanies.length === 0" class="text-center py-12">
                                <Building2 class="mx-auto h-12 w-12 text-gray-400" />
                                <h3 class="mt-2 text-sm font-medium text-gray-900">
                                    {{ searchTerm ? 'Nessuna azienda trovata' : 'Nessuna azienda presente' }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ searchTerm ? 'Prova a modificare i termini di ricerca.' : 'Inizia aggiungendo la prima azienda.' }}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Pagination -->
                <Card v-if="companies.links && companies.links.length > 3">
                    <CardContent class="p-4">
                        <Pagination :links="companies.links" />
                    </CardContent>
                </Card>
            </div>
        </template>
    </AuthenticatedLayout>
</template>
