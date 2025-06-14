<script setup lang="ts">
import AppDataTable from '@/components/AppDataTable.vue';
import AppDataTableDropdownAction from '@/components/AppDataTableDropdownAction.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogClose, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import { dayjs } from '@/lib/dayjs';
import { formatCurrency } from '@/lib/utils';
import { Category, Expense, type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ColumnDef } from '@tanstack/vue-table';
import { ArrowUpDown } from 'lucide-vue-next';
import { h, ref, watch } from 'vue';

interface Props {
    categories: Category[];
    expenses: Expense[];
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

const adding = ref(false);
const updating = ref(false);
const deleting = ref(false);

const form = useForm<{ id: string; category_id: string; amount: string }>({
    id: '',
    category_id: '',
    amount: '',
});

const columns: ColumnDef<Expense>[] = [
    // {
    //     id: 'select',
    //     header: ({ table }) =>
    //         h(Checkbox, {
    //             modelValue: table.getIsAllPageRowsSelected() || (table.getIsSomePageRowsSelected() && 'indeterminate'),
    //             'onUpdate:modelValue': (value) => table.toggleAllPageRowsSelected(!!value),
    //             ariaLabel: 'Select all',
    //         }),
    //     cell: ({ row }) =>
    //         h(Checkbox, {
    //             modelValue: row.getIsSelected(),
    //             'onUpdate:modelValue': (value) => row.toggleSelected(!!value),
    //             ariaLabel: 'Select row',
    //         }),
    //     enableSorting: false,
    //     enableHiding: false,
    // },
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
        cell: ({ row }) => h('div', { class: '' }, dayjs(row.getValue('createdAt')).format('l')),
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
            const formatted = formatCurrency(row.getValue('amount'));

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
                onUpdate: (id: string) => {
                    const defaults = props.expenses.find((expense) => expense.id === id);
                    form.id = defaults?.id;
                    form.category_id = defaults?.category_id;
                    form.amount = defaults?.amount;
                    updating.value = true;
                },
                onDelete: (id: string) => {
                    const defaults = props.expenses.find((expense) => expense.id === id);
                    form.id = defaults?.id;
                    form.category_id = defaults?.category_id;
                    form.amount = defaults?.amount;
                    deleting.value = true;
                },
            });
        },
    },
];

watch(form.data(), () => {
    console.log(form.data());
});

const onClose = (cb: () => void) => {
    form.cancel();
    cb();
};

const addExpense = (e: Event) => {
    e.preventDefault();

    form.post('expense.store', {
        preserveScroll: true,
        onSuccess: () => (adding.value = false),
        onError: () => {},
        onFinish: () => form.reset(),
    });
};

const updateExpense = (e: Event) => {
    e.preventDefault();

    form.put(('expense.update', form.data()), {
        preserveScroll: true,
        onSuccess: () => (updating.value = false),
        onError: () => {},
        onFinish: () => form.reset(),
    });
};

const deleteExpense = (e: Event) => {
    e.preventDefault();
    console.log(form.data());

    form.delete(('expense.destroy', { expense: form.data() }), {
        preserveScroll: true,
        onSuccess: () => (deleting.value = false),
        onError: () => {},
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Expenses" />

    <AppLayout :breadcrumbs="breadcrumbs"
        ><div class="border-sidebar-border/70 dark:border-sidebar-border relative m-5 rounded-xl border p-5">
            <div class="flex">
                <Button type="button" class="ml-auto" @click="adding = true">Ajouter</Button>
            </div>
            <AppDataTable :columns="columns" :data="expenses" /></div
    ></AppLayout>

    <Dialog :open="adding"
        ><DialogContent>
            <form class="space-y-6" @submit="addExpense">
                <DialogHeader class="space-y-3">
                    <DialogTitle>New expense</DialogTitle>
                </DialogHeader>

                <div class="grid gap-2">
                    <Label for="category" class="sr-only">Category</Label>
                    <Select id="category" name="category_id" v-model="form.category_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="category in categories" :value="category.id" :key="category.id"> {{ category.name }} </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.category_id" />
                </div>

                <div class="grid gap-2">
                    <Label for="amount" class="sr-only">Amount</Label>
                    <Input id="amount" type="number" name="amount" v-model="form.amount" min="0" step="0.01" placeholder="Amount" />
                    <InputError :message="form.errors.amount" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" type="button" @click="onClose(() => (adding = false))"> Cancel </Button>
                    </DialogClose>

                    <Button type="submit" :disabled="form.processing"> Add </Button>
                </DialogFooter>
            </form>
        </DialogContent></Dialog
    >

    <Dialog :open="updating"
        ><DialogContent>
            <form class="space-y-6" @submit="updateExpense">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Edit expense</DialogTitle>
                </DialogHeader>

                <div class="grid gap-2">
                    <Label for="category" class="sr-only">Category</Label>
                    <Select id="category" name="category_id" v-model="form.category_id">
                        <SelectTrigger class="w-full">
                            <SelectValue placeholder="Category" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="category in categories" :value="category.id" :key="category.id"> {{ category.name }} </SelectItem>
                        </SelectContent>
                    </Select>
                    <InputError :message="form.errors.category_id" />
                </div>

                <div class="grid gap-2">
                    <Label for="amount" class="sr-only">Amount</Label>
                    <Input id="amount" type="number" name="amount" v-model="form.amount" min="0" step="0.01" placeholder="Amount" />
                    <InputError :message="form.errors.amount" />
                </div>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="onClose(() => (updating = false))"> Cancel </Button>
                    </DialogClose>

                    <Button type="submit" :disabled="form.processing"> Save </Button>
                </DialogFooter>
            </form>
        </DialogContent></Dialog
    >

    <Dialog :open="deleting"
        ><DialogContent>
            <form class="space-y-6" @submit="deleteExpense">
                <DialogHeader class="space-y-3">
                    <DialogTitle>Delete expense</DialogTitle>
                </DialogHeader>

                <DialogDescription>
                    Are you sure you want to delete this expense ? Note that expense data will be permanently deleted.
                </DialogDescription>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary" @click="onClose(() => (deleting = false))"> Cancel </Button>
                    </DialogClose>

                    <Button variant="destructive" type="submit" :disabled="form.processing"> Delete </Button>
                </DialogFooter>
            </form>
        </DialogContent></Dialog
    >
</template>
