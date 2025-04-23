<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { User } from '@/types';

const props = defineProps<{
    receptionist: User;
}>();

const form = useForm({
    name: props.receptionist.name,
    email: props.receptionist.email,
    avatar_img: null,
    gender: props.receptionist.gender,
    mobile: props.receptionist.mobile,
    country: props.receptionist.country,
    national_id:props.receptionist.national_id
});

const submit = () => {
    console.log(form.data());
    router.post(route('receptionists.update', props.receptionist.id), {
        _method: 'put',
        ...form.data()
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6 max-w-md mx-auto">
        <!-- Name -->
        <div>
            <label for="name" class="block mb-1 text-sm font-medium">Name</label>
            <Input id="name" type="text" v-model="form.name" />
            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-1 text-sm font-medium">Email</label>
            <Input id="email" type="email" v-model="form.email" />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Gender -->
        <div>
            <label class="block mb-1 text-sm font-medium">Gender</label>
            <Select v-model="form.gender">
                <SelectTrigger>
                    <SelectValue placeholder="Select Gender" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="male">Male</SelectItem>
                    <SelectItem value="female">Female</SelectItem>
                </SelectContent>
            </Select>
            <p v-if="form.errors.gender" class="text-red-500 text-sm mt-1">{{ form.errors.gender }}</p>
        </div>

        <!-- Mobile -->
        <div>
            <label for="mobile" class="block mb-1 text-sm font-medium">Mobile</label>
            <Input id="mobile" type="text" v-model="form.mobile" />
            <p v-if="form.errors.mobile" class="text-red-500 text-sm mt-1">{{ form.errors.mobile }}</p>
        </div>

        <!-- Country -->
        <div>
            <label for="country" class="block mb-1 text-sm font-medium">Country</label>
            <Input id="country" type="text" v-model="form.country" />
            <p v-if="form.errors.country" class="text-red-500 text-sm mt-1">{{ form.errors.country }}</p>
        </div>

        <!-- Profile Image -->
        <div>
            <label for="avatar_img" class="block mb-1 text-sm font-medium">Profile Image</label>
            <input
                type="file"
                @change="(e) => (form.avatar_img = e.target.files[0])"
                class="input input-bordered w-full"
                name="avatar_img"
            />
            <p v-if="form.errors.avatar_img" class="text-red-500 text-sm mt-1">{{ form.errors.avatar_img }}</p>
        </div>

        <!-- Submit Button -->
        <Button type="submit" :disabled="form.processing">Update Receptionist</Button>
    </form>
</template>
