<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-oasis-cream to-oasis-softBlue p-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8 animate-fade-in">
            <h2 class="text-2xl font-semibold text-oasis-lightGreen text-center mb-6">Welcome Back</h2>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="email" class="block text-oasis-beige text-sm font-medium mb-2">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        class="w-full px-4 py-2 border border-oasis-lightGreen rounded-md focus:outline-none focus:ring-2 focus:ring-oasis-softBlue transition-all duration-300 hover:border-oasis-lightGreen"
                        placeholder="Enter your email"
                    >
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-oasis-beige text-sm font-medium mb-2">Password</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        class="w-full px-4 py-2 border border-oasis-lightGreen rounded-md focus:outline-none focus:ring-2 focus:ring-oasis-softBlue transition-all duration-300 hover:border-oasis-lightGreen"
                        placeholder="Enter your password"
                    >
                    <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
                </div>
                <Button
                    type="submit"
                    size="lg"
                    class="w-full"
                >
                    Log In
            </Button>
            </form>
            <div class="mt-4 text-center">
                <a href="/forgot-password" class="text-oasis-lightGreen hover:underline">Forgot your password?</a>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import Button from '@/components/ui/button/Button.vue';

interface FormData {
    email: string;
    password: string;
}

export default defineComponent({
    components: { Head, Button },
    setup() {
        const form = useForm({
            email: '',
            password: '',
        });

        const submit = () => {
            form.post(route('login'), {
                onFinish: () => form.reset('password'),
            });
        };

        const { props } = usePage();
        const canResetPassword = route().has('password.request');

        return { form, submit, canResetPassword };
    },
});
</script>