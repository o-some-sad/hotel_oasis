<template>
    <div>
        <Head title="Receptionist Details" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col gap-4 rounded-xl p-4">
                <div class="overflow-hidden rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">{{ receptionist.name }}</h3>
                            <Link :href="route('receptionists.index')" class="rounded-md px-4 py-2 text-sm font-medium">
                                <Button> Back to List</Button>
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Avatar -->
                            <div>
                                <div class="rounded-lg">
                                    <div v-if="receptionist.avatar_img" class="mb-4 flex justify-center">
                                        <img :src="`/storage/${receptionist.avatar_img}`" alt="Avatar" class="h-48 w-48 rounded-full object-cover" />
                                    </div>
                                    <div v-else class="mb-4 flex h-48 items-center justify-center rounded-full">
                                        <span class="text-2xl">No Image</span>
                                    </div>

                                    <div class="text-center">
                                        <span
                                            v-if="receptionist.banned_at"
                                            class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200"
                                        >
                                            Banned
                                        </span>
                                        <span v-else class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"> Active </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="space-y-4 md:col-span-2">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div v-for="(label, key) in infoFields" :key="key" class="rounded-md">
                                        <p class="text-sm font-medium">{{ label }}</p>
                                        <p class="mt-1 text-lg capitalize">
                                            {{ key === 'created_at' ? dayjs(receptionist[key]).format('YYYY-MM-DD ') : receptionist[key] }}
                                        </p>
                                    </div>
                                    <div v-if="receptionist.banned_at" class="rounded-md">
                                        <p class="text-sm font-medium">Banned At</p>
                                        <p class="mt-1 text-lg">{{ formatDate(receptionist.banned_at) }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex space-x-3">
                                    <Button v-if="receptionist.banned_at" class="" @click="handleUnban(receptionist)"> Unban Client </Button>
                                    <Button v-else class="" @click="handleBan(receptionist)"> Ban Client </Button>
                                    <Button
                                        class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:text-white dark:hover:bg-red-400"
                                        @click="openDeleteDialog"
                                    >
                                        Delete Client
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Dialog -->
            <AlertDialog :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                        <AlertDialogDescription> This action cannot be undone. This will permanently delete the client. </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="isDeleteDialogOpen = false">Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="confirmDelete(props.receptionist)" class="bg-red-600 text-white hover:bg-red-700">
                            Delete
                        </AlertDialogAction>
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
import dayjs from 'dayjs';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
// import type {Receptionist} from "@/types/index.js";

const props = defineProps({
    receptionist: Object,
});

const receptionist = props.receptionist;

console.log('Receptionist Props:', receptionist);

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manage Receptionists', href: '/receptionists' },
    { title: 'Receptionist Details', href: `/receptionists/${receptionist.id}` },
];

const infoFields = {
    name: 'Name',
    email: 'Email',
    national_id: 'National ID',
    mobile: 'Mobile',
    country: 'Country',
    gender: 'Gender',
    created_at: 'Created At',
};

const isDeleteDialogOpen = ref(false);

const openDeleteDialog = () => {
    isDeleteDialogOpen.value = true;
};

function handleBan(receptionist) {
    router.patch(
        route('receptionists.update', { receptionist: receptionist.id }),
        {
            banned_at: new Date().toISOString(),
            _method: 'PATCH',
        },
        {
            preserveState: true,
            onSuccess: () => {
                // Update the local state immediately
                receptionist.banned_at = new Date().toISOString();
            },
        },
    );
}

function handleUnban(receptionist) {
    router.patch(
        route('receptionists.update', { receptionist: receptionist.id }),
        {
            banned_at: null,
            _method: 'PATCH',
        },
        {
            preserveState: true,
            onSuccess: () => {
                // Update the local state immediately
                receptionist.banned_at = null;
            },
        },
    );
}

function confirmDelete(receptionist) {
    router.delete(route('receptionists.destroy', { receptionist: receptionist.id }), {
        onSuccess: () => {
            router.visit('/receptionists');
        },
    });
}

const formatDate = (date) => {
    return new Date(date).toLocaleString();
};
</script>
