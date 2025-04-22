<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';

defineProps<{ floors: { id: number; name: string }[] }>();

const form = useForm({
    number: '',
    capacity: '',
    price: '',
    floor_id: '',
});

const submit = () => {
    form.post(route('rooms.store'));
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6 max-w-md mx-auto">
        <!-- Room Number -->
        <div>
            <label for="number" class="block mb-1 text-sm font-medium">Room Number</label>
            <Input id="number" type="text" v-model="form.number" />
            <p v-if="form.errors.number" class="text-red-500 text-sm mt-1">{{ form.errors.number }}</p>
        </div>

        <!-- Capacity -->
        <div>
            <label for="capacity" class="block mb-1 text-sm font-medium">Capacity</label>
            <Input id="capacity" type="number" v-model="form.capacity" />
            <p v-if="form.errors.capacity" class="text-red-500 text-sm mt-1">{{ form.errors.capacity }}</p>
        </div>

        <!-- Price -->
        <div>
            <label for="price" class="block mb-1 text-sm font-medium">Price (in dollars)</label>
            <Input id="price" type="number" v-model="form.price" />
            <p v-if="form.errors.price" class="text-red-500 text-sm mt-1">{{ form.errors.price }}</p>
        </div>

        <!-- Floor -->
        <div>
            <label class="block mb-1 text-sm font-medium">Floor</label>
            <Select v-model="form.floor_id">
                <SelectTrigger>
                    <SelectValue placeholder="Select floor" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="floor in floors"
                        :key="floor.id"
                        :value="floor.id.toString()"
                    >
                        {{ floor.name }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="form.errors.floor_id" class="text-red-500 text-sm mt-1">{{ form.errors.floor_id }}</p>
        </div>

        <!-- Submit Button -->
        <Button type="submit" :disabled="form.processing">Create Room</Button>
    </form>
</template>
