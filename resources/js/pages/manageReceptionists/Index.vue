<script setup lang="ts">
import { computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import DataTable from '@/components/DataTable.vue'

const receptionists = computed(() => usePage().props.receptionists)
const message = computed(() => usePage().props.message)

const headers = computed(() => {
    if (receptionists.value.length > 0) {
        const baseKeys = Object.keys(receptionists.value[0]);

        const filteredKeys = baseKeys.filter(key => !['banned_at','id'].includes(key));

        if (!filteredKeys.includes('action')) {
            filteredKeys.push('action');
        }

        return filteredKeys;
    }
    return [];
})

function handleBan(receptionist) {
    router.patch(route('receptionists.update', { receptionist: receptionist.id }), {
        banned_at: new Date().toISOString(),
        _method: 'PATCH',
    });
}

function handleUnban(receptionist) {
    console.log("dddddddd");
    router.patch(route('receptionists.update', { receptionist: receptionist.id }), {
        banned_at: null,
        _method: 'PATCH',
    });
}

function handleDelete(receptionist) {
    if (confirm('Are you sure you want to delete this receptionist?')) {
        router.delete(route('receptionists.destroy', { receptionist: receptionist.id }));
    }
}

function handleUpdate(receptionist) {
    console.log(receptionist.id);
    router.get(route('receptionists.edit', { receptionist: receptionist.id }));
}



function handleAddNewUser() {
    router.get('/receptionists/create');
}
</script>

<template>
    <div class="bg-gray-50 min-h-screen py-6 px-4">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Receptionists</h1>

        <div v-if="message" class="text-red-500 text-center mb-4">
            {{ message }}
        </div>

        <div class="text-center mb-6">
            <button
                @click="handleAddNewUser"
                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300"
            >
                Add New User
            </button>
        </div>

        <div v-if="!message" class="overflow-x-auto shadow-md rounded-lg">
            <DataTable
                :headers="headers"
                :receptionists="receptionists"
                @ban="handleBan"
                @unban="handleUnban"
                @delete="handleDelete"
                @update="handleUpdate"
            />
        </div>
    </div>
</template>
