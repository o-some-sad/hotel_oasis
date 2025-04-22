<template>
  <nav v-if="links.length > 1" class="flex justify-center mt-4">
    <ul class="inline-flex -space-x-px">
      <li v-for="link in links" :key="link.url || link.label">
        <button
          v-if="link.url"
          :class="[
            'px-3 py-1 border border-gray-300',
            link.active ? 'bg-indigo-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100',
            'rounded'
          ]"
          @click="$emit('navigate', link.url)"
          v-html="link.label"
        />
        <span v-else class="px-3 py-1 border border-gray-300 bg-gray-100 text-gray-400 rounded" v-html="link.label" />
      </li>
    </ul>
  </nav>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from 'vue';

defineProps<{ links: Array<{ url: string | null; label: string; active: boolean }> }>();
defineEmits(['navigate']);
</script>
