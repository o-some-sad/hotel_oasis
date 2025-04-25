<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import StaffTable from './StaffTable.vue';

const props = defineProps<{
    data: any;
    links: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Clients Reservations',
        href: '/clients-reservations',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" title="Clients Reservations">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="text-2xl font-bold">All Clients Reservations</h1>
            <StaffTable :links="props.links" :data="props.data" />
        </div>
    </AppLayout>
</template>
