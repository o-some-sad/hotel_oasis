<template>
  <div>
    <Head title="Edit Client" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="w-full p-6 bg-white dark:bg-gray-900">
        <div class="mb-6 flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Edit Client</h2>
          <Link
            :href="route('clients.index')"
            class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            Back to List
          </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                  <Label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Name</Label>
                  <Input 
                    id="name" 
                    v-model="form.name" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    required 
                  />
                  <InputError :message="form.errors.name" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Email -->
                <div>
                  <Label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Email</Label>
                  <Input 
                    id="email" 
                    v-model="form.email" 
                    type="email" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    required 
                  />
                  <InputError :message="form.errors.email" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Password (optional for editing) -->
                <div>
                  <Label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Password (leave blank to keep current)</Label>
                  <Input 
                    id="password" 
                    v-model="form.password" 
                    type="password" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                  />
                  <InputError :message="form.errors.password" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- National ID -->
                <div>
                  <Label for="national_id" class="block text-sm font-medium text-gray-700 dark:text-gray-200">National ID</Label>
                  <Input 
                    id="national_id" 
                    v-model="form.national_id" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    required 
                  />
                  <InputError :message="form.errors.national_id" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Mobile -->
                <div>
                  <Label for="mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Mobile</Label>
                  <Input 
                    id="mobile" 
                    v-model="form.mobile" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    required 
                  />
                  <InputError :message="form.errors.mobile" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Country -->
                <div>
                  <Label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Country</Label>
                  <Input 
                    id="country" 
                    v-model="form.country" 
                    type="text" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    required 
                  />
                  <InputError :message="form.errors.country" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Gender -->
                <div>
                  <Label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Gender</Label>
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button 
                        variant="outline" 
                        class="w-full justify-between mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      >
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
                  <InputError :message="form.errors.gender" class="mt-2 text-red-600 dark:text-red-400" />
                </div>

                <!-- Avatar -->
                <div>
                  <Label for="avatar_image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Avatar Image</Label>
                  <Input 
                    id="avatar_image" 
                    type="file" 
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" 
                    @input="form.avatar_image = $event.target.files[0]" 
                  />
                  <p v-if="client.avatar_img" class="mt-2 text-sm text-gray-500">
                    Current image: {{ client.avatar_img.split('/').pop() }}
                  </p>
                  <InputError :message="form.errors.avatar_image" class="mt-2 text-red-600 dark:text-red-400" />
                </div>
              </div>

              <div class="flex items-center justify-end">
                <Button 
                  type="submit" 
                  class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" 
                  :disabled="form.processing"
                >
                  Update Client
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuCheckboxItem,  } from '@/components/ui/dropdown-menu'
import InputError from '@/components/InputError.vue'
// ChevronDown
const props = defineProps({
  client: Object,
})

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
]

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
})

const submit = () => {
  form.post(route('clients.update', props.client.id), {
    preserveScroll: true,
    onSuccess: () => {
      form.password = '';
      form.avatar_image = null;
    },
  })
}
</script>