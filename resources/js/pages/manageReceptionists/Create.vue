<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Form,
    FormField,
    FormItem,
    FormLabel,
    FormControl,
} from '@/components/ui/form';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    errors: Object,
});

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

onMounted(async () => {
    try {
        const response = await axios.get('/api/countries');
        countries.value = response.data;
    } catch (error) {
        console.error('Error fetching countries:', error);
    }

    document.addEventListener('click', (e) => {
        if (!e.target.closest('#country_search')) showCountryDropdown.value = false;
    });
});

const filteredCountries = computed(() => {
    const term = countrySearch.value.toLowerCase();
    return !term ? countries.value : countries.value.filter((c) => c.name.toLowerCase().includes(term));
});

const selectCountry = (country) => {
    selectedCountry.value = country;
    form.country = country.iso_alpha_3;
    countrySearch.value = country.name;
    showCountryDropdown.value = false;
};

const submit = () => {
    form.post(route('receptionists.store'), {
        onSuccess: () => window.location.href = '/receptionists',
    });
};
</script>

<template>
    <div class="container mx-auto my-8 rounded-md p-6 shadow-md max-w-2xl">
        <h2 class="text-xl font-semibold mb-6">Create Receptionist</h2>

        <Form>
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name -->
                <FormField name="name">
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.name" />
                        </FormControl>
                        <p v-if="props.errors.name" class="text-sm text-red-500 mt-1">{{ props.errors.name }}</p>
                    </FormItem>
                </FormField>

                <!-- Email -->
                <FormField name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input type="email" v-model="form.email" autocomplete="off" />
                        </FormControl>
                        <p v-if="props.errors.email" class="text-sm text-red-500 mt-1">{{ props.errors.email }}</p>
                    </FormItem>
                </FormField>

                <!-- Password -->
                <FormField name="password">
                    <FormItem>
                        <FormLabel>Password</FormLabel>
                        <FormControl>
                            <Input type="password" v-model="form.password" autocomplete="new-password" />
                        </FormControl>
                        <p v-if="props.errors.password" class="text-sm text-red-500 mt-1">{{ props.errors.password }}</p>
                    </FormItem>
                </FormField>

                <!-- National ID -->
                <FormField name="national_id">
                    <FormItem>
                        <FormLabel>National ID</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.national_id" />
                        </FormControl>
                        <p v-if="props.errors.national_id" class="text-sm text-red-500 mt-1">{{ props.errors.national_id }}</p>
                    </FormItem>
                </FormField>

                <!-- Gender -->
                <FormField name="gender">
                    <FormItem>
                        <FormLabel>Gender</FormLabel>
                        <FormControl>
                            <Select v-model="form.gender">
                                <SelectTrigger>
                                    <SelectValue placeholder="Select Gender" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="male">Male</SelectItem>
                                    <SelectItem value="female">Female</SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <p v-if="props.errors.gender" class="text-sm text-red-500 mt-1">{{ props.errors.gender }}</p>
                    </FormItem>
                </FormField>

                <!-- Mobile -->
                <FormField name="mobile">
                    <FormItem>
                        <FormLabel>Mobile</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.mobile" />
                        </FormControl>
                        <p v-if="props.errors.mobile" class="text-sm text-red-500 mt-1">{{ props.errors.mobile }}</p>
                    </FormItem>
                </FormField>

                <!-- Country -->
                <FormField name="country">
                    <FormItem>
                        <FormLabel>Country</FormLabel>
                        <FormControl>
                            <div class="relative">
                                <Input
                                    id="country_search"
                                    v-model="countrySearch"
                                    placeholder="Search for a country..."
                                    @input="showCountryDropdown = true"
                                    @focus="showCountryDropdown = true"
                                />
                                <div v-if="showCountryDropdown && filteredCountries.length" class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg">
                                    <div class="py-1">
                                        <button
                                            v-for="country in filteredCountries"
                                            :key="country.id"
                                            @click="selectCountry(country)"
                                            class="w-full px-4 py-2 text-left text-sm hover:bg-gray-100"
                                            type="button"
                                        >
                                            {{ country.name }}
                                        </button>
                                    </div>
                                </div>
                                <div v-if="selectedCountry" class="mt-2 text-sm text-gray-600">
                                    Selected: <span class="font-medium">{{ selectedCountry.name }}</span>
                                </div>
                            </div>
                        </FormControl>
                        <p v-if="props.errors.country" class="text-sm text-red-500 mt-1">{{ props.errors.country }}</p>
                    </FormItem>
                </FormField>

                <!-- Profile Image -->
                <FormField name="avatar_img">
                    <FormItem>
                        <FormLabel>Profile Image</FormLabel>
                        <FormControl>
                            <input
                                type="file"
                                @change="(e) => (form.avatar_img = e.target.files[0])"
                                class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
                            />
                        </FormControl>
                        <p v-if="props.errors.avatar_img" class="text-sm text-red-500 mt-1">{{ props.errors.avatar_img }}</p>
                    </FormItem>
                </FormField>

                <!-- Submit -->
                <Button type="submit" :disabled="form.processing" class="w-full">Create Receptionist</Button>
            </form>
        </Form>
    </div>
</template>
