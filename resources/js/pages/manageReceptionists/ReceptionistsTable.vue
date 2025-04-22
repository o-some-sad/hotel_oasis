<script setup lang="ts">
import type { ColumnDef } from '@tanstack/vue-table'
import { Button } from '@/components/ui/button'
import { Checkbox } from '@/components/ui/checkbox'
import { Input } from '@/components/ui/input'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { FlexRender, getCoreRowModel, useVueTable } from '@tanstack/vue-table'
import { ArrowUpDown, Plus } from 'lucide-vue-next'
import { h, ref } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { SharedData } from '@/types'
import type { Receptionist } from '@/types'

const props = defineProps<{
    data: Receptionist[],
    links: any[]
}>()

const page = usePage<SharedData>()
const user = page.props.auth.user
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
            h(Button, {
                variant: 'ghost',
                onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
            }, () => ['Email', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })]),
        cell: ({ row }) => h('div', row.getValue('email')),
    },

    {
        accessorKey: 'created_by',
        header: 'Creator',
        cell: ({row}) => {
            const creatorId = row.getValue('created_by');

            if (creatorId) {
                return ` ${creatorId}`;

            }

            return '';
        }

    },

    {
        id: 'actions',
        header: 'Actions',
        cell: ({ row }) => {
            const receptionist = row.original;
            const isActionEnabled = receptionist.action;

            return h('div', { class: 'flex gap-2' }, [
                h(Button, {
                    variant: 'outline',
                    onClick: () => {
                        if (isActionEnabled) {
                            receptionist.banned_at
                                ? handleUnban(receptionist)
                                : handleBan(receptionist);
                        }
                    },
                    disabled: !isActionEnabled  // إذا كانت قيمة action هي false، يتم تعطيل الزر
                }, receptionist.banned_at ? 'Unban' : 'Ban'),

                h(Button, {
                    variant: 'outline',
                    onClick: () => {
                        if (isActionEnabled) {
                            handleUpdate(receptionist);
                        }
                    },
                    disabled: !isActionEnabled  // إذا كانت قيمة action هي false، يتم تعطيل الزر
                }, 'Edit'),

                h(Button, {
                    variant: 'destructive',
                    onClick: () => {
                        if (isActionEnabled) {
                            handleDelete(receptionist);
                        }
                    },
                    disabled: !isActionEnabled  // إذا كانت قيمة action هي false، يتم تعطيل الزر
                }, 'Delete'),
            ]);
        }
    }

]

function handleBan(receptionist: Receptionist) {
    router.patch(route('receptionists.update', { receptionist: receptionist.id }), {
        banned_at: new Date().toISOString(),
        _method: 'PATCH',
    })
}

function handleUnban(receptionist: Receptionist) {
    router.patch(route('receptionists.update', { receptionist: receptionist.id }), {
        banned_at: null,
        _method: 'PATCH',
    })
}

function handleDelete(receptionist: Receptionist) {
    if (confirm('Are you sure you want to delete this receptionist?')) {
        router.delete(route('receptionists.destroy', { receptionist: receptionist.id }))
    }
}

function handleUpdate(receptionist: Receptionist) {
    router.get(route('receptionists.edit', { receptionist: receptionist.id }))
}

const table = useVueTable({
    data: props.data,
    columns,
    getCoreRowModel: getCoreRowModel(),
    manualPagination: true
})
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
                    <Plus class="w-4 h-4" />
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

        <div class="flex justify-end py-4 space-x-2">
            <Link v-for="link in links" :key="link.label" :href="link.url || '#'">
                <Button v-html="link.label" variant="outline" />
            </Link>
        </div>
    </div>
</template>
