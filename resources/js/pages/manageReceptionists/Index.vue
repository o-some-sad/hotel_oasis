<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import DataTable from '@/components/DataTable.vue'

const receptionists = computed(() => usePage().props.receptionists)
const message = computed(() => usePage().props.message)

// استخراج الـ headers من أول عنصر في الداتا
const headers = computed(() => {
    if (receptionists.value.length > 0) {
        return Object.keys(receptionists.value[0])
    }
    return []
})
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Receptionists</h1>

        <div v-if="message" class="text-red-500">{{ message }}</div>

        <div v-else>
            <DataTable :headers="headers" :rows="receptionists" />
        </div>
    </div>
</template>
