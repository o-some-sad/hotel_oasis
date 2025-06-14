<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import {computed, onMounted, ref} from "vue";
import axios from "axios";

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


const countrySearch = ref('');
const showCountryDropdown = ref(false);
const countries = ref([]);
const selectedCountry = ref(null);

// Fetch all countries when component mounts
onMounted(async () => {
    try {
        const response = await axios.get('/api/countries');
        countries.value = response.data;
    } catch (error) {
        console.error('Error fetching countries:', error);
    }
});
// Filter countries based on search input
const filteredCountries = computed(() => {
    if (!countrySearch.value) return countries.value;

    const searchTerm = countrySearch.value.toLowerCase();
    return countries.value.filter((country) => country.name.toLowerCase().includes(searchTerm));
});

// Close dropdown when clicking outside
const closeDropdown = (e) => {
    if (!e.target.closest('#country_search')) {
        showCountryDropdown.value = false;
    }
};

// Add event listener to close dropdown when clicking outside
onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

// Select a country
const selectCountry = (country) => {
    selectedCountry.value = country;
    form.country = country.iso_alpha_3; // Store the ISO alpha-3 code as the value
    countrySearch.value = country.name; // Display the country name in the input
    showCountryDropdown.value = false;
};

// Filter countries on input
const filterCountries = () => {
    showCountryDropdown.value = true;
};

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

                <div>
                    <Label for="country">Country</Label>
                    <div class="relative">
                        <Input
                            id="country_search"
                            v-model="countrySearch"
                            type="text"
                            placeholder="Search for a country..."
                            @input="filterCountries"
                            @focus="showCountryDropdown = true"
                            class="mt-1 block w-full"
                            required
                        />
                        <div
                            v-if="showCountryDropdown && filteredCountries.length > 0"
                            class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg dark:bg-zinc-800"
                        >
                            <div class="py-1">
                                <button
                                    v-for="country in filteredCountries"
                                    :key="country.id"
                                    @click="selectCountry(country)"
                                    class="w-full px-4 py-2 text-left text-sm hover:bg-zinc-100 focus:bg-zinc-100 focus:outline-none dark:hover:bg-zinc-700 dark:focus:bg-zinc-700"
                                    type="button"
                                >
                                    {{ country.name }}
                                </button>
                            </div>
                        </div>
                        <div v-if="selectedCountry" class="mt-2 text-sm">
                            Selected: <span class="font-medium">{{ selectedCountry.name }}</span>
                        </div>
                    </div>
                    <InputError :message="form.errors.country" class="mt-2" />
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
