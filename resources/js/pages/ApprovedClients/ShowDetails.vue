<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItemType } from '@/types';

const props = defineProps<{
    id: string;
    client: any
}>()
console.log(props.client);


const breadcrumbs: BreadcrumbItemType[] = [
    {
        title: 'Dashboard',
        href: '/dashboard'
    },
    {
        title: 'Approved clients',
        href: '/approved-clients'
    },
    {
        title: `Show details for: ${props.id}`,
        href: `/approved-clients/${props.id}`
    }
];




</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- name -->
            <h1 class="mb-1 font-medium text-4xl">{{ props.client.name }}</h1>
            <div class="flex gap-4 flex-col lg:flex-row">
                <div class="flex-1/2 flex flex-col gap-4 border rounded-2xl p-4 w-full">
                    <div>
                        <div class="text-xl font-bold">Country</div>
                        <p>{{ props.client.country }}</p>
                    </div>
                    <div>
                        <div class="text-xl font-bold">Mobile</div>
                        <p>{{ props.client.mobile }}</p>
                    </div>
                    <div>
                        <div class="text-xl font-bold">Gender</div>
                        <p>{{ props.client.gender }}</p>
                    </div>
                </div>
                <div class="flex-1/2 flex flex-col gap-2 border rounded-2xl p-4 w-full">
                    <p class="text-xl font-bold">Recent resevations</p>
                    <p>no reservations</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>