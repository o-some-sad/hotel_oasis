<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import Table from './Table.vue';
import { PaginationData } from '.';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: "Managers",
        href: "/managers",
    },
];

const props = defineProps<{
    pagination?: PaginationData;
}>();

const data = props.pagination?.data ?? [];
const links = props.pagination?.links ?? [];

</script>

<template>
    <Head title="Managers" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Table :links="links" :data="data" />
        </div>
    </AppLayout>
</template>
