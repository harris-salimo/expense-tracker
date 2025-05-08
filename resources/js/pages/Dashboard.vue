<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dayjs } from '@/lib/dayjs';
import { currencyFormat } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

interface Props {
    latestExpenses: Record<string, any>[];
    pastWeekTotalExpenses: string;
    pastMonthTotalExpenses: string;
    pastYearTotalExpenses: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-2xl font-medium text-foreground">{{ currencyFormat(pastWeekTotalExpenses) }}</p>

                    <p class="text-sm text-muted-foreground">Total Past Week Expenses</p>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-2xl font-medium text-foreground">{{ currencyFormat(pastMonthTotalExpenses) }}</p>

                    <p class="text-sm text-muted-foreground">Total Past Month Expenses</p>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-2xl font-medium text-foreground">{{ currencyFormat(pastYearTotalExpenses) }}</p>

                    <p class="text-sm text-muted-foreground">Total Past Year Expenses</p>
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border p-5 md:min-h-min">
                <Table class="caption-top">
                    <TableCaption
                        >A list of your recent expenses.
                        <TextLink class="inline-flex" :href="route('expense.index')">View more</TextLink></TableCaption
                    >
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> Creation date </TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead class="text-right"> Amount </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        ><TableRow v-for="expense in latestExpenses" :key="expense.id">
                            <TableCell class="font-medium">
                                {{ dayjs(expense.createdAt).format('l') }}
                            </TableCell>
                            <TableCell>{{ expense.category }}</TableCell>
                            <TableCell class="text-right"> {{ currencyFormat(expense.amount) }} </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
