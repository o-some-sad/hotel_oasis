<script setup lang="ts">
import {
    Form,
    FormField,
    FormItem,
    FormLabel,
    FormControl,
    FormMessage
} from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { router, useForm, Link } from '@inertiajs/vue3'; // استيراد Link

const props = defineProps<{
    room: {
        id: number;
        number: string;
        capacity: number;
        price: number;
        floor_id: number;
    };
    floors: { id: number; name: string }[];
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
        ...form.data()
    });
};
</script>

<template>
    <div class="container mx-auto my-8 p-6 bg-white dark:bg-gray-900 rounded-md shadow-md">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Room</h2>
            <Link :href="route('rooms.index')" class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Back to List
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
                        <FormMessage>{{ form.errors.number }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="capacity" :model-value="form.capacity">
                    <FormItem class="space-y-2">
                        <FormLabel>Capacity</FormLabel>
                        <FormControl>
                            <Input type="number" v-model="form.capacity" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.capacity }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="price" :model-value="form.price">
                    <FormItem class="space-y-2">
                        <FormLabel>Price (in dollars)</FormLabel>
                        <FormControl>
                            <Input type="number" step="0.1" v-model="form.price" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.price }}</FormMessage>
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
                                    <SelectItem
                                        v-for="floor in props.floors"
                                        :key="floor.id"
                                        :value="floor.id"
                                    >
                                        {{ floor.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage>{{ form.errors.floor_id }}</FormMessage>
                    </FormItem>
                </FormField>

                <div>
                    <Button type="submit" :disabled="form.processing" class="w-full py-2 rounded-md font-semibold">Update Room</Button>
                </div>
            </form>
        </Form>
    </div>
</template>
