<script setup lang="ts">
import {} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { PaginationData } from '.';
import ReceptionistsTable from './ReceptionistsTable.vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Receptionist',
        href: '/receptionists',
    },
];

const props = defineProps<{
    pagination: PaginationData;
}>();
const data = props.pagination.data;
</script>
<template>
    <Head title="Receptionist" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ReceptionistsTable :data="data" :links="props.pagination.links" />
        </div>
    </AppLayout>
</template>
