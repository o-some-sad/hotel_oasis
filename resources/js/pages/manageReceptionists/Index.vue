<script setup lang="ts">
import DataTable from '@/components/DataTable.vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const receptionists = computed(() => usePage().props.receptionists);
const message = computed(() => usePage().props.message);

const headers = computed(() => {
    if (receptionists.value.length > 0) {
        const baseKeys = Object.keys(receptionists.value[0]);
        const requiredKeys = ['status', 'update', 'delete'];

        requiredKeys.forEach((key) => {
            if (!baseKeys.includes(key)) {
                baseKeys.push(key);
            }
        });

        return baseKeys;
    }
    return [];
});

function handleBan(receptionist) {
    if (confirm('Are you sure you want to ban this receptionist?')) {
        router.post(route('receptionists.ban', receptionist.id));
    }
}

function handleUnban(receptionist) {
    if (confirm('Are you sure you want to unban this receptionist?')) {
        router.post(route('receptionists.unban', receptionist.id));
    }
}

function handleDelete(receptionist) {
    if (confirm('Are you sure you want to delete this receptionist?')) {
        router.delete(`/receptionists/${receptionist.id}`);
    }
}

function handleUpdate(receptionist) {
    router.get(route('receptionists.edit', receptionist.id));
}

function handleAddNewUser() {
    router.get('/receptionists/create');
}
</script>

<template>
    <div class="min-h-screen bg-gray-50 px-4 py-6">
        <h1 class="mb-6 text-center text-3xl font-bold text-blue-600">Receptionists</h1>

        <div v-if="message" class="mb-4 text-center text-red-500">
            {{ message }}
        </div>

        <div class="mb-6 text-center">
            <button @click="handleAddNewUser" class="rounded-lg bg-green-600 px-6 py-3 text-white transition duration-300 hover:bg-green-700">
                Add New User
            </button>
        </div>

        <div v-if="!message" class="overflow-x-auto rounded-lg shadow-md">
            <DataTable
                :headers="headers"
                :receptionists="receptionists"
                @status="(receptionist) => (receptionist.status === 'Ban' ? handleBan(receptionist) : handleUnban(receptionist))"
                @delete="handleDelete"
                @update="handleUpdate"
            />
        </div>
    </div>
</template>
