<template>
    <div>
        <Head title="Manager Details" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="overflow-hidden rounded-xl shadow-sm">
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">{{ manager.name }}</h3>
                            <div class="space-x-2">
                                <Link :href="route('managers.edit', manager.id)">
                                    <Button variant="secondary"> Edit Manager </Button>
                                </Link>
                                <Link :href="route('managers.index')">
                                    <Button variant="secondary"> Back to List </Button>
                                </Link>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Manager Avatar -->
                            <div class="col-span-1">
                                <div class="overflow-hidden rounded-lg p-4">
                                    <div v-if="manager.avatar_img" class="mb-4 flex justify-center">
                                        <img :src="avatar_url" :alt="manager.name" class="h-48 w-48 rounded-full object-cover object-center" />
                                    </div>
                                    <div v-else class="mb-4 flex h-48 w-full items-center justify-center rounded-lg bg-gray-200">
                                        <span class="text-gray-500">No Avatar</span>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="text-lg font-semibold">{{ manager.name }}</h4>
                                        <p class="text-gray-500">{{ manager.email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Manager Details -->
                            <div class="col-span-2 space-y-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div v-for="(label, key) in infoFields" :key="key" class="rounded-md p-4">
                                        <p class="text-sm font-medium">{{ label }}</p>
                                        <p v-if="key === 'created_at'" class="mt-1 text-lg">
                                            {{ formatDate(manager[key]) }}
                                        </p>
                                        <p v-else class="mt-1 text-lg capitalize">
                                            {{ manager[key] || 'N/A' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end space-x-2">
                                    <Button variant="destructive" @click="confirmDelete">Delete Manager</Button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script setup lang="ts">
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import dayjs from 'dayjs';

const props = defineProps({
    manager: Object,
    avatar_url: String,
});

const breadcrumbs = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Manage Managers', href: '/managers' },
    { title: 'Manager Details', href: `/managers/${props.manager.id}` },
];

const infoFields = {
    email: 'Email',
    national_id: 'National ID',
    mobile: 'Mobile',
    country: 'Country',
    gender: 'Gender',
    created_at: 'Created At',
};

function formatDate(date) {
    return dayjs(date).format('YYYY-MM-DD HH:mm:ss');
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete this manager?')) {
        router.delete(route('managers.destroy', props.manager.id));
    }
}
</script>
