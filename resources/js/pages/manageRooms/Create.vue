<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel, FormMessage } from '@/components/ui/form'; // استورد components بتوع الفورم عشان التنسيق الأفضل
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Link, useForm } from '@inertiajs/vue3'; // استورد Link عشان زر الرجوع

const props = defineProps<{
    floors: { id: number; name: string }[];
    errors: Object,

}>();

const form = useForm({
    capacity: '',
    price: '',
    floor_id: '',
});

const submit = () => {
    form.post(route('rooms.store'));
};
</script>

<template>
    <div class="container mx-auto my-8 rounded-md p-6 shadow-md">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Create New Room</h2>
            <Link :href="route('rooms.index')" class="rounded-md px-4 py-2 text-sm font-medium">
                <Button>Back to List</Button>
            </Link>
        </div>
        <Form>
            <form @submit.prevent="submit" class="space-y-6">
                <FormField name="capacity">
                    <FormItem>
                        <FormLabel>Capacity</FormLabel>
                        <FormControl>
                            <Input type="number" v-model="form.capacity" class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.capacity" class="text-sm text-red-500 mt-1">{{ props.errors.capacity }}</p>
                    </FormItem>
                </FormField>

                <FormField name="price">
                    <FormItem>
                        <FormLabel>Price (in dollars)</FormLabel>
                        <FormControl>
                            <Input type="number" v-model="form.price"   step="0.1"  class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.price" class="text-sm text-red-500 mt-1">{{ props.errors.price }}</p>
                    </FormItem>
                </FormField>

                <FormField name="floor_id">
                    <FormItem>
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
                    <Button type="submit" :disabled="form.processing" class="w-full rounded-md py-2 font-semibold">Create Room</Button>
                </div>
            </form>
        </Form>
    </div>
</template>
