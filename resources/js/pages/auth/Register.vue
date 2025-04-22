<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    national_id: '',
    mobile: '',
    country: '',
    gender: '',
    avatar_img: null,
});


const submit = () => {
    form.post(route('register'), {
        forceFormData: true,
        onFinish: () => form.reset('password', 'password_confirmation', 'avatar_img'),
    });
};

</script>

<template>
    <AuthBase title="Create an account" description="Enter your details below to create your account">
        <Head title="Register" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="Full name" />
                    <InputError :message="form.errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        placeholder="Password"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirm password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        placeholder="Confirm password"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

            <div class="grid gap-2">
                <Label for="national_id">National ID</Label>
                <Input id="national_id" type="text" required v-model="form.national_id" placeholder="Your national ID" />
                <InputError :message="form.errors.national_id" />
            </div>

            <div class="grid gap-2">
                <Label for="mobile">Mobile</Label>
                <Input id="mobile" type="text" required v-model="form.mobile" placeholder="Mobile number" />
                <InputError :message="form.errors.mobile" />
            </div>

            <div class="grid gap-2">
                <Label for="country">Country</Label>
                <Input id="country" type="text" required v-model="form.country" placeholder="Country" />
                <InputError :message="form.errors.country" />
            </div>

            <div class="grid gap-2">
                <Label for="gender">Gender</Label>
                <select id="gender" v-model="form.gender" required class="input">
                    <option value="">Select gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <InputError :message="form.errors.gender" />
            </div>

            <div class="grid gap-2">
                <Label for="avatar_img">Avatar Image</Label>
                <Input id="avatar_img" type="file" @change="e => form.avatar_img = e.target.files[0]" />
                <InputError :message="form.errors.avatar_img" />
            </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    Create account
                </Button>
                Already have an account?
                <div class="text-center text-sm text-muted-foreground">
                    <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">Log in</TextLink>
                </div>
            </div>
        </form>
    </AuthBase>
</template>
