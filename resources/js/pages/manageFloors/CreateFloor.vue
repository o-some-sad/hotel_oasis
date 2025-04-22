<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const form = useForm({
    name: '',
});

const errors = computed(() => form.errors);

const submit = () => {
    form.post(route('floors.store'), {
        onSuccess: () => {
            // Redirect to floors listing
            window.location.href = '/floors';
        },
    });
};
</script>

<template>
    <Head title="Create Floor" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">Create New Floor</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-2">
                                <Label for="name">Floor Name</Label>
                                <Input id="name" v-model="form.name" placeholder="Enter floor name" :class="{ 'border-red-500': errors.name }" />
                                <p v-if="errors.name" class="mt-1 text-sm text-red-500">{{ errors.name }}</p>
                                <p class="text-sm">Floor name must be at least 3 characters long.</p>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <Button type="button" variant="outline" @click="() => window.history.back()"> Cancel </Button>
                                <Button type="submit" :disabled="form.processing"> Create Floor </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
