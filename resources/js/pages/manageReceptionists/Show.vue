<template>
    <div>
        <Head title="Receptionist Details" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col gap-4 rounded-xl p-4 bg-white dark:bg-gray-900">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">{{ receptionist.name }}</h3>
                            <Link
                                :href="route('receptionists.index')"
                                class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300"
                            >
                                Back to List
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Avatar -->
                            <div>
                                <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                    <div v-if="receptionist.avatar_img" class="flex justify-center mb-4">
                                        <img
                                            :src="`/storage/${receptionist.avatar_img}`"
                                            alt="Avatar"
                                            class="h-48 w-48 rounded-full object-cover"
                                        />

                                    </div>
                                    <div v-else class="h-48 flex items-center justify-center rounded-full bg-gray-200 dark:bg-gray-800 mb-4">
                                        <span class="text-2xl text-gray-500 dark:text-gray-400">No Image</span>
                                    </div>

                                    <div class="text-center">
                    <span
                        v-if="receptionist.banned_at"
                        class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-200"
                    >
                      Banned
                    </span>
                                        <span
                                            v-else
                                            class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-200"
                                        >
                      Active
                    </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Info -->
                            <div class="md:col-span-2 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="(label, key) in infoFields" :key="key" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                        <p class="text-sm font-medium text-gray-500">{{ label }}</p>
                                        <p class="mt-1 text-lg capitalize">
                                            {{ key === 'created_at' ? formatDate(receptionist[key]) : receptionist[key] }}
                                        </p>
                                    </div>
                                    <div
                                        v-if="receptionist.banned_at"
                                        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md"
                                    >
                                        <p class="text-sm font-medium text-gray-500">Banned At</p>
                                        <p class="mt-1 text-lg">{{ formatDate(receptionist.banned_at) }}</p>
                                    </div>
                                </div>

                                <div class="mt-6 flex space-x-3">
                                    <Button
                                        v-if="receptionist.banned_at"
                                        class="bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-400"
                                        @click="handleUnban(receptionist)"
                                    >
                                        Unban Client
                                    </Button>
                                    <Button
                                        v-else
                                        class="bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-400"
                                        @click="handleBan(receptionist)"
                                    >
                                        Ban Client
                                    </Button>
                                    <Button
                                        class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400"
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
                        <AlertDialogDescription>
                            This action cannot be undone. This will permanently delete the client.
                        </AlertDialogDescription>
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
import {Head, Link, router} from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle
} from '@/components/ui/alert-dialog'
// import type {Receptionist} from "@/types/index.js";

const props = defineProps({
    receptionist: Object
})

const receptionist = props.receptionist

console.log('Receptionist Props:', receptionist)

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manage Receptionists', href: '/receptionists' },
    { title: 'Receptionist Details', href: `/receptionists/${receptionist.id}` }
]

const infoFields = {
    name: 'Name',
    email: 'Email',
    national_id: 'National ID',
    mobile: 'Mobile',
    country: 'Country',
    gender: 'Gender',
    created_at: 'Created At'
}

const isDeleteDialogOpen = ref(false)

const openDeleteDialog = () => {
    isDeleteDialogOpen.value = true
}

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
            router.visit('/receptionists')
        },
    })
}


const formatDate = (date) => {
    return new Date(date).toLocaleString()
}
</script>

