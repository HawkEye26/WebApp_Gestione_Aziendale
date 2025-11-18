<template>
    <div :class="cardClasses">
        <slot />
    </div>
</template>

<script setup>
import { computed } from 'vue'

// Determina stile delle card
const props = defineProps({
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'outline', 'secondary'].includes(value)
    },
    class: {
        type: String,
        default: ''
    }
})

// Classe comune per tutte le card
const cardClasses = computed(() => {
    const baseClasses = 'rounded-xl border text-card-foreground shadow'

    // Stile in base alla variante
    const variantClasses = {
        default: 'bg-card',
        outline: 'border border-border',
        secondary: 'bg-secondary'
    }

    return `${baseClasses} ${variantClasses[props.variant]} ${props.class}`
})
</script>