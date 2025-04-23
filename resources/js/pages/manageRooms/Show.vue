<template>
    <div>
        <Head title="Room Details" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex flex-col gap-4 rounded-xl p-4 bg-white dark:bg-gray-900">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">{{ room.number }}</h3>
                            <Link
                                :href="route('rooms.index')"
                                class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300"
                            >
                                Back to List
                            </Link>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center">
                <span
                    v-if="room.is_reserved"
                    class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-900 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:text-red-200"
                >
                  Reserved
                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-green-100 dark:bg-green-900 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:text-green-200"
                                >
                  Unreserved
                </span>
                            </div>

                            <!-- Info -->
                            <div class="md:col-span-2 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div
                                        v-for="(label, key) in infoFields"
                                        :key="key"
                                        class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md"
                                    >
                                        <p class="text-sm font-medium text-gray-500">{{ label }}</p>
                                        <p class="mt-1 text-lg capitalize">
                                            <template v-if="key === 'created_at'">
                                                {{ formatDate(room[key]) }}
                                            </template>
                                            <template v-else-if="key === 'price'">
                                                ${{ (room[key] / 100).toFixed(2) }}
                                            </template>
                                            <template v-else-if="key === 'is_reserved'">
                                                {{ room[key] ? 'Yes' : 'No' }}
                                            </template>
                                            <template v-else>
                                                {{ room[key] }}
                                            </template>
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex space-x-3">
                                    <Button
                                        class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400"
                                        @click="openDeleteDialog"
                                    >
                                        Delete Room
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
                            This action cannot be undone. This will permanently delete the room.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="isDeleteDialogOpen = false">Cancel</AlertDialogCancel>
                        <AlertDialogAction @click="confirmDelete(room)" class="bg-red-600 text-white hover:bg-red-700">
                            Delete
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </AppLayout>
    </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
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

const props = defineProps({
    room: Object
})

const room = props.room
console.log(props.room);


const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manage Rooms', href: '/rooms' },
    { title: 'Room Details', href: `/rooms/${room.id}` }
]

const infoFields = {
    number: 'Number',
    price: 'Price',
    capacity: 'Capacity',
    floor_id: 'Floor',
    created_at: 'Created At',
    created_by: 'Creator',
    is_reserved: 'Reserved'
}
console.log(room.floor)

const isDeleteDialogOpen = ref(false)

const openDeleteDialog = () => {
    isDeleteDialogOpen.value = true
}

function confirmDelete(room) {
    router.delete(route('rooms.destroy', { room: room.id }), {
        onSuccess: () => {
            router.visit('/rooms')
        },
    })
}

const formatDate = (date) => {
    return new Date(date).toLocaleString()
}
</script>
