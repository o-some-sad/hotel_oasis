<template>
    <div class="p-6 max-w-md mx-auto">
      <h2 class="text-xl font-bold mb-4">Approve Client</h2>
      <form :action="`/clients/${client.id}/approve`" method="POST">
        <input type="hidden" name="_token" :value="csrf" />
        <button
          type="submit"
          class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
        >
          Approve
        </button>
      </form>
    </div>
  </template>
  
  <script setup    lang="ts">
  defineProps({
    client: Object,
  });
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  </script>
  