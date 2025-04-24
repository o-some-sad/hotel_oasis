<template>
    <div>
        <Head title="Create Client" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="overflow-hidden rounded-xl">
                    <div class="p-6">
                        <div class="mb-6 flex justify-between">
                            <h3 class="text-lg font-medium">Client Details</h3>
                            <Link :href="route('clients.index')">
                                <Button class="rounded-md px-4 py-2 text-sm font-medium">Back to List</Button>
                            </Link>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <!-- Name -->
                                <div>
                                    <Label for="name">Name</Label>
                                    <Input id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Email -->
                                <div>
                                    <Label for="email">Email</Label>
                                    <Input id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.email" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div>
                                    <Label for="password">Password</Label>
                                    <Input id="password" v-model="form.password" type="password" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.password" class="mt-2" />
                                </div>

                                <!-- National ID -->
                                <div>
                                    <Label for="national_id">National ID</Label>
                                    <Input id="national_id" v-model="form.national_id" type="text" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.national_id" class="mt-2" />
                                </div>

                                <!-- Mobile -->
                                <div>
                                    <Label for="mobile">Mobile</Label>
                                    <Input id="mobile" v-model="form.mobile" type="text" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.mobile" class="mt-2" />
                                </div>

                                <!-- Country -->
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

                                <!-- Gender -->
                                <div>
                                    <Label for="gender" class="block text-sm font-medium text-gray-700">Gender</Label>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button
                                                variant="outline"
                                                class="mt-1 w-full justify-between rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            >
                                                {{ form.gender || 'Select Gender' }}
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent class="w-full">
                                            <DropdownMenuCheckboxItem
                                                class="w-full"
                                                v-for="option in ['male', 'female']"
                                                :key="option"
                                                :checked="form.gender === option"
                                                @click="form.gender = option"
                                            >
                                                {{ option }}
                                            </DropdownMenuCheckboxItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                    <InputError :message="form.errors.gender" class="mt-2" />
                                </div>

                                <!-- Avatar -->
                                <div>
                                    <Label for="avatar_image">Avatar Image</Label>
                                    <Input
                                        id="avatar_image"
                                        type="file"
                                        class="mt-1 block w-full"
                                        @input="form.avatar_image = $event.target.files[0]"
                                    />
                                    <InputError :message="form.errors.avatar_image" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <Button type="submit" class="ml-4" :disabled="form.processing"> Create Client </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script lang="ts" setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Manage Clients',
        href: '/clients',
    },
    {
        title: 'Create Client',
        href: '/clients/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    national_id: '',
    mobile: '',
    country: '',
    gender: '',
    avatar_image: null,
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
    form.post(route('clients.store'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => form.reset(),
        onError: (errors) => {
            alert('Failed to create client. Please check the form and try again.');
        },
    });
};
</script>
