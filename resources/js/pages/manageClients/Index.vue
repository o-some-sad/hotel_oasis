<template>
  <div>
    <Head title="Manage Clients" />
    <AppLayout :breadcrumbs="breadcrumbs">
      <div class="w-full p-6">
        <div class="mb-6 flex justify-between items-center">
          <h2 class="text-2xl font-bold">Client Management</h2>
          <Link
            :href="route('clients.create')"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            Add New Client
          </Link>
        </div>

        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <ClientsTable :data="clients" :links="[]" />
          </div>
        </div>
      </div>
    </AppLayout>
  </div>
</template>

<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import ClientsTable from './ClientsTable.vue'
import type { BreadcrumbItem } from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
  {
    title: 'Manage Clients',
    href: '/clients',
  },
]

defineProps({
  clients: Array,
})
</script>