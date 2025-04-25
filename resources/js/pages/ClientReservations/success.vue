<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    reservation_id: string;
    room_number: string;
}>();


</script>

<template>
    <AppLayout>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h1 class="mb-1 font-medium text-4xl">Thank you.</h1>
            <div class="flex gap-4 flex-col lg:flex-row">
                <div class="flex-1/2 flex flex-col gap-4 border rounded-2xl p-4 w-full">
                    <div>
                        <div class="text-xl font-bold">Reservation Confirmed</div>
                        <p>Reservation has been successfully confirmed.</p>
                    </div>
                    <div>
                        <div class="text-xl font-bold">Reservation ID</div>
                        <p>{{ props.reservation_id }}</p>
                    </div>
                    <div>
                        <div class="text-xl font-bold">Room</div>
                        <p>{{ props.room_number }}</p>
                    </div>
                </div>
            </div>
        </div>

    </AppLayout>
</template>