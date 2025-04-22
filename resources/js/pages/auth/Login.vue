<template>
    <div class="bg-background flex min-h-screen items-center justify-center p-4">
        <div class="animate-fade-in bg-card w-full max-w-md rounded-lg p-8 shadow-lg">
            <h2 class="text-card-foreground mb-6 text-center text-2xl font-semibold">Welcome Back</h2>

            <!-- Display server errors -->
            <div v-if="authError" class="bg-destructive/10 text-destructive border-destructive mb-4 rounded-md border p-3 text-sm">
                {{ authError }}
            </div>

            <form @submit.prevent="submit">
                <!-- Email Field -->
                <FormField name="email">
                    <div class="mb-4">
                        <FormLabel class="mb-2">Email</FormLabel>
                        <FormControl>
                            <Input
                                v-model="form.email"
                                type="email"
                                placeholder="Enter your email"
                                class="w-full py-2"
                                :class="{ 'border-destructive': form.errors.email }"
                                @input="validateInput"
                            />
                        </FormControl>
                        <!-- Only show regular validation errors here -->
                        <p v-if="form.errors.email && !isServerError(form.errors.email)" class="text-destructive mt-1 text-xs">
                            {{ form.errors.email }}
                        </p>
                    </div>
                </FormField>

                <!-- Password Field -->
                <FormField name="password">
                    <div class="mb-6">
                        <FormLabel class="mb-2">Password</FormLabel>
                        <FormControl>
                            <div class="relative">
                                <Input
                                    v-model="form.password"
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="Enter your password"
                                    class="w-full py-2 pr-10"
                                    :class="{ 'border-destructive': form.errors.password }"
                                    @input="validateInput"
                                />
                                <button
                                    type="button"
                                    @click="togglePasswordVisibility"
                                    class="text-foreground/70 hover:text-foreground absolute top-1/2 right-3 -translate-y-1/2 focus:outline-none"
                                >
                                    <svg
                                        v-if="showPassword"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path
                                            fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd"
                                        />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </FormControl>
                        <p v-if="form.errors.password" class="text-destructive mt-1 text-xs">{{ form.errors.password }}</p>
                    </div>
                </FormField>

                <button
                    type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary/90 w-full rounded-md px-4 py-2.5 font-medium transition-colors"
                    :disabled="form.processing || !canSubmit"
                    :class="{ 'cursor-not-allowed opacity-70': form.processing || !canSubmit }"
                >
                    {{ form.processing ? 'Logging in...' : 'Log In' }}
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="/forgot-password" class="text-primary text-sm hover:underline">Forgot your password?</a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { FormControl, FormField, FormLabel } from '../../components/ui/form';
import { Input } from '../../components/ui/input';

interface FormData {
    email: string;
    password: string;
    server?: string;
}

const form = useForm<FormData>({
    email: '',
    password: '',
    server: '',
});

const showPassword = ref(false);
const formTouched = ref(false);
const authError = ref('');

const canSubmit = computed(() => {
    return form.email && form.password;
});

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const validateInput = () => {
    formTouched.value = true;

    // Clear any previous errors when user starts typing
    form.clearErrors();
    authError.value = '';
};

// Simple form validation function
const validateForm = () => {
    let isValid = true;

    // Clear all previous errors
    form.clearErrors();

    if (!form.email) {
        form.setError('email', 'Email is required');
        isValid = false;
    } else if (!isValidEmail(form.email)) {
        form.setError('email', 'Please enter a valid email address');
        isValid = false;
    }

    if (!form.password) {
        form.setError('password', 'Password is required');
        isValid = false;
    } else if (form.password.length < 6) {
        form.setError('password', 'Password must be at least 6 characters');
        isValid = false;
    }

    return isValid;
};

const isValidEmail = (email: string) => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
};

const isServerError = (error: string) => {
    // Check if this is a server-generated error message
    return (
        error.includes('contact the administrator') || error.includes('throttle') || error === 'Invalid credentials' || error === trans('auth.failed')
    );
};

const submit = () => {
    if (!validateForm()) {
        return;
    }

    authError.value = ''; // Clear previous errors

    // Only submit to server if validation passes
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
        onError: (errors: Record<string, string>) => {
            // Check for email-related errors from the server
            if (errors.email) {
                // If it's a special message like the banned user message,
                // display it prominently at the top
                if (isServerError(errors.email)) {
                    authError.value = errors.email;
                }
            }

            // Handle other server errors
            if (errors.message) {
                authError.value = errors.message;
            }

            // If we have regular validation errors but no special auth error,
            // show a generic message
            if (!authError.value && Object.keys(errors).length > 0) {
                authError.value = 'Please check your credentials and try again.';
            }
        },
    });
};

// Watch for input changes to validate in real-time when user has already interacted with form
watch([() => form.email, () => form.password], () => {
    if (formTouched.value) {
        validateForm();
    }
});
</script>
