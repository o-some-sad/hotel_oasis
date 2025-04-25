<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { router, usePage } from '@inertiajs/vue3';
import type { ColumnDef, ColumnFiltersState, SortingState, VisibilityState } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import dayjs from 'dayjs';
import { ArrowUpDown, MoreHorizontal, Plus } from 'lucide-vue-next';
import { computed, h, ref } from 'vue';
import { RowData } from '.';

const props = defineProps<{
    data: RowData[];
    links: any[];
}>();

const user = usePage().props.auth.user;

// Define the table columns
const columns: ColumnDef<RowData>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Name', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('name')),
    },
    {
        accessorKey: 'email',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'lowercase' }, row.getValue('email')),
    },
    {
        accessorKey: 'mobile',
        header: 'Mobile',
        cell: ({ row }) => h('div', {}, row.getValue('mobile')),
    },
    {
        accessorKey: 'country',
        header: 'Country',
        cell: ({ row }) => h('div', {}, row.getValue('country')),
    },
    {
        accessorKey: 'gender',
        header: 'Gender',
        cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('gender')),
    },
    {
        accessorKey: 'created_at',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Created At', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', {}, dayjs(row.getValue('created_at')).format('YYYY-MM-DD')),
    },
    {
        id: 'actions',
        header: 'Actions',
        cell: ({ row }) => {
            const manager = row.original;
            const isAdmin = user.role === 'admin';

            if (!isAdmin) {
                return h('div', {}, '');
            }

            return h('div', { class: 'flex justify-end' }, [
                h(
                    DropdownMenu,
                    {},
                    {
                        default: () => [
                            h(
                                DropdownMenuTrigger,
                                { asChild: true },
                                {
                                    default: () => [
                                        h(
                                            Button,
                                            { variant: 'ghost', class: 'h-8 w-8 p-0' },
                                            {
                                                default: () => [
                                                    h('span', { class: 'sr-only' }, 'Open menu'),
                                                    h(MoreHorizontal, { class: 'h-4 w-4' }),
                                                ],
                                            },
                                        ),
                                    ],
                                },
                            ),
                            h(
                                DropdownMenuContent,
                                { align: 'end' },
                                {
                                    default: () => [
                                        h(
                                            DropdownMenuItem,
                                            {
                                                onClick: () => router.visit(route('managers.show', manager.id)),
                                            },
                                            { default: () => 'View' },
                                        ),
                                        h(
                                            DropdownMenuItem,
                                            {
                                                onClick: () => router.visit(route('managers.edit', manager.id)),
                                            },
                                            { default: () => 'Edit' },
                                        ),
                                        h(
                                            DropdownMenuItem,
                                            {
                                                onClick: () => {
                                                    if (confirm('Are you sure you want to delete this manager?')) {
                                                        router.delete(route('managers.destroy', manager.id));
                                                    }
                                                },
                                            },
                                            { default: () => 'Delete' },
                                        ),
                                    ],
                                },
                            ),
                        ],
                    },
                ),
            ]);
        },
    },
];

// Set up table state
const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const searchValue = ref('');

// Function to ensure columnFilters[0] exists before setting its value
function updateFilterValue(value: string) {
    searchValue.value = value;
    if (!columnFilters.value[0]) {
        columnFilters.value[0] = { id: 'name', value: value };
    } else {
        columnFilters.value[0].value = value;
    }
    table.getColumn('name')?.setFilterValue(value);
}

// Create the table instance
const table = useVueTable({
    get data() {
        return props.data;
    },
    columns,
    state: {
        get sorting() {
            return sorting.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get rowSelection() {
            return rowSelection.value;
        },
    },
    onSortingChange: (updaterOrValue) => {
        sorting.value = typeof updaterOrValue === 'function' ? updaterOrValue(sorting.value) : updaterOrValue;
    },
    onColumnFiltersChange: (updaterOrValue) => {
        columnFilters.value = typeof updaterOrValue === 'function' ? updaterOrValue(columnFilters.value) : updaterOrValue;
    },
    onColumnVisibilityChange: (updaterOrValue) => {
        columnVisibility.value = typeof updaterOrValue === 'function' ? updaterOrValue(columnVisibility.value) : updaterOrValue;
    },
    onRowSelectionChange: (updaterOrValue) => {
        rowSelection.value = typeof updaterOrValue === 'function' ? updaterOrValue(rowSelection.value) : updaterOrValue;
    },
    getCoreRowModel: getCoreRowModel(),
});

const canCreate = computed(() => user.role === 'admin');

function handlePageChange(url) {
    router.visit(url);
}
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <Input placeholder="Filter managers..." class="max-w-sm" v-model="searchValue" @update:model-value="updateFilterValue" />
            <Button v-if="canCreate" @click="router.visit(route('managers.create'))"> <Plus class="mr-2 h-4 w-4" /> Add Manager </Button>
        </div>
        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead v-for="column in table.getVisibleLeafColumns()" :key="column.id">
                            <FlexRender v-if="column.getCanSort()" :render="column.columnDef.header" />
                            <span v-else>{{ column.columnDef.header }}</span>
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id" :data-state="row.getIsSelected() && 'selected'">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="table.getRowModel().rows?.length === 0">
                        <TableCell :colspan="columns.length" class="h-24 text-center"> No managers found. </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>
        <div class="mt-4 flex items-center justify-center space-x-2 py-4">
            <Pagination :links="links" @navigate="handlePageChange" />
        </div>
    </div>
</template>
