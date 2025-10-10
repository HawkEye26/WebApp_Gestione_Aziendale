<template>
    <div :class="cardClasses">
        <slot />
    </div>
</template>

<script setup>
import { computed } from 'vue'

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

const cardClasses = computed(() => {
    const baseClasses = 'rounded-xl border text-card-foreground shadow'

    const variantClasses = {
        default: 'bg-card',
        outline: 'border border-border',
        secondary: 'bg-secondary'
    }

    return `${baseClasses} ${variantClasses[props.variant]} ${props.class}`
})
</script>