<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { PaginationData } from '@/pages/manageRooms';
import type { Receptionist } from '@/types';
import { SharedData } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import type { ColumnDef } from '@tanstack/vue-table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { ArrowUpDown, Plus } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

const props = defineProps<{
    data: Receptionist[];
    links: PaginationData['links'];
}>();

console.log('Pagination links received:', props.links);
console.log('Data received:', props.data);

// Create a reactive local copy of the data
const receptionists = ref<Receptionist[]>([...props.data]);

// Watch for changes to props.data and update the local copy
watch(
    () => props.data,
    (newData) => {
        receptionists.value = [...newData];
    },
    { deep: true },
);

const page = usePage<SharedData>();
const user = page.props.auth.user;

// Add states for sorting, filtering, visibility and row selection
const sorting = ref([]);
const columnFilters = ref([]);
const columnVisibility = ref({});
const rowSelection = ref({});

const columns: ColumnDef<Receptionist>[] = [
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
        accessorKey: 'email',
        header: ({ column }) =>
            h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            ),
        cell: ({ row }) => h('div', row.getValue('email')),
    },

    {
        accessorKey: 'created_by',
        header: 'Creator',
        cell: ({ row }) => {
            const creatorId = row.getValue('created_by');

            if (creatorId) {
                return ` ${creatorId}`;
            }

            return '';
        },
    },

    {
        id: 'actions',
        header: 'Actions',
        cell: ({ row }) => {
            const receptionist = row.original;
            const isActionEnabled = receptionist.action;

            return h('div', { class: 'flex gap-2' }, [
                h(
                    Button,
                    {
                        variant: 'outline',
                        onClick: () => {
                            if (isActionEnabled) {
                                receptionist.banned_at ? handleUnban(receptionist) : handleBan(receptionist);
                            }
                        },
                        disabled: !isActionEnabled,
                    },
                    receptionist.banned_at ? 'Unban' : 'Ban',
                ),

                h(
                    Button,
                    {
                        variant: 'outline',
                        onClick: () => {
                            if (isActionEnabled) {
                                handleUpdate(receptionist);
                            }
                        },
                        disabled: !isActionEnabled,
                    },
                    'Edit',
                ),
                h(
                    Button,
                    {
                        variant: 'outline',
                        onClick: () => {
                            if (isActionEnabled) {
                                handleView(receptionist);
                            }
                        },
                        disabled: !isActionEnabled,
                    },
                    'View',
                ),

                h(
                    Button,
                    {
                        variant: 'destructive',
                        onClick: () => {
                            if (isActionEnabled) {
                                handleDelete(receptionist);
                            }
                        },
                        disabled: !isActionEnabled,
                    },
                    'Delete',
                ),
            ]);
        },
    },
];

function handleBan(receptionist: Receptionist) {
    router.patch(
        route('receptionists.update', { receptionist: receptionist.id }),
        {
            banned_at: new Date().toISOString(),
            _method: 'PATCH',
        },
        {
            preserveState: true,
            onSuccess: () => {
                // Update the local state immediately
                receptionist.banned_at = new Date().toISOString();
            },
        },
    );
}

function handleUnban(receptionist: Receptionist) {
    router.patch(
        route('receptionists.update', { receptionist: receptionist.id }),
        {
            banned_at: null,
            _method: 'PATCH',
        },
        {
            preserveState: true,
            onSuccess: () => {
                // Update the local state immediately
                receptionist.banned_at = null;
            },
        },
    );
}

function handleDelete(receptionist: Receptionist) {
    if (confirm('Are you sure you want to delete this receptionist?')) {
        router.delete(route('receptionists.destroy', { receptionist: receptionist.id }), {
            preserveState: true,
            onSuccess: () => {
                receptionists.value = receptionists.value.filter((r) => r.id !== receptionist.id);
            },
        });
    }
}

function handleUpdate(receptionist: Receptionist) {
    router.get(route('receptionists.edit', { receptionist: receptionist.id }));
}
function handleView(receptionist: Receptionist) {
    router.get(route('receptionists.show', { receptionist: receptionist.id }));
}

const table = useVueTable({
    get data() {
        return receptionists.value;
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
                placeholder="Filter email..."
                :model-value="table.getColumn('email')?.getFilterValue()"
                @update:model-value="table.getColumn('email')?.setFilterValue($event)"
            />

            <Link :href="route('receptionists.create')">
                <Button class="flex items-center gap-2">
                    <Plus class="h-4 w-4" />
                    Add Receptionist
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
                        <TableCell :colspan="columns.length" class="text-center">No receptionists found.</TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="text-muted-foreground flex-1 text-sm">
                {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }}
                row(s) selected.
            </div>
            <div class="space-x-2" v-if="links && links.length">
                <Link v-for="link in links" :key="link.label" :href="link.url ? link.url : '#'" :class="{ 'pointer-events-none': !link.url }">
                    <Button v-html="link.label" variant="outline" />
                </Link>
            </div>
        </div>
    </div>
</template>
