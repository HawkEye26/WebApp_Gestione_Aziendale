<!-- Grafico a barre -->
<template>
    <div class="w-full">
        <div class="space-y-3">
            <!-- Cicla su tutti gli elementi di data e stampa anche un cerchietto accanto -->
            <div v-for="(item, index) in data" :key="index" class="flex items-center">
                <div class="flex-1 flex items-center space-x-3">
                    <!-- Assegnazione del colore in maniera ciclica con l'operatore modulo % -->
                    <div :class="colors[index % colors.length]" class="w-4 h-4 rounded-full flex-shrink-0"></div>
                    <span class="text-sm font-medium text-gray-700 flex-1">{{ item.label }}</span>
                    <span class="text-sm text-gray-500">{{ item.value }}</span>
                    <span class="text-xs text-gray-400">{{ getPercentage(item.value) }}%</span>
                </div>
            </div>

            <!-- Barre orizzontale -->
            <div class="space-y-2 mt-4">
                <div v-for="(item, index) in data" :key="`bar-${index}`" class="relative">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <!-- Barra colorata in base alla percentuale del valore rispetto al valore massimo -->
                        <div :class="colors[index % colors.length]"
                            class="h-2 rounded-full transition-all duration-700 ease-out"
                            :style="{ width: `${getPercentage(item.value)}%` }"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

// Ogni oggetto deve avere almeno label e value
const props = defineProps({
    data: {
        type: Array,
        required: true,
        default: () => []
    }
})

const colors = [
    'bg-blue-500',
    'bg-green-500',
    'bg-purple-500',
    'bg-orange-500',
    'bg-red-500',
    'bg-indigo-500',
    'bg-pink-500',
    'bg-yellow-500'
]

// Trova il valore massimo in maniera dinamica usando spread(...) per trasf. l'array in singoli argomenti 
const maxValue = computed(() => {
    return Math.max(...props.data.map(item => item.value))
})

// Calcolo percentuale evitando la divisione per 0 (error) e arrotondando
const getPercentage = (value) => {
    if (maxValue.value === 0) return 0
    return Math.round((value / maxValue.value) * 100)
}
</script>