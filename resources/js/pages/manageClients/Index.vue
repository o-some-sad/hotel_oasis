<template>
    <Head title="Manage Clients" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Client Management</h2>
            </div>
            
            <ClientsTable :links="links" :data="data" />
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ClientsTable from './ClientsTable.vue';
import { type BreadcrumbItem } from '@/types';
import { PaginationData } from '.';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Manage Clients',
        href: '/clients',
    },
];

const props = defineProps<{
    pagination?: PaginationData;
}>();

const data = props.pagination?.data ?? [];
const links = props.pagination?.links ?? [];

function goToPage(url: string) {
    router.visit(url);
}
</script>