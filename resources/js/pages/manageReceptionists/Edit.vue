<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Form, FormControl, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { User } from '@/types';
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';
import axios from 'axios';

const props = defineProps<{
    receptionist: User;
    errors: Object,
}>();

const form = useForm({
    name: props.receptionist.name,
    email: props.receptionist.email,
    avatar_img: null,
    gender: props.receptionist.gender,
    mobile: props.receptionist.mobile,
    country: props.receptionist.country,
    national_id: props.receptionist.national_id,
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
    console.log(props.errors.name);
    router.post(route('receptionists.update', props.receptionist.id), {
        _method: 'put',
        ...form.data(),
    });
};
</script>

<template>
    <div class="container mx-auto my-8 rounded-md p-6 shadow-md">
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-semibold">Edit Receptionist</h2>
            <Link :href="route('receptionists.index')" class="rounded-md px-4 py-2 text-sm font-medium">
                <Button>Back to List</Button>
            </Link>
        </div>

        <Form>
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name -->
                <FormField name="name">
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.name" class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.name" class="text-sm text-red-500 mt-1">{{ props.errors.name }}</p>
                    </FormItem>
                </FormField>

                <!-- Email -->
                <FormField name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input type="email" v-model="form.email" class="rounded-md" />
                        </FormControl>
                        <p v-if="props.errors.email" class="text-sm text-red-500 mt-1">{{ props.errors.email }}</p>
                    </FormItem>
                </FormField>

                <!-- Gender -->
                <FormField name="gender">
                    <FormItem>
                        <FormLabel>Gender</FormLabel>
                        <FormControl>
                            <Select v-model="form.gender" class="rounded-md">
                                <SelectTrigger class="w-full">
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
                            <Input type="text" v-model="form.mobile" class="rounded-md" />
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
                                <div v-if="showCountryDropdown && filteredCountries.length"
                                     class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg">
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

                <!-- National ID -->
                <FormField name="national_id">
                    <FormItem>
                        <FormLabel>National ID</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.national_id" class="rounded-md" disabled />
                        </FormControl>
                        <p v-if="props.errors.national_id" class="text-sm text-red-500 mt-1">{{ props.errors.national_id }}</p>
                    </FormItem>
                </FormField>

                <!-- Avatar Image -->
                <FormField name="avatar_img">
                    <FormItem>
                        <FormLabel>Profile Image</FormLabel>
                        <FormControl>
                            <input
                                type="file"
                                @change="(e) => (form.avatar_img = e.target.files[0])"
                                class="input input-bordered w-full rounded-md"
                                name="avatar_img"
                            />
                        </FormControl>
                        <p v-if="props.errors.avatar_img" class="text-sm text-red-500 mt-1">{{ props.errors.avatar_img }}</p>
                    </FormItem>
                </FormField>

                <!-- Submit -->
                <div>
                    <Button type="submit" :disabled="form.processing" class="w-full rounded-md py-2 font-semibold">
                        Update Receptionist
                    </Button>
                </div>
            </form>
        </Form>
    </div>
</template>
