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
import {router, useForm} from '@inertiajs/vue3';

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
    const data = {
        number: Number(form.number),
        capacity: Number(form.capacity),
        price: Math.round(parseFloat(form.price) * 100),
        floor_id: Number(form.floor_id),
    };

    console.log(data);

    router.patch(route('rooms.update', props.room.id), {
        _method: 'patch',
        data,
        preserveScroll: true,
        onSuccess: () => {
            window.location.href = '/rooms';
        }
    });
};



</script>

<template>
    <Form>
        <form @submit.prevent="submit" class="space-y-4">
            <FormField name="number" :model-value="form.number">
                <FormItem>
                    <FormLabel>Room Number</FormLabel>
                    <FormControl>
                        <Input type="text" v-model="form.number"  disabled/>
                    </FormControl>
                    <FormMessage>{{ form.errors.number }}</FormMessage>
                </FormItem>
            </FormField>

            <FormField name="capacity" :model-value="form.capacity">
                <FormItem>
                    <FormLabel>Capacity</FormLabel>
                    <FormControl>
                        <Input type="number" v-model="form.capacity" />
                    </FormControl>
                    <FormMessage>{{ form.errors.capacity }}</FormMessage>
                </FormItem>
            </FormField>

            <FormField name="price" :model-value="form.price">
                <FormItem>
                    <FormLabel>Price (in dollars)</FormLabel>
                    <FormControl>
                        <Input type="number" step="0.01" v-model="form.price" />
                    </FormControl>
                    <FormMessage>{{ form.errors.price }}</FormMessage>
                </FormItem>
            </FormField>

            <FormField name="floor_id" :model-value="form.floor_id">
                <FormItem>
                    <FormLabel>Floor</FormLabel>
                    <FormControl>
                        <Select v-model="form.floor_id">
                            <SelectTrigger>
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

            <Button type="submit" :disabled="form.processing">Update Room</Button>
        </form>
    </Form>
</template>
