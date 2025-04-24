<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import FloorsTable from '@/pages/manageFloors/FloorsTable.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    pagination: Object,
    data: Array,
    links: Array,
    message: String,
});

onMounted(() => {
    if (props.message) {
        alert(props.message);
    }
});
</script>

<template>
    <Head title="Manage Floors" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">Manage Floors</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <FloorsTable
                            :data="props.pagination ? props.pagination.data : data"
                            :links="props.pagination ? props.pagination.links : links"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
