<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CreateTicketLayout from '@/Layouts/CreateTicketLayout.vue';
import debounce from 'lodash/debounce';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Ticket from '@/Pages/Ticket/Partials/Ticket.vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const columns = ref(['Id', 'Content', 'Comment', 'Status', 'Ticket_Type']);
const createTicket = ref(false);
const filters = usePage().props.filters;
const search = ref(filters.search ?? '');
const sortDirection = ref('desc');
const sortKey = ref('id');

const create = () => {
    createTicket.value = true;
}

const closeModal = () => {
    createTicket.value = false;
}

const sortBy = (column) => {
    if (sortKey.value === column) {
        sortDirection.value = sortDirection.value === 'desc' ? 'asc' : 'desc';
    } else {
        sortKey.value = column;
    }

    router.get(
        router.page.url,
        { sort: column, direction: sortDirection.value },
        { preserveState: true, replace: true }
    );
};

watch(search, debounce((value) => {
    router.get(router.page.url, { search: value }, { preserveState: true, replace: true });
}, 500));
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="pb-2 sm:pb-0 font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
            <div class="flex">
                <input v-model="search" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mr-4" placeholder="Search ..." />
                <PrimaryButton class="hidden lg:block" @click="create">Create new ticket</PrimaryButton>
                <PrimaryButton class="lg:hidden" @click="create">+</PrimaryButton>
            </div>
        </template>

        <Modal :show="createTicket" @close="closeModal">
            <CreateTicketLayout :closeModal="closeModal" />
        </Modal>

        <h3 class="max-w-7xl mx-auto py-6 px-2 sm:px-4 lg:px-6" v-if="!$page.props.tickets.data.length">No tickets to display.</h3>

        <div class="py-2 lg:py-4 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between lg:grid lg:grid-cols-[50px_minmax(200px,_1fr)_minmax(100px,_1fr)_minmax(100px,_1fr)_minmax(100px,_1fr)_150px] bg-slate-300 rounded-lg">
            <div v-for="column in columns">
                <div @click.prevent="sortBy(column.toLowerCase())" class="hover:cursor-pointer">
                    <span class="pr-2">{{ column.replace('_', ' ') }}</span>
                    <span v-if="sortDirection === 'asc' && sortKey === column.toLowerCase()">&uarr;</span>
                    <span v-if="sortDirection === 'desc' && sortKey === column.toLowerCase()">&darr;</span>
                </div>
            </div>
        </div>

        <div v-if="$page.props.tickets.data.length">
            <div v-for="ticket in $page.props.tickets.data" :key="ticket.id" class="even:bg-slate-200/75 max-w-7xl mx-auto sm:px-6 lg:px-8 rounded-lg">
                <Ticket :ticket="ticket" />
            </div>
        </div>

        <Pagination v-if="$page.props.tickets.data.length" :links="$page.props.tickets.links"/>

    </AuthenticatedLayout>
</template>
