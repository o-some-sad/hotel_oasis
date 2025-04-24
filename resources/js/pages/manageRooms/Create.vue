<script setup lang="ts">
import { useForm, Link } from '@inertiajs/vue3'; // استورد Link عشان زر الرجوع
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage
} from '@/components/ui/form'; // استورد components بتوع الفورم عشان التنسيق الأفضل

const props = defineProps<{
    floors: { id: number; name: string }[]
}>();

const form = useForm({
    capacity: '',
    price: '',
    floor_id: '',
});

const submit = () => {
    console.log(form);
    form.post(route('rooms.store'));
};
</script>

<template>
    <div class="container mx-auto my-8 p-6 bg-white dark:bg-gray-900 rounded-md shadow-md">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Create New Room</h2>
            <Link :href="route('rooms.index')" class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Back to List
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
                        <FormMessage>{{ form.errors.capacity }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="price">
                    <FormItem>
                        <FormLabel>Price (in dollars)</FormLabel>
                        <FormControl>
                            <Input type="number" v-model="form.price" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.price }}</FormMessage>
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
                    <Button type="submit" :disabled="form.processing" class="w-full py-2 rounded-md font-semibold">Create Room</Button>
                </div>
            </form>
        </Form>
    </div>
</template>
