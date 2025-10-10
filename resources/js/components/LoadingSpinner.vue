<!-- Componente per i caricamenti animati -->
<template>
    <div class="flex items-center justify-center p-8">
        <div class="flex flex-col items-center space-y-4">
            <!-- Spinner animato -->
            <div class="relative">
                <div class="w-12 h-12 border-4 border-blue-200 rounded-full animate-pulse"></div>
                <div
                    class="absolute top-0 left-0 w-12 h-12 border-4 border-blue-600 rounded-full border-t-transparent animate-spin">
                </div>
            </div>

            <!-- Testo di caricamento -->
            <div class="text-center">
                <p class="text-sm font-medium text-gray-700">{{ title }}</p>
                <p v-if="description" class="text-xs text-gray-500 mt-1">{{ description }}</p>
            </div>

            <!-- Barra di progresso opzionale -->
            <div v-if="showProgress" class="w-48 h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all duration-300 ease-out"
                    :style="{ width: `${progress}%` }"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    title: {
        type: String,
        default: 'Caricamento...'
    },
    description: {
        type: String,
        default: ''
    },
    showProgress: {
        type: Boolean,
        default: false
    },
    duration: {
        type: Number,
        // 3 secondi
        default: 3000 
    }
})

const progress = ref(0)
let progressInterval = null

// Se la barra è attiva calcola di quanto aumentare ogni 100ms fino al 100%
onMounted(() => {
    if (props.showProgress) {
        const increment = 100 / (props.duration / 100)
        progressInterval = setInterval(() => {
            if (progress.value < 100) {
                progress.value += increment
            } else {
                clearInterval(progressInterval)
            }
        }, 100)
    }
})

// Quando termina la barra, ferma anche il timer
onUnmounted(() => {
    if (progressInterval) {
        clearInterval(progressInterval)
    }
})
</script>

<!-- Animazione spinner CSS -->
<style scoped>
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Animazione spinner trasparenza da pieno a mezzo trasparente */
@keyframes pulse {

    0%,
    100% {
        opacity: 1;
    }

    50% {
        opacity: .5;
    }
}
</style>