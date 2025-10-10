<!-- Componente base per lo stile -->
<template>
    <!-- Imposta lo stile in base alla variante scelta con il biding dinamico :class -->
    <Card :class="cardClass" class="hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium" :class="textClass">
                {{ title }}
            </CardTitle>
            <!-- Icona dinamica -->
            <component :is="icon" :class="iconClass" class="h-6 w-6" />
        </CardHeader>
        <CardContent>
            <div class="text-3xl font-bold" :class="valueClass">{{ value }}</div>
            <p class="text-xs mt-1" :class="descriptionClass">
                {{ description }}
            </p>
            <slot name="extra" />
        </CardContent>
    </Card>
</template>

<script setup>
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        required: true
    },
    description: {
        type: String,
        required: true
    },
    icon: {
        type: Object,
        required: true
    },
    variant: {
        type: String,
        default: 'blue',
        validator: (value) => ['blue', 'green', 'purple', 'orange', 'red'].includes(value)
    }
});

const variants = {
    blue: {
        cardClass: 'bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200',
        textClass: 'text-blue-800',
        iconClass: 'text-blue-600',
        valueClass: 'text-blue-900',
        descriptionClass: 'text-blue-600'
    },
    green: {
        cardClass: 'bg-gradient-to-br from-green-50 to-green-100 border-green-200',
        textClass: 'text-green-800',
        iconClass: 'text-green-600',
        valueClass: 'text-green-900',
        descriptionClass: 'text-green-600'
    },
    purple: {
        cardClass: 'bg-gradient-to-br from-purple-50 to-purple-100 border-purple-200',
        textClass: 'text-purple-800',
        iconClass: 'text-purple-600',
        valueClass: 'text-purple-900',
        descriptionClass: 'text-purple-600'
    },
    orange: {
        cardClass: 'bg-gradient-to-br from-orange-50 to-orange-100 border-orange-200',
        textClass: 'text-orange-800',
        iconClass: 'text-orange-600',
        valueClass: 'text-orange-900',
        descriptionClass: 'text-orange-600'
    },
    red: {
        cardClass: 'bg-gradient-to-br from-red-50 to-red-100 border-red-200',
        textClass: 'text-red-800',
        iconClass: 'text-red-600',
        valueClass: 'text-red-900',
        descriptionClass: 'text-red-600'
    }
};

const currentVariant = variants[props.variant];
const cardClass = currentVariant.cardClass;
const textClass = currentVariant.textClass;
const iconClass = currentVariant.iconClass;
const valueClass = currentVariant.valueClass;
const descriptionClass = currentVariant.descriptionClass;
</script>