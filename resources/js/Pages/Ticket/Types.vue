<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import CreateTicketTypeLayout from '@/Layouts/CreateTicketTypeLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import EditTicketTypeLayout from '@/Layouts/EditTicketTypeLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const types = computed(() => usePage().props.ticketTypesPaginated);
console.log(types,'types');

const form = useForm({
    ticketType: types.type,
    id: types.id
});

const createTicket = ref(false);
const editTicket = ref(false);

const create = () => {
    createTicket.value = true;
}

const closeModal = () => {
    createTicket.value = false;
    editTicket.value = false;
}
</script>

<template>
    <Head title="Ticket types" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="pb-2 font-semibold text-xl text-gray-800 leading-tight">Ticket types</h2>
            <PrimaryButton @click="create">
                Create new ticket
            </PrimaryButton>
        </template>

        <Modal :show="createTicket" @close="closeModal">
            <CreateTicketTypeLayout :closeModal="closeModal" />
        </Modal>

        <Modal :show="editTicket" @close="closeModal">
            <form class="w-full p-6 lg:p-10" @submit.prevent="form.patch(route('types.update'))">
                <div class="pb-4">
                    <InputLabel for="type" value="Ticket type" :isRequired="true" />
                    <TextInput
                        id="type"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.ticketType"
                        required
                        autofocus
                        autocomplete="type"
                    />
                    <InputError class="mt-2" :message="form.errors.ticketType" />
                </div>
                <PrimaryButton>Save</PrimaryButton>
            </form>
        </Modal>

        <div class="lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6" v-for="ticket in types.data">
                <EditTicketTypeLayout :ticket="ticket" :closeModal="closeModal" />
            </div>
        </div>

        <Pagination :links="types.links" />

    </AuthenticatedLayout>
</template>
