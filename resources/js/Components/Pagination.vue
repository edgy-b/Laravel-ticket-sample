<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
defineProps<{
    links: Array<{ active: boolean, label: string, url: string }>;
}>();
</script>

<template>
    <div class="flex w-full lg:w-1/3 justify-between mx-auto p-4 lg:p-6">
        <div v-for="link in links">
            <Link
                v-if="link.url"
                :href="link.url"
                v-html="link.label"
            />
            <span class="text-gray-500" v-else v-html="link.label"></span>
        </div>
    </div>
</template>
