<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link, router, useForm } from '@inertiajs/vue3'; // استيراد Link

const props = defineProps<{
    room: {
        id: number;
        number: string;
        capacity: number;
        price: number;
        floor_id: number;
    };
    floors: { id: number; name: string }[];
    errors: Object,

}>();

const form = useForm({
    number: props.room.number,
    capacity: props.room.capacity,
    price: (props.room.price / 100).toFixed(2),
    floor_id: props.room.floor_id,
});

const submit = () => {
    console.log(form.data());
    router.post(route('rooms.update', props.room.id), {
        _method: 'put',
        ...form.data(),
    });
};
</script>

<template>
    <div class="container mx-auto my-8 rounded-md p-6 shadow-md">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Edit Room</h2>
            <Link :href="route('rooms.index')" class="rounded-md px-4 py-2 text-sm font-medium">
                <Button>Back to list</Button>
            </Link>
        </div>
        <Form>
            <form @submit.prevent="submit" class="space-y-6">
                <FormField name="number" :model-value="form.number">
                    <FormItem class="space-y-2">
                        <FormLabel>Room Number</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.number" disabled class="rounded-md" />
                        </FormControl>
                        <p>{{ props.errors.number }}</p>
                    </FormItem>
                </FormField>

                <FormField name="capacity" :model-value="form.capacity">
                    <FormItem class="space-y-2">
                        <FormLabel>Capacity</FormLabel>
                        <FormControl>
                            <Input type="number" v-model="form.capacity" class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.capacity" class="text-sm text-red-500 mt-1">{{ props.errors.capacity }}</p>
                    </FormItem>
                </FormField>

                <FormField name="price" :model-value="form.price">
                    <FormItem class="space-y-2">
                        <FormLabel>Price (in dollars)</FormLabel>
                        <FormControl>
                            <Input type="number" step="0.1" v-model="form.price" class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.price" class="text-sm text-red-500 mt-1">{{ props.errors.price }}</p>
                    </FormItem>
                </FormField>

                <FormField name="floor_id" :model-value="form.floor_id">
                    <FormItem class="space-y-2">
                        <FormLabel>Floor</FormLabel>
                        <FormControl>
                            <Select v-model="form.floor_id" class="rounded-md">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select floor" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="floor in props.floors" :key="floor.id" :value="floor.id">
                                        {{ floor.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <p v-if="props.errors.floor_id" class="text-sm text-red-500 mt-1">{{ props.errors.floor_id }}</p>
                    </FormItem>
                </FormField>

                <div>
                    <Button type="submit" :disabled="form.processing" class="w-full rounded-md py-2 font-semibold">Update Room</Button>
                </div>
            </form>
        </Form>
    </div>
</template>
