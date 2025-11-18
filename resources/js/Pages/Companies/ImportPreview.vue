<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { toast } from '@/utils/toast';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Upload, FileSpreadsheet, CheckCircle, AlertCircle, Info, Shield, FileText, FileCode } from 'lucide-vue-next';

// stato del file selezionato
const file = ref(null)

// per messaggi di feedback per l'importazione
const success = ref('')
const errors = ref('')

// Funzione per ottenere l'icona del file in base all'estensione
function getFileIcon(fileName) {

  // Restituisce l'icona nel caso sia vuota o nulla
  if (!fileName) return FileSpreadsheet;

  // Divide il nome del file e prende solo l'estensione per selezionare l'icona
  const extension = fileName.split('.').pop().toLowerCase();
  switch (extension) {
    case 'json':
      return FileCode;
    case 'csv':
      return FileText;
    case 'xls':
    case 'xlsx':
    default:
      return FileSpreadsheet;
  }
}

// Gestione selezione file
function handleFileChange(e) {
  errors.value = ''
  success.value = ''

  // Prende il primo elemento dalla lista del file
  file.value = e.target.files[0] || null

  if (file.value) {
    const fileType = file.value.name.split('.').pop().toLowerCase();

    // Variabile temporanea per stampare a schermo
    let fileTypeText = '';

    switch (fileType) {
      case 'json':
        fileTypeText = 'JSON';
        break;
      case 'csv':
        fileTypeText = 'CSV';
        break;
      case 'xls':
        fileTypeText = 'XLS';
        break;
      case 'xlsx':
        fileTypeText = 'Excel';
        break;
      default:
        fileTypeText = 'file';
    }

    toast.info(`File ${fileTypeText} selezionato 📁`, `${file.value.name} pronto per l'upload`);
  }
}

// Controllo upload
function upload() {
  if (!file.value) {
    errors.value = 'Seleziona un file prima di caricare.'
    toast.warning('File mancante! ⚠️', 'Seleziona un file prima di caricare');
    return
  }

  // Toast di inizio caricamento
  toast.info('Caricamento in corso... ⏳', 'Il file è in fase di elaborazione elaborato');

  // Contenitore per inserire il file anche binario
  const formData = new FormData()
  formData.append('file', file.value)

  // Sistema per l'importazione del file esterno passando il file nel form
  router.post(route('companies.importStore'), formData, {
    onSuccess: (response) => {
      success.value = 'File importato con successo!'
      file.value = null
      toast.success('Import completato! 🎉', 'File importato con successo');
    },
    onError: (formErrors) => {
      errors.value = formErrors.file ?? 'Errore durante l\'importazione'
      toast.error('Errore Import! ❌', formErrors.file ?? 'Errore durante l\'importazione');
    }
  });
}
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="space-y-6">
        <!-- Titolo -->
        <Card>
          <CardHeader>
            <CardTitle class="flex items-center gap-2">
              <Upload class="h-6 w-6 text-blue-600" />
              Importazione Aziende da File Excel
            </CardTitle>
          </CardHeader>
        </Card>

        <!-- Struttura di importazione -->
        <Card>
          <CardContent class="p-8">
            <div class="max-w-2xl mx-auto">
              <div
                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                <FileSpreadsheet class="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Seleziona il file dei dati</h3>
                <p class="text-sm text-gray-500 mb-4">Carica un file Excel (.xls, .xlsx), JSON o CSV con i dati delle
                  aziende</p>

                <!-- Gestione selezione del file con @change -->
                <input type="file" accept=".xls,.xlsx,.json,.csv" @change="handleFileChange"
                  class="block w-full text-sm text-gray-700 bg-white border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
              </div>

              <!-- Preview del file -->
              <div v-if="file" class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center gap-3">
                  <!-- Icona in base al tipo -->
                  <component :is="getFileIcon(file.name)" class="h-8 w-8 text-blue-600" />
                  <div class="flex-1">
                    <h4 class="font-medium text-blue-900">{{ file.name }}</h4>
                    <p class="text-sm text-blue-700">
                      <!-- Mostra l'estensione del file in maiuscolo e converte la dimensione da byte a KB -->
                      Tipo: {{ file.name.split('.').pop().toUpperCase() }} •
                      Dimensione: {{ (file.size / 1024).toFixed(1) }} KB
                    </p>
                  </div>
                  <!-- Simbolo di conferma -->
                  <CheckCircle class="h-6 w-6 text-green-600" />
                </div>
              </div>

              <!-- Messaggio in caso di errore o successo -->
              <div v-if="errors" class="mt-4 p-4 bg-red-50 rounded-lg border border-red-200">
                <div class="flex items-center gap-2">
                  <AlertCircle class="h-5 w-5 text-red-600" />
                  <p class="text-red-800 font-medium">{{ errors }}</p>
                </div>
              </div>

              <div v-if="success" class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="flex items-center gap-2">
                  <CheckCircle class="h-5 w-5 text-green-600" />
                  <p class="text-green-800 font-medium">{{ success }}</p>
                </div>
              </div>

              <!-- Bottone Upload -->
              <div class="mt-6 flex justify-center">
                <!-- Attiva il bottone solo dopo il controllo che l'utente sia autenticato ed abbia i permessi per importare -->
                <button
                  v-if="$page.props.auth.user && Array.isArray($page.props.auth.user.can) && $page.props.auth.user.can.includes('import files')"
                  @click="upload"
                  class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-medium rounded-lg shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-50"
                  :disabled="!file">
                  <Upload class="h-4 w-4" />
                  Carica e Importa
                </button>
              </div>
            </div>
          </CardContent>
        </Card>

        <!-- Card estetiche inferiori -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <Card>
            <CardContent class="p-6">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <FileSpreadsheet class="h-8 w-8 text-green-600" />
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Formati Supportati</h3>
                  <p class="text-sm text-gray-500 mt-1">Excel (.xls, .xlsx), JSON (.json) e CSV (.csv)</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent class="p-6">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <Shield class="h-8 w-8 text-blue-600" />
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Sicurezza</h3>
                  <p class="text-sm text-gray-500 mt-1">I dati vengono validati e processati in modo sicuro</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent class="p-6">
              <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                  <Info class="h-8 w-8 text-purple-600" />
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-900">Supporto</h3>
                  <p class="text-sm text-gray-500 mt-1">Per problemi contatta l'assistenza tecnica</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Istruzioni -->
        <Card>
          <CardHeader>
            <CardTitle class="text-lg">📋 Istruzioni per l'importazione</CardTitle>
          </CardHeader>
          <CardContent class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <h4 class="font-medium text-gray-900">Struttura dei Dati</h4>
                <div class="space-y-3">
                  <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">📊 Excel/CSV:</p>
                    <div class="space-y-1 ml-4">
                      <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                        <span>Nome Azienda, Indirizzo, CAP, Città</span>
                      </div>
                      <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-1.5 h-1.5 bg-blue-600 rounded-full"></div>
                        <span>Provincia, Regione, Email</span>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-700 mb-2">🔧 JSON:</p>
                    <div class="ml-4">
                      <div class="flex items-center gap-2 text-sm text-gray-600">
                        <div class="w-1.5 h-1.5 bg-purple-600 rounded-full"></div>
                        <span>Array di oggetti con le stesse proprietà</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="space-y-4">
                <h4 class="font-medium text-gray-900">Consigli Utili</h4>
                <div class="space-y-2">
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                    <span>La prima riga deve contenere le intestazioni</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                    <span>Rimuovi righe vuote dal file</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                    <span>Massimo 1000 righe per file</span>
                  </div>
                  <div class="flex items-center gap-2 text-sm text-gray-600">
                    <div class="w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                    <span>L'email devono terminare con .it o .com</span>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </template>
  </AuthenticatedLayout>
</template>