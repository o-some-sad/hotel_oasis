<template>
    <Head title="Manage Clients" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">Client Management</h2>
                <Link :href="'/clients/create'">
                    <button class="bg-indigo-600 text-white rounded-md px-4 py-2 hover:bg-indigo-700 transition">
                        Add Client
                    </button>
                </Link>
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