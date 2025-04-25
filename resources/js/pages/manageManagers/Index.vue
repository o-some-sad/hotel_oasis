<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PaginationData } from '.';
import ManagersTable from './ManagersTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Manage Managers',
        href: '/managers',
    },
];

const props = defineProps<{
    pagination: PaginationData;
}>();

const data = props.pagination.data;
</script>

<template>
    <Head title="Manage Managers" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ManagersTable :links="props.pagination.links" :data="data" />
        </div>
    </AppLayout>
</template>
