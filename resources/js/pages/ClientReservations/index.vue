<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import Table from './Table.vue';

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
        title: "My Reservations",
        href: "/my-reservations",
    },
]
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" title="Client Reservations">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
           
            <Table :links="[]" :data="props.data" />
        </div>
    </AppLayout>
</template>