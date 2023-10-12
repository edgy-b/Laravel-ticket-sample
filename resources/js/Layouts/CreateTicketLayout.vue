<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import FilePond from '@/Components/FilePond.vue';

const props = defineProps<{
    closeModal: () => void;
}>();

const form = useForm({
    content: '',
    images: [],
    comment: '',
    type: ''
});

const ticketTypes = usePage().props.ticketTypes;

const submit = () => {
    form.post(route('ticket'), {
        onSuccess: () => props.closeModal()
    });
};
</script>

<template>
    <div class="min-h-1/2 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100">
        <form @submit.prevent="submit" class="w-full p-6 lg:p-10">
            <div class="pb-4">
                <InputLabel for="type" value="Ticket type" :isRequired=true />
                <select
                    id="type"
                    name="type"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    v-model="form.type"
                    required
                    autofocus
                >
                    <option :value="ticketType.id" v-for="ticketType in ticketTypes">{{ ticketType.type }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.type" />
            </div>

            <div class="pb-4">
                <InputLabel for="email" value="Ticket content" :isRequired=true />
                <textarea
                    id="content"
                    name="content"
                    type="textarea"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    v-model="form.content"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.content" />
            </div>

            <div class="pb-4">
                <InputLabel for="images" value="Upload images" :isRequired=true />
                <FilePond :form="form" />
            </div>

            <div>
                <InputLabel for="comment" value="Comment" />
                <TextInput
                    id="comment"
                    type="text"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    v-model="form.comment"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.content" />
            </div>

            <div class="flex items-center justify-center mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create new ticket
                </PrimaryButton>
            </div>
        </form>
        <slot />
    </div>
</template>
