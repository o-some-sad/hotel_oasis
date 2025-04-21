<template>
  <div>

    <Head title="Create Client" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="overflow-hidden bg-white shadow-sm rounded-xl">
          <div class="p-6 text-gray-900">
            <div class="mb-6 flex justify-between">
              <h3 class="text-lg font-medium">Client Details</h3>
              <Link
                :href="route('clients.index')"
                class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300"
              >
                Back to List
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
                  <Input id="country" v-model="form.country" type="text" class="mt-1 block w-full" required maxlength="50" />
                  <InputError :message="form.errors.country" class="mt-2" />
                </div>

                <!-- Gender -->
                <div>
                  <Label for="gender" class="block text-sm font-medium text-gray-700">Gender</Label>
                  <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                      <Button 
                        variant="outline" 
                        class="w-full justify-between mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                      >
                        {{ form.gender || 'Select Gender' }}
                      </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent class="w-full">
                      <DropdownMenuCheckboxItem   class="w-full"
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
                <Button 
                  type="submit" 
                  class="ml-4" 
                  :disabled="form.processing"
                >
                  Create Client
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script lang="ts" setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue' 
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuCheckboxItem } from '@/components/ui/dropdown-menu'
import InputError from '@/components/InputError.vue'
import { type BreadcrumbItem } from '@/types'

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
]

const form = useForm({
  name: '',
  email: '',
  password: '',
  national_id: '',
  mobile: '',
  country: '',
  gender: '',
  avatar_image: null,
})

const submit = () => {
  form.post(route('clients.store'), {
    preserveScroll: true,
    forceFormData: true,
    onSuccess: () => form.reset(),
    onError: (errors) => {
      alert('Failed to create client. Please check the form and try again.');
    },
  })
}
</script> 