<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ChevronDown } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
    manager: Object,
});

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Manage Managers',
        href: '/managers',
    },
    {
        title: 'Edit Manager',
        href: `/managers/${props.manager.id}/edit`,
    },
];

const form = useForm({
    name: props.manager.name,
    email: props.manager.email,
    password: '',
    password_confirmation: '',
    national_id: props.manager.national_id,
    mobile: props.manager.mobile,
    country: props.manager.country,
    gender: props.manager.gender,
    avatar_img: null,
    _method: 'PUT',
});

const countrySearch = ref('');
const showCountryDropdown = ref(false);
const countries = ref([]);
const selectedCountry = ref(null);

// Fetch all countries when component mounts
onMounted(async () => {
    try {
        const response = await axios.get('https://restcountries.com/v3.1/all?fields=name,cca2');
        countries.value = response.data
            .map((country) => ({
                name: country.name.common,
                code: country.cca2,
            }))
            .sort((a, b) => a.name.localeCompare(b.name));

        // Find current country from list
        const currentCountry = countries.value.find((country) => country.code === props.manager.country);
        if (currentCountry) {
            selectedCountry.value = currentCountry;
        }
    } catch (error) {
        console.error('Error fetching countries:', error);
    }
});

const filteredCountries = computed(() => {
    return countries.value.filter((country) => country.name.toLowerCase().includes(countrySearch.value.toLowerCase()));
});

function selectCountry(country) {
    selectedCountry.value = country;
    form.country = country.code;
    showCountryDropdown.value = false;
}

function handleFileChange(event) {
    form.avatar_img = event.target.files[0];
}

function submit() {
    form.post(route('managers.update', props.manager.id), {
        preserveScroll: true,
    });
}
</script>

<template>
    <div>
        <Head title="Edit Manager" />
        <AppLayout :breadcrumbs="breadcrumbs">
            <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Edit Manager</h1>
                    <Link :href="route('managers.index')">
                        <Button variant="outline">Back to Managers</Button>
                    </Link>
                </div>
                <div class="rounded-md border bg-white p-6 shadow-sm dark:bg-neutral-900">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <Label for="name">Name</Label>
                            <Input id="name" v-model="form.name" type="text" class="mt-1 block w-full" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Email -->
                        <div>
                            <Label for="email">Email</Label>
                            <Input id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Password (Optional for edit) -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <Label for="password">Password (Leave blank to keep current)</Label>
                                <Input id="password" v-model="form.password" type="password" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <div>
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full" />
                            </div>
                        </div>

                        <!-- National ID -->
                        <div>
                            <Label for="national_id">National ID</Label>
                            <Input id="national_id" v-model="form.national_id" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.national_id" />
                        </div>

                        <!-- Mobile -->
                        <div>
                            <Label for="mobile">Mobile</Label>
                            <Input id="mobile" v-model="form.mobile" type="text" class="mt-1 block w-full" required />
                            <InputError class="mt-2" :message="form.errors.mobile" />
                        </div>

                        <!-- Country -->
                        <div>
                            <Label for="country">Country</Label>
                            <div class="relative">
                                <Button
                                    type="button"
                                    variant="outline"
                                    class="mt-1 flex w-full justify-between"
                                    @click="showCountryDropdown = !showCountryDropdown"
                                >
                                    <span>{{ selectedCountry ? selectedCountry.name : 'Select country' }}</span>
                                    <ChevronDown class="h-4 w-4" />
                                </Button>
                                <div
                                    v-if="showCountryDropdown"
                                    class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white py-1 shadow-lg dark:bg-neutral-900"
                                >
                                    <Input
                                        v-model="countrySearch"
                                        placeholder="Search countries..."
                                        class="mx-2 my-2 w-[calc(100%-1rem)]"
                                        @click.stop
                                    />
                                    <div class="mt-1 max-h-40 overflow-y-auto">
                                        <div
                                            v-for="country in filteredCountries"
                                            :key="country.code"
                                            class="cursor-pointer px-4 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800"
                                            @click="selectCountry(country)"
                                        >
                                            {{ country.name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.country" />
                        </div>

                        <!-- Gender -->
                        <div>
                            <Label for="gender">Gender</Label>
                            <div class="mt-1 flex gap-4">
                                <div class="flex items-center">
                                    <Input id="male" v-model="form.gender" type="radio" name="gender" value="male" class="h-4 w-4" />
                                    <label for="male" class="ml-2">Male</label>
                                </div>
                                <div class="flex items-center">
                                    <Input id="female" v-model="form.gender" type="radio" name="gender" value="female" class="h-4 w-4" />
                                    <label for="female" class="ml-2">Female</label>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.gender" />
                        </div>

                        <!-- Avatar Image -->
                        <div>
                            <Label for="avatar_img">Avatar Image (Leave blank to keep current)</Label>
                            <Input id="avatar_img" type="file" @change="handleFileChange" class="mt-1 block w-full" accept="image/*" />
                            <InputError class="mt-2" :message="form.errors.avatar_img" />

                            <div v-if="manager.avatar_img" class="mt-2">
                                <p class="text-sm text-gray-500">Current avatar:</p>
                                <img :src="`/storage/${manager.avatar_img}`" alt="Current avatar" class="mt-1 h-20 w-20 rounded-full object-cover" />
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <Button type="submit" :disabled="form.processing" :class="{ 'opacity-50': form.processing }"> Update Manager </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    </div>
</template>
