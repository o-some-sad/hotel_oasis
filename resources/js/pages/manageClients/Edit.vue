<template>
  <div>
    <Head title="Edit Client" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="w-full p-6">
        <div class="mb-6 flex justify-between items-center">
          <h2 class="text-2xl font-bold">Edit Client</h2>
          <Link :href="route('clients.index')">
            <Button variant="default">
              Back to List
            </Button>
          </Link>
        </div>

        <div class="overflow-hidden shadow-sm rounded-lg">
          <div class="p-6 border-b">
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Name -->
                <div>
                  <Label for="name">Name</Label>
                  <Input 
                    id="name" 
                    v-model="form.name" 
                    type="text"
                    required 
                  />
                  <InputError :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div>
                  <Label for="email">Email</Label>
                  <Input 
                    id="email" 
                    v-model="form.email" 
                    type="email"
                    required 
                  />
                  <InputError :message="form.errors.email" />
                </div>

                <!-- Password (optional for editing) -->
                <div>
                  <Label for="password">Password (leave blank to keep current)</Label>
                  <Input 
                    id="password" 
                    v-model="form.password" 
                    type="password"
                  />
                  <InputError :message="form.errors.password" />
                </div>

                <!-- National ID -->
                <div>
                  <Label for="national_id">National ID</Label>
                  <Input 
                    id="national_id" 
                    v-model="form.national_id" 
                    type="text"
                    required 
                  />
                  <InputError :message="form.errors.national_id" />
                </div>

                <!-- Mobile -->
                <div>
                  <Label for="mobile">Mobile</Label>
                  <Input 
                    id="mobile" 
                    v-model="form.mobile" 
                    type="text"
                    required 
                  />
                  <InputError :message="form.errors.mobile" />
                </div>

                <!-- Country -->
                <div>
                  <Label for="country">Country</Label>
                  <Input 
                    id="country" 
                    v-model="form.country" 
                    type="text"
                    required 
                  />
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
                  <Input 
                    id="avatar_image" 
                    type="file"
                    @input="form.avatar_image = $event.target.files[0]" 
                  />
                  <p v-if="client.avatar_img" class="mt-2 text-sm text-muted-foreground">
                    Current image: {{ client.avatar_img.split('/').pop() }}
                  </p>
                  <InputError :message="form.errors.avatar_image" />
                </div>
              </div>

              <div class="flex items-center justify-end">
                <Button 
                  type="submit" 
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
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuCheckboxItem } from '@/components/ui/dropdown-menu'
import InputError from '@/components/InputError.vue'
import { ChevronDown } from 'lucide-vue-next'

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