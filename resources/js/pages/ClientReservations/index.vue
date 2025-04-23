<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';
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
           <i>PLEASE IMPLEMENT ME :)</i>
           <Link :href="route('my-reservations.create')">Create Reservation</Link>
        </div>
    </AppLayout>
</template>