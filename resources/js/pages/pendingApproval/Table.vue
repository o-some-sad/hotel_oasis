<script setup lang="ts">
import type { ColumnDef, ColumnFiltersState, ExpandedState, SortingState, VisibilityState } from '@tanstack/vue-table';
// import { valueUpdater } from '@/utils'
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
// import DropdownAction from '@/components/DataTableDropDown.vue'
import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table';
import { ArrowUpDown, ChevronDown, Plus } from 'lucide-vue-next';
import { h, ref } from 'vue';
import { PaginationData, RowData } from '.';
import { Link, usePage } from '@inertiajs/vue3';
import { SharedData, User } from '@/types';
// import DropdownAction from './DataTableDemoColumn.vue'


const props = defineProps<{
    data: RowData[],
    links: PaginationData['links']
}>();

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const columns: ColumnDef<RowData>[] = [
    {
        id: 'select',
        header: ({ table }) =>
            h(Checkbox, {
                modelValue: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
                'onUpdate:modelValue': (value) => table.toggleAllPageRowsSelected(!!value),
                ariaLabel: 'Select all',
            }),
        cell: ({ row }) =>
            h(Checkbox, {
                modelValue: row.getIsSelected(),
                'onUpdate:modelValue': (value) => row.toggleSelected(!!value),
                ariaLabel: 'Select row',
            }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: 'Name',
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
        cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('mobile')),
    },
    {
        accessorKey: 'country',
        header: () => h('div', {}, 'Country'),
        cell: ({ row }) => {
            return h('div', row.getValue('country'));
        },
    },
    {
        accessorKey: 'gender',
        header: ()=> h('div', { class: 'text-center' }, 'Gender'),
        cell: ({ row }) => {
            return h('div', { class: 'text-center' }, row.getValue('gender'));
        },
    },
    {
  id: 'actions',
  enableHiding: false,
  cell: ({ row }) => {
    const user = row.original;
    return h('div', { class: 'text-center' }, [
      h(Button, {
        onClick: () => {
          window.location.href = `/pending-approval/${user.id}/approve`;
        },
        variant: 'success'
      }, 'Approve'),
      
    ]);
  },
}

];

const sorting = ref<SortingState>([]);
const columnFilters = ref<ColumnFiltersState>([]);
const columnVisibility = ref<VisibilityState>({});
const rowSelection = ref({});
const expanded = ref<ExpandedState>({});

const table = useVueTable({
    data: props.data,
    manualPagination: true,
    columns,
    getCoreRowModel: getCoreRowModel(),
    // getPaginationRowModel: getPaginationRowModel(),
    // getSortedRowModel: getSortedRowModel(),
    // getFilteredRowModel: getFilteredRowModel(),
    // getExpandedRowModel: getExpandedRowModel(),
    // onSortingChange: updaterOrValue => valueUpdater(updaterOrValue, sorting),
    // onColumnFiltersChange: updaterOrValue => valueUpdater(updaterOrValue, columnFilters),
    // onColumnVisibilityChange: updaterOrValue => valueUpdater(updaterOrValue, columnVisibility),
    // onRowSelectionChange: updaterOrValue => valueUpdater(updaterOrValue, rowSelection),
    // onExpandedChange: updaterOrValue => valueUpdater(updaterOrValue, expanded),
    // state: {
    //   get sorting() { return sorting.value },
    //   get columnFilters() { return columnFilters.value },
    //   get columnVisibility() { return columnVisibility.value },
    //   get rowSelection() { return rowSelection.value },
    //   get expanded() { return expanded.value },
    // },
});
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center gap-2">
                <Input
                    class="max-w-sm"
                    placeholder="Filter emails..."
                    :model-value="table.getColumn('email')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('email')?.setFilterValue($event)"
                />
                <Button variant="outline">
                    <Plus />
                </Button>
            </div>
            <DropdownMenu>
                <DropdownMenuTrigger as-child>
                    <Button variant="outline"> Columns <ChevronDown class="ml-2 h-4 w-4" /> </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuCheckboxItem
                        v-for="column in table.getAllColumns().filter((column) => column.getCanHide())"
                        :key="column.id"
                        class="capitalize"
                        :model-value="column.getIsVisible()"
                        @update:model-value="
                            (value) => {
                                column.toggleVisibility(!!value);
                            }
                        "
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
                            <FlexRender v-if="!header.isPlaceholder" :render="header.column.columnDef.header" :props="header.getContext()" />
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
                        <TableCell :colspan="columns.length" class="h-24 text-center"> No results. </TableCell>
                    </TableRow>
                </TableBody>
            </Table>
        </div>

        <div class="flex items-center justify-end space-x-2 py-4">
            <div class="text-muted-foreground flex-1 text-sm">
                {{ table.getFilteredSelectedRowModel().rows.length }} of {{ table.getFilteredRowModel().rows.length }} row(s) selected.
            </div>
            <div class="space-x-2">
                <Link v-for="link in links" :key="link.label" :href="link.url ? link.url : '#'">
                    <Button v-html="link.label" variant="outline" />
                </Link>
            </div>
        </div>
    </div>
</template>
