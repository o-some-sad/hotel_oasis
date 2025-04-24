<template>
    <div>
        <Head title="Room Details" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col gap-4 rounded-xl p-4">
                <div class="overflow-hidden rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">{{ room.number }}</h3>
                            <Link :href="route('rooms.index')" class="rounded-md px-4 py-2 text-sm font-medium">
                                <Button>Back to List</Butt>
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="space-y-4 md:col-span-2">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div v-for="(label, key) in infoFields" :key="key" class="rounded-md p-4">
                                        <p class="text-sm font-medium">{{ label }}</p>
                                        <p class="mt-1 text-lg capitalize">
                                            <template v-if="key === 'created_at'">
                                                {{ room[key] }}
                                            </template>
                                            <template v-else-if="key === 'price'">
                                                ${{ parseFloat(room[key].replace('$', '')).toFixed(2) }}
                                            </template>
                                            <template v-else-if="key === 'is_reserved'">
                                                {{ room[key] ? 'Yes' : 'No' }}
                                            </template>
                                            <template v-else-if="key === 'floor_id'">
                                                {{ room[key] }}
                                            </template>
                                            <template v-else>
                                                {{ room[key] }}
                                            </template>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex space-x-3">
                                    <Button class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400" @click="openDeleteDialog">
                                        Delete Room
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <AlertDialog :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                        <AlertDialogDescription> This action cannot be undone. This will permanently delete the room. </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="isDeleteDialogOpen = false">Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="confirmDelete(room)" class="bg-red-600 text-white hover:bg-red-700"> Delete </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </AppLayout>
    </div>
</template>

<script setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    room: Object,
});

const room = props.room.data;
console.log(room);

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manage Rooms', href: '/rooms' },
    { title: 'Room Details', href: `/rooms/${room.id}` },
];

const infoFields = {
    number: 'Number',
    price: 'Price',
    capacity: 'Capacity',
    floor_id: 'Floor',
    created_at: 'Created At',
    created_by: 'Creator',
    is_reserved: 'Reserved',
};

const isDeleteDialogOpen = ref(false);

const openDeleteDialog = () => {
    isDeleteDialogOpen.value = true;
};

function confirmDelete(room) {
    router.delete(route('rooms.destroy', { room: room.id }), {
        onSuccess: () => {
            router.visit('/rooms');
        },
    });
}
</script>
