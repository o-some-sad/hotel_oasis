<script setup lang="ts">
import { defineProps, defineEmits } from 'vue'

interface DataTableProps {
    headers: string[];
    receptionists: Record<string, any>[];
}

const emit = defineEmits(['ban', 'unban', 'delete', 'update'])

defineProps<DataTableProps>()

function handleAction(action: string, receptionist: Record<string, any>) {
    emit(action.toLowerCase(), receptionist)
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
                    <template v-if="['status', 'update', 'delete'].includes(header)">
                        <button
                            v-if="receptionist[header]"
                            @click="handleAction(header, receptionist)"
                            class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 mx-1 text-xs"
                        >
                            {{ receptionist[header] }}
                        </button>
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
