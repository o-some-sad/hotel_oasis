<script setup lang="ts">
import type { ColumnDef, ColumnFiltersState, ExpandedState, SortingState, VisibilityState } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import {ArrowUpDown, ChevronDown, Plus} from 'lucide-vue-next';
import { h, ref, watch } from 'vue';
import { PaginationData, RowData } from '.';
import { Link, usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps<{
    data: RowData[],
    links: PaginationData['links']
}>();

// Create a reactive local copy of the data
const rooms = ref<RowData[]>([...props.data]);

// Watch for changes to props.data and update the local copy
watch(
    () => props.data,
    (newData) => {
        rooms.value = [...newData];
    },
    { deep: true },
);

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

// Handle room deletion
function handleDelete(room: RowData) {
    const isOwnerOrAdmin = user.id === room.created_by || user.role === 'admin';
    
    if (isOwnerOrAdmin && confirm('Are you sure you want to delete this room?')) {
        router.delete(route('rooms.destroy', room.id), {
            preserveState: true,
            onSuccess: () => {
                // Remove the deleted room from our reactive data array
                rooms.value = rooms.value.filter(r => r.id !== room.id);
            },
        });
    }
}

const columns: ColumnDef<RowData>[] = [
    {
        id: 'select',
        header: ({ table }) =>
            h('div', { class: 'text-center' }, [
                h(Checkbox, {
                    modelValue: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
                    'onUpdate:modelValue': (value) => table.toggleAllPageRowsSelected(!!value),
                    ariaLabel: 'Select all',
                })
            ]),
        cell: ({ row }) =>
            h('div', { class: 'text-center' }, [
                h(Checkbox, {
                    modelValue: row.getIsSelected(),
                    'onUpdate:modelValue': (value) => row.toggleSelected(!!value),
                    ariaLabel: 'Select row',
                })
            ]),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'number',
        header: () => h('div', { class: 'text-left' }, 'Number'),
        cell: ({ row }) => h('div', { class: 'text-left' }, row.getValue('number')),
    },
    {
        accessorKey: 'capacity',
        header: ({ column }) =>
            h('div', { class: 'text-center' }, [
                h(
                    Button,
                    {
                        variant: 'ghost',
                        onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                        class: 'mx-auto',
                    },
                    () => ['Capacity', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
                ),
            ]),
        cell: ({ row }) => h('div', { class: 'text-center' }, row.getValue('capacity')),
    },
    {
        accessorKey: 'price',
        header: () => h('div', { class: 'text-center' }, 'Price'),
        cell: ({ row }) => h('div', { class: 'text-center' }, `${(row.getValue('price') )}`)
    },

    {
        accessorKey: 'floor_id',
        header: () => h('div', { class: 'text-center' }, 'Floor'),
        cell: ({ row }) => h('div', { class: 'text-center' }, row.getValue('floor_id')),
    },
    {
        accessorKey: 'created_by',
        header: () => h('div', { class: 'text-right' }, 'Creator'),
        cell: ({ row }) => h('div', { class: 'text-right' }, row.getValue('created_by')),
    },
    {
        id: 'actions',
        header: () => h('div', { class: 'text-center' }, 'Actions'),
        cell: ({ row }) => {
            const room = row.original;
            const isOwnerOrAdmin = user.id === room.created_by || user.role === 'admin';

            return h('div', { class: 'flex gap-2 flex-wrap justify-center' }, [
                h(Button, {
                    variant: 'outline',
                    onClick: () => router.visit(route('rooms.show', room.id)),
                    disabled: !isOwnerOrAdmin,
                }, 'View'),

                h(Button, {
                    variant: 'outline',
                    onClick: () => router.visit(route('rooms.edit', room.id)),
                    disabled: !isOwnerOrAdmin,
                }, 'Edit'),

                h(Button, {
                    variant: 'destructive',
                    onClick: () => {
                        handleDelete(room);
                    },
                    disabled: !isOwnerOrAdmin,
                }, 'Delete'),
            ]);
        },
    },
];

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const expanded = ref<ExpandedState>({});

const table = useVueTable({
    get data() {
        return rooms.value;
    },
    manualPagination: true,
    columns,
    getCoreRowModel: getCoreRowModel(),
});
</script>
<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <Input
                class="max-w-sm"
                placeholder="Filter price..."
                :model-value="table.getColumn('price')?.getFilterValue()"
                @update:model-value="table.getColumn('price')?.setFilterValue($event)"
            />
            <Link :href="route('rooms.create')">
                <Button class="flex items-center gap-2">
                    <Plus class="h-4 w-4" />
                    Add Room
                </Button>
            </Link>
        </div>
        <div class="flex items-center justify-between py-4">
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline">
                        Columns <ChevronDown class="ml-2 h-4 w-4" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem
                        v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                        :key="column.id"
                        class="capitalize"
                        :model-value="column.getIsVisible()"
                        @update:model-value="(value) => column.toggleVisibility(!!value)"
                    >
                           {{ column.id }}
                    </DropdownMenuCheckboxItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows?.length">
                        <template v-for="row in table.getRowModel().rows" :key="row.id">
                            <TableRow :data-state="row.getIsSelected() && 'selected'">
                                <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                    <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="row.getIsExpanded()">
                                <TableCell :colspan="row.getAllCells().length">
                                    {{ JSON.stringify(row.original) }}
                                </TableCell>
                            </TableRow>
                        </template>
                    </template>

                    <TableRow v-else>
                        <TableCell :colspan="columns.length" class="h-24 text-center">No results.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="text-muted-foreground flex-1 text-sm">
                {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }} row(s) selected.
            </div>
        </div>
    </div>
</template>
