<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="mb-1 font-medium text-4xl">Error</h1>
            <div class="flex gap-4 flex-col lg:flex-row">
                <div class="flex-1/2 flex flex-col gap-4 w-full bg-destructive/10 text-destructive border-destructive mb-4 rounded-md border p-3 text-2xl">
                    <p>{{ page.props.flashes || 'An error occurred, please contact admin' }}</p>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

    const page = usePage()
</script>