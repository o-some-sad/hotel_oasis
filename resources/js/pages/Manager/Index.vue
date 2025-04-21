<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-white p-6 md:p-10 flex flex-col items-center gap-8">
    
    <!-- Title -->
    <div class="text-center space-y-2">
      <h1 class="text-4xl md:text-5xl font-bold text-gray-900 tracking-tight">
        Managers Dashboard
      </h1>
      <p class="text-gray-500 text-lg">Elegant management at your fingertips</p>
    </div>

    <!-- Add Manager Button -->
    <Link
  :href="route('manager.create')"
  class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white text-sm font-medium shadow hover:bg-blue-700 transition"
>
  <LucidePlus class="h-5 w-5" />
  Add New Manager
</Link>

    <!-- Table -->
    <div v-if="managers?.data?.length" class="w-full max-w-6xl overflow-hidden rounded-xl shadow-sm border bg-white">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead class="bg-gray-100">
            <tr>
              <th
                v-for="header in headers"
                :key="header"
                class="px-6 py-3 text-sm font-semibold text-gray-600 uppercase tracking-wider"
              >
                {{ header }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="manager in managers.data"
              :key="manager.id"
              class="border-t hover:bg-gray-50 transition"
            >
              <td
                v-for="header in headers"
                :key="`${manager.id}-${header}`"
                class="px-6 py-4 text-gray-700"
              >
                <template v-if="header === 'Update'">
                  <button
                    @click="handleUpdate(manager)"
                    class="text-blue-600 hover:text-blue-700 border border-blue-300 px-3 py-1 rounded-md text-xs font-medium transition"
                  >
                    Update
                  </button>
                </template>
                <template v-else-if="header === 'Delete'">
                  <button
                    @click="handleDelete(manager)"
                    class="text-rose-600 hover:text-rose-700 border border-rose-300 px-3 py-1 rounded-md text-xs font-medium transition"
                  >
                    Delete
                  </button>
                </template>
                <template v-else>
                  {{ getFieldValue(manager, header) || '-' }}
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div
        v-if="managers.links?.length > 3"
        class="flex items-center justify-between px-6 py-4 border-t bg-gray-50"
      >
        <div class="text-sm text-gray-500">
          Showing <span class="font-medium">{{ managers.from }}</span>
          to <span class="font-medium">{{ managers.to }}</span>
          of <span class="font-medium">{{ managers.total }}</span> managers
        </div>
        <div class="flex items-center space-x-2">
          <template v-for="(link, i) in managers.links" :key="i">
            <button
              v-if="link.url"
              :disabled="link.active"
              :class="[
                'px-3 py-1 rounded text-sm',
                link.active
                  ? 'bg-blue-600 text-white hover:bg-blue-700'
                  : 'text-gray-700 hover:bg-gray-100'
              ]"
              @click="navigate(link.url)"
              v-html="link.label"
            />
            <span v-else class="px-3 py-1 text-gray-400 text-sm" v-html="link.label" />
          </template>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center p-8 rounded-xl border border-dashed border-gray-200 bg-gray-50 max-w-md">
      <div class="flex justify-center">
        <LucideFolderOpen class="h-10 w-10 text-gray-400" />
      </div>
      <h3 class="mt-2 text-lg font-medium text-gray-900">No managers found</h3>
      <p class="mt-1 text-gray-500">Get started by adding a new manager to your sanctuary.</p>
      <div class="mt-4">
        <button
          @click="handleAdd"
          class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-white text-sm font-medium shadow hover:bg-blue-700 transition"
        >
          <LucidePlus class="h-4 w-4" />
          Add Manager
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { LucidePlus, LucideFolderOpen } from 'lucide-vue-next'
import { Inertia } from '@inertiajs/inertia'
const { managers } = defineProps({
  managers: Object
})

const headers = computed(() => {
  if (managers?.data?.length > 0) {
    const baseKeys = Object.keys(managers?.data[0] ?? {})
      .filter((key: string) => key !== 'id' && key !== 'index')
      .map(key => key.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase()))
    return [...baseKeys, 'Update', 'Delete']
  }
  return []
})

function getFieldValue(manager, header) {
  const formats = [
    header.toLowerCase().replace(/\s/g, '_'),
    header.toLowerCase().replace(/\s/g, ''),
    header.toLowerCase(),
    header.toLowerCase().replace(/\s/g, '_') + '_at',
    header.toLowerCase().replace(/\s/g, '') + 'At',
    'is_' + header.toLowerCase().replace(/\s/g, '_')
  ]
  for (const key of formats) {
    if (manager[key] !== undefined) return manager[key]
  }
  return null
}

function handleUpdate(manager) {
  console.log('Update:', manager)
}

function handleDelete(manager) {
  console.log('Delete:', manager)
}

function navigate(url: string) {
  window.location.href = url
}
</script>
