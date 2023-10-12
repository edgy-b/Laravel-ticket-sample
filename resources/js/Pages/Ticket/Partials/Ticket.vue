<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3'
import axios from 'axios';
import { ref } from 'vue';
const props = defineProps<{
    ticket: {
        id: number;
        comment?: string;
        content: string;
        type: string;
        status: string;
        images: string;
        ticket_type: string;
    };
}>();

const isAddComment = ref(false);
const images = props.ticket.images.split(',');

const form = useForm({
    id: props.ticket.id,
    comment: props.ticket.comment
});

const download = (imageLink: string) => {
    axios({
        method: 'GET',
        url: `/storage/tickets/${imageLink}`,
        responseType: 'blob'
    }).then((response) => {
        const fileUrl = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.download = imageLink;
        link.href = fileUrl;
        link.click();
        URL.revokeObjectURL(link.href);
    })
}

const close = () => {
    form.post(route('ticket.edit', route().params));
}

const editComment = () => {
    isAddComment.value = !isAddComment.value;
}

const saveComment = () => {
    form.patch(route('ticket.update', route().params), {
        onSuccess: () => isAddComment.value = false
    });
}
</script>

<template>
    <div class="overflow-hidden sm:rounded-lg">
        <div class="py-2 lg:py-4 lg:grid lg:grid-cols-[50px_minmax(200px,_1fr)_minmax(100px,_1fr)_minmax(100px,_1fr)_minmax(100px,_1fr)_150px] relative">
            <div>{{ ticket.id }}</div>
            <div>{{ ticket.content }}</div>
            <div>
                <div v-if="!isAddComment">{{ ticket.comment }}</div>
                <TextInput v-if="isAddComment" type="text" v-model="form.comment" />
                <PrimaryButton v-if="isAddComment" @click="saveComment">Save</PrimaryButton>
            </div>
            
            <div>{{ ticket.status }}</div>
            <div>{{ ticket.ticket_type }}</div>
            <div class="min-h-[76px]">
                <div>
                <SecondaryButton v-if="ticket.status !== 'closes'" class="mb-2" @click="close">Close ticket</SecondaryButton>
                </div>
                <PrimaryButton v-if="!isAddComment" class="" @click="editComment">Comment</PrimaryButton>
                <SecondaryButton v-else @click="editComment">Cancel</SecondaryButton>
            </div>
            
        </div>
        
        <div class="flex pb-2 lg:pb-4">
            <div v-for="imageLink in images" class="px-2 lg:px-4">
                <img class="w-16 h-16 rounded-lg object-cover" :src="`/storage/tickets/${imageLink}`" dowloadable />
                <button @click="download(imageLink)">Download</button>
            </div>
        </div>
    </div>
</template>
