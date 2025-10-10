<!-- Componente per notifiche Toast -->
<template>
    <div class="fixed bottom-4 right-4 z-50 space-y-2">
        <transition-group name="toast" tag="div">
            <div v-for="toast in toasts" :key="toast.id" :class="[
                'max-w-sm rounded-lg shadow-lg p-4 flex items-center space-x-3',
                toastClasses[toast.type]
            ]">
                <component :is="getIcon(toast.type)" class="w-5 h-5 flex-shrink-0" />
                <div class="flex-1">
                    <p class="text-sm font-medium">{{ toast.title }}</p>
                    <p v-if="toast.message" class="text-xs opacity-90">{{ toast.message }}</p>
                </div>
                <button @click="removeToast(toast.id)" class="flex-shrink-0 opacity-70 hover:opacity-100">
                    <X class="w-4 h-4" />
                </button>
            </div>
        </transition-group>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { CheckCircle, AlertCircle, XCircle, Info, X } from 'lucide-vue-next'

// Arrat reattivo che contiene tutti i toast attivi
const toasts = ref([])

const toastClasses = {
    success: 'bg-green-50 text-green-800 border border-green-200',
    error: 'bg-red-50 text-red-800 border border-red-200',
    warning: 'bg-yellow-50 text-yellow-800 border border-yellow-200',
    info: 'bg-blue-50 text-blue-800 border border-blue-200'
}

const getIcon = (type) => {
    switch (type) {
        case 'success': return CheckCircle
        case 'error': return XCircle
        case 'warning': return AlertCircle
        case 'info': return Info
        default: return Info
    }
}

// Aggiunta toast
const addToast = (toast) => {
    const id = Date.now()
    toasts.value.push({ ...toast, id })

    // Auto rimuovi dopo 5 secondi
    setTimeout(() => {
        removeToast(id)
    }, 5000)
}

// Rimozione toast manuale o automatica
const removeToast = (id) => {
    const index = toasts.value.findIndex(toast => toast.id === id)
    if (index > -1) {
        toasts.value.splice(index, 1)
    }
}

// Esponiamo le funzioni per essere usate da componenti parent
defineExpose({
    addToast,
    removeToast
})

// Listener per eventi toast globali
onMounted(() => {
    window.addEventListener('show-toast', (event) => {
        addToast(event.detail)
    })
})
</script>

<!-- Animazione per far scorrere i Toast da destra verso sinistra -->
<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
</style>