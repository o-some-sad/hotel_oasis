<template>
  <div>
      <Head title="Edit Client" />
      <AppLayout :breadcrumbs="breadcrumbs">
          <div class="w-full p-6">
              <div class="mb-6 flex items-center justify-between">
                  <h2 class="text-2xl font-bold">Edit Client</h2>
                  <Link :href="route('clients.index')">
                      <Button variant="default"> Back to List </Button>
                  </Link>
              </div>

              <div class="overflow-hidden rounded-lg shadow-sm">
                  <div class="border-b p-6">
                      <form @submit.prevent="submit" class="space-y-6">
                          <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                              <!-- Name -->
                              <div>
                                  <Label for="name">Name</Label>
                                  <Input id="name" v-model="form.name" type="text" required />
                                  <InputError :message="form.errors.name" />
                              </div>

                              <!-- Email -->
                              <div>
                                  <Label for="email">Email</Label>
                                  <Input id="email" v-model="form.email" type="email" required />
                                  <InputError :message="form.errors.email" />
                              </div>

                              <!-- Password (optional for editing) -->
                              <div>
                                  <Label for="password">Password (leave blank to keep current)</Label>
                                  <Input id="password" v-model="form.password" type="password" />
                                  <InputError :message="form.errors.password" />
                              </div>

                              <!-- National ID -->
                              <div>
                                  <Label for="national_id">National ID</Label>
                                  <Input id="national_id" v-model="form.national_id" type="text" required />
                                  <InputError :message="form.errors.national_id" />
                              </div>

                              <!-- Mobile -->
                              <div>
                                  <Label for="mobile">Mobile</Label>
                                  <Input id="mobile" v-model="form.mobile" type="text" required />
                                  <InputError :message="form.errors.mobile" />
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
                                      />
                                      <div
                                          v-if="showCountryDropdown && filteredCountries.length > 0"
                                          class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border bg-white shadow-lg dark:bg-zinc-950"
                                      >
                                          <div class="py-1">
                                              <button
                                                  v-for="country in filteredCountries"
                                                  :key="country.id"
                                                  @click="selectCountry(country)"
                                                  class="w-full px-4 py-2 text-left text-sm hover:bg-zinc-100 focus:bg-zinc-100 focus:outline-none dark:hover:bg-zinc-950 dark:focus:bg-zinc-950"
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
                                  <InputError :message="form.errors.country" />
                              </div>

                              <!-- Gender -->
                              <div>
                                  <Label for="gender">Gender</Label>
                                  <DropdownMenu>
                                      <DropdownMenuTrigger asChild>
                                          <Button variant="outline" class="w-full justify-between">
                                              {{ form.gender || 'Select Gender' }}
                                              <ChevronDown class="ml-2 h-4 w-4" />
                                          </Button>
                                      </DropdownMenuTrigger>
                                      <DropdownMenuContent class="w-full">
                                          <DropdownMenuCheckboxItem
                                              v-for="option in ['male', 'female', 'other']"
                                              :key="option"
                                              :checked="form.gender === option"
                                              @click="form.gender = option"
                                          >
                                              {{ option }}
                                          </DropdownMenuCheckboxItem>
                                      </DropdownMenuContent>
                                  </DropdownMenu>
                                  <InputError :message="form.errors.gender" />
                              </div>

                              <!-- Avatar -->
                              <div>
                                  <Label for="avatar_image">Avatar Image</Label>
                                  <Input id="avatar_image" type="file" @input="form.avatar_image = $event.target.files[0]" />
                                  <p v-if="client.avatar_img" class="text-muted-foreground mt-2 text-sm">
                                      Current image: {{ client.avatar_img.split('/').pop() }}
                                  </p>
                                  <InputError :message="form.errors.avatar_image" />
                              </div>
                          </div>

                          <div class="flex items-center justify-end">
                              <Button type="submit" :disabled="form.processing"> Update Client </Button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </AppLayout>
  </div>
</template>

<script setup>
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { ChevronDown } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';

const props = defineProps({
  client: Object,
});

const breadcrumbs = [
  {
      title: 'Dashboard',
      href: '/dashboard',
  },
  {
      title: 'Manage Clients',
      href: '/clients',
  },
  {
      title: 'Edit Client',
      href: `/clients/${props.client.id}/edit`,
  },
];

const form = useForm({
  name: props.client.name,
  email: props.client.email,
  password: '',
  national_id: props.client.national_id,
  mobile: props.client.mobile,
  country: props.client.country,
  gender: props.client.gender,
  avatar_image: null,
  _method: 'PUT',
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

      // If client already has a country, find and select it
      if (form.country) {
          const country = countries.value.find((c) => c.iso_alpha_3 === form.country || c.name === form.country);
          if (country) {
              selectedCountry.value = country;
          }
      }
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
  form.post(route('clients.update', props.client.id), {
      preserveScroll: true,
      onSuccess: () => {
          form.password = '';
          form.avatar_image = null;
      },
  });
};
</script>

