<template>
  <div>
    <Head title="Client Details" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="overflow-hidden shadow-sm rounded-xl">
          <div class="p-6">
            <div class="mb-6 flex justify-between">
              <h3 class="text-lg font-medium">{{ client.name }}</h3>
              <div class="space-x-2">
                <Link :href="route('clients.edit', client.id)">
                  <Button variant="secondary">
                    Edit Client
                  </Button>
                </Link>
                <Link :href="route('clients.index')">
                  <Button variant="secondary">
                    Back to List
                  </Button>
                </Link>
              </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
              <!-- Client Avatar -->
              <div class="col-span-1">
                <div class="overflow-hidden rounded-lg p-4">
                  <div v-if="client.avatar_img" class="mb-4 flex justify-center">
                    <img 
                      :src="`/storage/${client.avatar_img}`" 
                      alt="Client Avatar" 
                      class="h-48 w-48 rounded-full object-cover"
                    />
                  </div>
                  <div v-else class="mb-4 flex h-48 w-full items-center justify-center rounded-full">
                    <span class="text-2xl">No Image</span>
                  </div>
                  
                  <div class="mt-4 text-center">
                    <Badge v-if="client.banned_at" variant="destructive">
                      Banned
                    </Badge>
                    <Badge v-else variant="success">
                      Active
                    </Badge>
                  </div>
                </div>
              </div>

              <!-- Client Information -->
              <div class="col-span-2">
                <div class="space-y-4">
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Name</p>
                      <p class="mt-1 text-lg">{{ client.name }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Email</p>
                      <p class="mt-1 text-lg">{{ client.email }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">National ID</p>
                      <p class="mt-1 text-lg">{{ client.national_id }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Mobile</p>
                      <p class="mt-1 text-lg">{{ client.mobile }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Country</p>
                      <p class="mt-1 text-lg">{{ client.country }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Gender</p>
                      <p class="mt-1 text-lg capitalize">{{ client.gender }}</p>
                    </div>
                    
                    <div class="rounded-md p-4">
                      <p class="text-sm font-medium">Created At</p>
                      <p class="mt-1 text-lg">{{ new Date(client.created_at).toLocaleString() }}</p>
                    </div>
                    
                    <div v-if="client.banned_at" class="rounded-md p-4">
                      <p class="text-sm font-medium">Banned At</p>
                    </div>
                  </div>
                  
                  <div class="mt-6 flex space-x-3">
                    <Button 
                      v-if="client.banned_at" 
                      @click="unbanClient"
                    >
                      Unban Client
                    </Button>
                    <Button 
                      v-else 
                      variant="default"
                      @click="banClient"
                    >
                      Ban Client
                    </Button>
                    <Button
                      variant="destructive"
                      @click="openDeleteDialog"
                    >
                      Delete Client
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Delete confirmation dialog -->
      <AlertDialog :open="isDeleteDialogOpen" @update:open="isDeleteDialogOpen = $event">
        <AlertDialogContent>
          <AlertDialogHeader>
            <AlertDialogTitle>Are you sure?</AlertDialogTitle>
            <AlertDialogDescription>
              This action cannot be undone. This will permanently delete the client from our servers.
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter>
            <AlertDialogCancel>Cancel</AlertDialogCancel>
            <AlertDialogAction @click="confirmDelete" variant="destructive">
              Delete
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </AppLayout>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { 
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog'

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
    title: 'Client Details',
    href: `/clients/${props.client.id}`,
  },
]

const isDeleteDialogOpen = ref(false)

const banClient = () => {
  router.post(route('clients.ban', props.client.id))
}

const unbanClient = () => {
  router.post(route('clients.unban', props.client.id))
}

const openDeleteDialog = () => {
  isDeleteDialogOpen.value = true
}

const confirmDelete = () => {
  router.delete(route('clients.destroy', props.client.id))
  isDeleteDialogOpen.value = false
}
</script>