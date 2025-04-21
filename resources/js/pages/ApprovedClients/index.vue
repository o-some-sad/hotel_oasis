<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import ApprovedClientsTable from './ApprovedClientsTable.vue';
import { RowData } from '.';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: "Approved clients",
        href: "/approved-clients",
    },
];

const props = defineProps<{
    data: RowData[];
}>();


console.log(props.data);

</script>

<template>
    <Head title="Approved clients" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ApprovedClientsTable :data="data" />
        </div>
    </AppLayout>
</template>
