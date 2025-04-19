<script setup lang="ts">
import AppDataTable from '@/components/AppDataTable.vue';
import AppDataTableDropdownAction from '@/components/AppDataTableDropdownAction.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import { dayjs } from '@/lib/dayjs';
import { currencyFormat } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref } from 'vue';

interface Props {
    categories: Record<string, any>[];
    expenses: Record<string, any>[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Expenses',
        href: '/expenses',
    },
];

const toUpdatedExpense = ref<Record<string, any> | null>(null);
const updated = ref(false);
const deleted = ref(false);

export interface Expense {
    id: string;
    amount: number;
    category: string;
    createdAt: string;
}

const columns: ColumnDef<Expense>[] = [
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
        accessorKey: 'createdAt',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Creation Date', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'lowercase' }, dayjs(row.getValue('createdAt')).format('l')),
    },
    {
        accessorKey: 'category',
        header: ({ column }) => {
            return h(
                Button,
                {
                    variant: 'ghost',
                    onClick: () => column.toggleSorting(column.getIsSorted() === 'asc'),
                },
                () => ['Category', h(ArrowUpDown, { class: 'ml-2 h-4 w-4' })],
            );
        },
        cell: ({ row }) => h('div', { class: 'capitalize' }, row.getValue('category')),
    },
    {
        accessorKey: 'amount',
        header: () => h('div', { class: 'text-right' }, 'Amount'),
        cell: ({ row }) => {
            const formatted = currencyFormat(row.getValue('amount'));

            return h('div', { class: 'text-right font-medium' }, formatted);
        },
    },
    {
        id: 'actions',
        enableHiding: false,
        cell: ({ row }) => {
            const data = row.original;

            return h(AppDataTableDropdownAction, {
                data,
                onExpand: row.toggleExpanded,
                onUpdate: (id: number) => {
                    toUpdatedExpense.value = props.expenses.find((expense) => expense.id === id) ?? null;
                    updated.value = true;
                },
                onDelete: (id: number) => {
                    toUpdatedExpense.value = props.expenses.find((expense) => expense.id === id) ?? null;
                    deleted.value = true;
                },
            });
        },
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const updateExpense = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const deleteExpense = (e: Event) => {
    e.preventDefault();

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Expenses" />

    <AppLayout :breadcrumbs="breadcrumbs"
        ><div class="border-sidebar-border/70 dark:border-sidebar-border relative m-5 min-h-[100vh] flex-1 rounded-xl border p-5 md:min-h-min">
            <AppDataTable :columns="columns" :data="expenses" /></div
    ></AppLayout>

    <Dialog :open="updated"
        ><DialogContent>
            <form class="space-y-6" @submit="updateExpense">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                    <DialogDescription>
                        Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to
                        confirm you would like to permanently delete your account.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-2">
                    <Label for="password" class="sr-only">Password</Label>
                    <Input id="password" type="password" name="password" ref="passwordInput" v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal"> Cancel </Button>
                    </DialogClose>

                    <Button variant="destructive" :disabled="form.processing">
                        <button type="submit">Delete account</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent></Dialog
    >

    <Dialog :open="deleted"
        ><DialogContent>
            <form class="space-y-6" @submit="deleteExpense">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                    <DialogDescription>
                        Once your account is deleted, all of its resources and data will also be permanently deleted. Please enter your password to
                        confirm you would like to permanently delete your account.
                    </DialogDescription>
                </DialogHeader>

                <div class="grid gap-2">
                    <Label for="password" class="sr-only">Password</Label>
                    <Input id="password" type="password" name="password" ref="passwordInput" v-model="form.password" placeholder="Password" />
                    <InputError :message="form.errors.password" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="closeModal"> Cancel </Button>
                    </DialogClose>

                    <Button variant="destructive" :disabled="form.processing">
                        <button type="submit">Delete account</button>
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent></Dialog
    >
</template>
