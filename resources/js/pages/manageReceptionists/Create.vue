<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';

const form = useForm({
    name: '',
    email: '',
    password: '',
    national_id: '',
    avatar_img: null,
    gender: '',
    mobile: '',
    country: '',
});

const submit = () => {
    form.post(route('receptionists.store'), {
        onSuccess: () => {
            window.location.href = '/receptionists';
        },
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
            <Input id="email" type="email" v-model="form.email"
                   autocomplete="off"
                   />
            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block mb-1 text-sm font-medium">Password</label>
            <Input id="password" type="password" v-model="form.password"
                   autocomplete="new-password"
            />
            <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
        </div>

        <!-- National ID -->
        <div>
            <label for="national_id" class="block mb-1 text-sm font-medium">National ID</label>
            <Input id="national_id" type="text" v-model="form.national_id" />
            <p v-if="form.errors.national_id" class="text-red-500 text-sm mt-1">{{ form.errors.national_id }}</p>
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
        <Button type="submit" :disabled="form.processing">Create Receptionist</Button>
    </form>
</template>
