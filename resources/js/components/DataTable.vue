<script setup lang="ts">
import { defineProps } from 'vue'

interface DataTableProps {
    headers: string[];
    rows: Record<string, any>[];
}

defineProps<DataTableProps>()
</script>

<template>
    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full table-auto border-collapse">
            <thead class="bg-blue-600 text-white">
            <tr>
                <th v-for="header in headers" :key="header" class="border px-6 py-3 text-left text-sm font-medium capitalize">
                    {{ header.replace('_', ' ') }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in rows" :key="index" class="hover:bg-gray-100">
                <td v-for="header in headers" :key="header" class="border px-6 py-4 text-sm text-gray-700">
                    {{ row[header] }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
