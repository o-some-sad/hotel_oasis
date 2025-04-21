<template>
    <div class="p-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Managers</h1>
  
      <div v-if="managers?.data?.length" class="overflow-x-auto rounded-2xl shadow-lg bg-white">
        <table class="min-w-full text-sm text-left text-gray-700">
          <thead class="bg-gray-100 uppercase text-xs text-gray-500">
            <tr>
              <th class="px-6 py-4">#</th>
              <th class="px-6 py-4">Name</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">National ID</th>
              <th class="px-6 py-4">Mobile</th>
              <th class="px-6 py-4">Country</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(manager, index) in managers.data" :key="manager.id" class="hover:bg-gray-50 border-b">
              <td class="px-6 py-4">{{ index + 1 }}</td>
              <td class="px-6 py-4 font-semibold text-gray-900">{{ manager.name }}</td>
              <td class="px-6 py-4">{{ manager.email }}</td>
              <td class="px-6 py-4">{{ manager.national_id }}</td>
              <td class="px-6 py-4">{{ manager.mobile }}</td>
              <td class="px-6 py-4">{{ manager.country }}</td>
            </tr>
          </tbody>
        </table>
      </div>
  
      <p v-else class="text-gray-500">No managers found.</p>
  
      <!-- Pagination (Updated Section) -->
      <div v-if="managers?.links?.length > 3" class="flex justify-center mt-8 space-x-2">
        <template v-for="(link, i) in managers?.links" :key="i">
          <a
            v-if="link.url"
            :href="link.url"
            class="px-4 py-2 rounded-lg border text-sm"
            :class="{
              'bg-blue-600 text-white': link.active,
              'text-gray-700 hover:bg-gray-200': !link.active && link.url
            }"
          >
            {{ link.label }}
          </a>
          <span
            v-else
            class="px-4 py-2 rounded-lg border text-sm text-gray-400 cursor-not-allowed"
          >
            {{ link.label }}
          </span>
        </template>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  const { managers } = defineProps({
    managers: Object
  });
  </script>