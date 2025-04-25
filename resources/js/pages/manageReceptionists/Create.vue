<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import {Label} from "@/components/ui/label";
import InputError from "@/components/InputError.vue";
import {onMounted, ref,computed} from "vue";
import axios from "axios";

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
