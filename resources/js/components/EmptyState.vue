<!-- Componente per uno "stato vuoto" -->
<template>
    <!-- Contenitore -->
    <div class="min-h-[400px] flex items-center justify-center">
        <div class="text-center">
            <!-- Icona cerchio nel caso non venga passata un'icona -->
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
                <!-- Icona della i di informazione -->
                <slot name="icon">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </slot>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ title }}</h3>
            <p class="text-gray-500 mb-4">{{ description }}</p>
            <slot name="action" />
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: 'Nessun dato disponibile'
    },
    description: {
        type: String,
        default: 'Non ci sono informazioni da visualizzare al momento.'
    }
})
</script>