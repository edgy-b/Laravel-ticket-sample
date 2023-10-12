<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';

const allowEdit = ref(false);
const props = defineProps<{
    ticket: { type: string, id: number };
    closeModal: () => void;
}>();

const form = useForm({
    type: props.ticket.type,
    id: props.ticket.id
});

const edit = (e: Event) => {
    e.preventDefault();
    allowEdit.value = true;
}

const cancel = (e: Event) => {
    e.preventDefault();
    allowEdit.value = false;
}

const submit = (action: string) => {    
    switch (action) {
        case 'delete':
            form.delete(route('types.destroy'))
            break;

        case 'update':
            form.patch(route('types.update'));
            break;
    
        default:
            break;
    }

    allowEdit.value = false;
};
</script>

<template>
    <div class="min-h-1/2 flex flex-col sm:justify-center items-center sm:pt-0 bg-gray-100">
        <form class="w-full p-2 lg:p-6" @submit.prevent="">
            <div class="p-4 sm:p-8 bg-white shadow rounded-lg flex justify-between">
                <div v-if="allowEdit" class="w-full md:w-1/2">
                    <InputLabel for="comment" value="Ticket type" :isRequired=true />
                    <TextInput
                        id="comment"
                        type="text"
                        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                        v-model="form.type"
                        required
                        autofocus
                    />
                    <TextInput
                        type="hidden"
                        v-model="form.id"
                    />
                    <InputError class="mt-2" :message="form.errors.type" />
                    <div class="pt-4 flex">
                        <SecondaryButton class="mr-4" @click="cancel">Cancel</SecondaryButton>
                        <PrimaryButton @click="submit('update')">Update</PrimaryButton>
                    </div>
                </div>
                <div v-else>{{ props.ticket.type }}</div>
                <div class="flex items-center pl-4">
                    <PrimaryButton @click="edit" v-if="!allowEdit">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title></title> <g id="Complete"> <g id="edit"> <g> <path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path> <polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polygon> </g> </g> </g> </g></svg>
                    </PrimaryButton>
                    <DangerButton @click="submit('delete')" v-if="allowEdit">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M10 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M14 12V17" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M4 7H20" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>    
                    </DangerButton>
                </div>
            </div>
        </form>
    </div>
</template>
