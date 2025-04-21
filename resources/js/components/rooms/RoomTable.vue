<script setup>
import { ref, computed } from 'vue'
import { Input } from 'shadcn-vue'
import { Button } from 'shadcn-vue'
import { DataTable } from 'shadcn-vue'

// Props
const props = defineProps({
    rooms: Array
})

// البحث
const search = ref('')

// تصفية البيانات حسب البحث
const filteredData = computed(() => {
    if (!search.value) return props.rooms
    return props.rooms.filter(room =>
        room.name.toLowerCase().includes(search.value.toLowerCase()) ||
        room.floor?.toLowerCase().includes(search.value.toLowerCase()) ||
        room.created_by?.toLowerCase().includes(search.value.toLowerCase())
    )
})

// الأعمدة مع الترتيب
const columns = [

    {
        key: 'name',
        title: 'Room Name',
        sortable: true,
    },
    {
        key: 'price',
        title: 'Price',
        sortable: true,
    },
    {
        key: 'floor',
        title: 'Floor',
        sortable: true,
    },
    {
        key: 'created_by',
        title: 'Created By',
        sortable: true,
    }
]

// Reset filters
function resetFilters() {
    search.value = ''
}
</script>

<template>
    <div class="space-y-4">
        <!-- 🔍 البحث -->
        <div class="flex items-center gap-4">
            <Input v-model="search" placeholder="Search rooms..." class="w-full max-w-sm" />
            <Button @click="resetFilters" variant="outline">Reset</Button>
        </div>

        <!-- 📋 الجدول -->
        <DataTable
            :data="filteredData"
            :columns="columns"
            class="border rounded-lg shadow-sm"
            :pagination="true"
            :page-size="10"
        />
    </div>
</template>
