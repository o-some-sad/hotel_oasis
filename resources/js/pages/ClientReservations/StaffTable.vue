<script setup lang="ts">
import type { ColumnDef, ColumnFiltersState, ExpandedState, SortingState, VisibilityState } from '@tanstack/vue-table';

import { Button } from '@/components/ui/button';

import { DropdownMenu, DropdownMenuCheckboxItem, DropdownMenuContent, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';

import Badge from '@/components/ui/badge/Badge.vue';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { SharedData, User } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { FlexRender, getCoreRowModel, getFilteredRowModel, useVueTable } from '@tanstack/vue-table';
import { ChevronDown } from 'lucide-vue-next';
import { h, ref } from 'vue';
import { PaginationData } from '.';

interface ReservationData {
    id: string;
    client: {
        id: string;
        name: string;
    };
    room: {
        id: string;
        number: number;
    };
    accompany_number: number;
    paid_price: number;
    payment_status: string;
    created_at: string;
}

const props = defineProps<{
    data: ReservationData[];
    links: PaginationData['links'];
}>();

const currencyFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

const page = usePage<SharedData>();
const user = page.props.auth.user as User;
const columns: ColumnDef<ReservationData>[] = [
    {
        accessorKey: 'client.name',
        header: 'Client Name',
    },
    {
        accessorKey: 'room.number',
        header: 'Room Number',
    },
    {
        accessorKey: 'accompany_number',
        header: 'Accompany Number',
    },
    {
        accessorKey: 'paid_price',
        header: 'Paid Price',
        cell: ({ row }) => h('div', { class: 'lowercase' }, currencyFormatter.format(row.getValue('paid_price') / 100)),
    },
    {
        accessorKey: 'payment_status',
        header: 'Payment Status',
        cell: ({ row }) => {
            return h(Badge, { class: 'text-right', variant: 'outline' }, row.getValue('payment_status'));
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            return h(
                'div',
                h(
                    Button,
                    {
                        onClick: () => {
                            // View details action
                        },
                    },
                    'View Details',
                ),
            );
        },
    },
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
    getFilteredRowModel: getFilteredRowModel(),
    onColumnFiltersChange: setColumnFilters,
    state: {
        sorting,
        columnFilters,
        columnVisibility,
        rowSelection,
        expanded,
    },
});

function setColumnFilters(filters) {
    columnFilters.value = filters;
}
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center gap-2">
                <Input
                    class="max-w-sm"
                    placeholder="Filter clients..."
                    :model-value="table.getColumn('client.name')?.getFilterValue() as string"
                    @update:model-value="table.getColumn('client.name')?.setFilterValue($event)"
                />
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
