<!-- DataTable.vue -->
<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

interface DataTableProps {
    headers: string[];
    receptionists: Record<string, any>[];
}

const emit = defineEmits(['ban', 'unban', 'delete', 'update'])

defineProps<DataTableProps>()

function handleAction(action: 'ban' | 'unban' | 'delete' | 'update', receptionist: Record<string, any>) {
    emit(action, receptionist)
}
</script>

<template>
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-blue-600 text-white">
            <tr>
                <th
                    v-for="header in headers"
                    :key="header"
                    class="border px-6 py-3 text-left text-sm font-medium capitalize"
                >
                    {{ header.replace('_', ' ') }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(receptionist, index) in receptionists"
                :key="index"
                class="hover:bg-gray-100"
            >
                <td
                    v-for="header in headers"
                    :key="header"
                    class="border px-6 py-4 text-sm text-gray-700"
                >
                    <template v-if="header === 'action'">
                        <div class="flex flex-wrap gap-2">
                            <button
                                @click="handleAction(receptionist.banned_at ? 'unban' : 'ban', receptionist)"
                                :disabled="!receptionist.action"
                                :class="[
                                        'px-3 py-1 rounded text-xs transition',
                                        receptionist.action
                                            ? 'bg-yellow-500 text-white hover:bg-yellow-600'
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    ]"
                            >
                                {{ receptionist.banned_at ? 'Unban' : 'Ban' }}
                            </button>
                            <button
                                @click="handleAction('update', receptionist)"
                                :disabled="!receptionist.action"
                                :class="[
                                        'px-3 py-1 rounded text-xs transition',
                                        receptionist.action
                                            ? 'bg-blue-500 text-white hover:bg-blue-600'
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    ]"
                            >
                                Update
                            </button>
                            <button
                                @click="handleAction('delete', receptionist)"
                                :disabled="!receptionist.action"
                                :class="[
                                        'px-3 py-1 rounded text-xs transition',
                                        receptionist.action
                                            ? 'bg-red-600 text-white hover:bg-red-700'
                                            : 'bg-gray-300 text-gray-500 cursor-not-allowed'
                                    ]"
                            >
                                Delete
                            </button>
                        </div>
                    </template>
                    <template v-else>
                        {{ receptionist[header] ?? '-' }}
                    </template>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
