<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { Floor } from '@/types';
import { SharedData } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import type { ColumnDef, ColumnFiltersState, SortingState, VisibilityState } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { ArrowUpDown, Plus } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
    data: Floor[];
    links: any[];
}>();

// Create a reactive local copy of the data
const floors = ref<Floor[]>([...props.data]);

// Watch for changes to props.data and update the local copy
watch(
    () => props.data,
    (newData) => {
        floors.value = [...newData];
    },
    { deep: true },
);

const page = usePage<SharedData>();
const user = page.props.auth.user;
const isAdmin = user.role === 'admin';

// Add states for sorting, filtering, visibility and row selection
const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});

const columns: ColumnDef<Floor>[] = [
    {
        id: 'select',
        header: ({ table }) =>
            h(Checkbox, {
                modelValue: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
                'onUpdate:modelValue': (value) => table.toggleAllPageRowsSelected(!!value),
            }),
        cell: ({ row }) =>
            h(Checkbox, {
                modelValue: row.getIsSelected(),
                'onUpdate:modelValue': (value) => row.toggleSelected(!!value),
            }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: 'Name',
        cell: ({ row }) => h('div', row.getValue('name')),
    },
    {
        accessorKey: 'number',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Number', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            ),
        cell: ({ row }) => h('div', row.getValue('number')),
    },
];

// If user is admin, add the manager name column
if (isAdmin) {
    columns.push({
        accessorKey: 'creator',
        header: 'Manager Name',
        cell: ({ row }) => {
            const creator = row.original.creator;
            return h('div', creator ? creator.name : 'Unknown');
        },
    });
}

// Add actions column
columns.push({
    id: 'actions',
    header: 'Actions',
    cell: ({ row }) => {
        const floor = row.original;
        const isActionEnabled = floor.action;

        // Return empty div if the user can't perform actions on this floor
        if (!isActionEnabled) {
            return h('div');
        }

        return h('div', { class: 'flex gap-2' }, [
            h(
                Button,
                {
                    variant: 'outline',
                    onClick: () => {
                        handleUpdate(floor);
                    },
                },
                'Edit',
            ),
            h(
                Button,
                {
                    variant: 'destructive',
                    onClick: () => {
                        handleDelete(floor);
                    },
                },
                'Delete',
            ),
        ]);
    },
});

function handleDelete(floor: Floor) {
    if (confirm('Are you sure you want to delete this floor?')) {
        router.delete(route('floors.destroy', { floor: floor.id }), {
            preserveState: true,
            onSuccess: () => {
                // Remove the deleted floor from our reactive data array
                floors.value = floors.value.filter((f) => f.id !== floor.id);
            },
        });
    }
}

function handleUpdate(floor: Floor) {
    router.get(route('floors.edit', { floor: floor.id }));
}

const table = useVueTable({
    get data() {
        return floors.value;
    },
    columns,
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true,
    state: {
        get rowSelection() {
            return rowSelection.value;
        },
        get columnFilters() {
            return columnFilters.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
        get sorting() {
            return sorting.value;
        },
    },
    onRowSelectionChange: (updater) => {
        if (typeof updater === 'function') {
            rowSelection.value = updater(rowSelection.value);
        } else {
            rowSelection.value = updater;
        }
    },
});
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <Input
                class="max-w-sm"
                placeholder="Filter floor name..."
                :model-value="table.getColumn('name')?.getFilterValue()"
                @update:model-value="table.getColumn('name')?.setFilterValue($event)"
            />

            <Link :href="route('floors.create')">
                <Button class="flex items-center gap-2">
                    <Plus class="h-4 w-4" />
                    Add Floor
                </Button>
            </Link>
        </div>

        <div class="rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender :render="header.column.columnDef.header" :props="header.getContext()" />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id">
                        <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                            <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                        </TableCell>
                    </TableRow>
                    <TableRow v-if="!table.getRowModel().rows.length">
                        <TableCell :colspan="columns.length" class="text-center">No floors found.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="text-muted-foreground flex-1 text-sm">
                {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }}
                row(s) selected.
            </div>
            <div class="space-x-2">
                <Link v-for="link in links" :key="link.label" :href="link.url ? link.url : '#'">
                    <Button v-html="link.label" variant="outline" />
                </Link>
            </div>
        </div>
    </div>
</template>
