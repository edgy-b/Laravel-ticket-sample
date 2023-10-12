<script setup lang="ts">
defineProps<{
    value?: string;
    isRequired?: boolean;
}>();
</script>

<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}<sup class="text-red-500" v-if="isRequired">*</sup></span>
        <span v-else><slot /></span>
    </label>
</template>
