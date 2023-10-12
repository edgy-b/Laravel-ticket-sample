<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps<{
    closeModal: () => void;
}>();

const form = useForm({
    type: ''
});

const submit = () => {
    form.post(route('types.store'), {
        onSuccess: () => props.closeModal()
    });
};
</script>

<template>
    <div class="min-h-1/2 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100">
        <form @submit.prevent="submit" class="w-full p-6 lg:p-10">
            <div>
                <InputLabel for="comment" value="Ticket type" :isRequired=true />
                <TextInput
                    id="comment"
                    type="text"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    v-model="form.type"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.type" />
            </div>

            <div class="flex items-center justify-around mt-4">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create new ticket
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
