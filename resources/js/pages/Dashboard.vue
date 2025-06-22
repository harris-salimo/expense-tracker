<script setup lang="ts">
import { buttonVariants } from '@/components/ui/button';
import { AreaChart } from '@/components/ui/chart-area';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dayjs } from '@/lib/dayjs';
import { formatCurrency, formatNumber } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { PlusIcon } from 'lucide-vue-next';

interface Props {
    latestExpenses: Record<string, any>[];
    pastWeekTotalExpenses: string;
    pastMonthTotalExpenses: string;
    pastYearTotalExpenses: string;
    monthlyExpenses: Record<string, any>[];
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const yTickFormatter = (value: any) => {
    return formatNumber(value as number);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <Link :href="route('expense.index')" :class="buttonVariants({ size: 'icon' })" class="rounded-full"><PlusIcon /></Link>
            </div>

            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-foreground text-2xl font-medium">{{ formatCurrency(pastWeekTotalExpenses) }}</p>

                    <p class="text-muted-foreground text-sm">Total Past Week Expenses</p>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-foreground text-2xl font-medium">{{ formatCurrency(pastMonthTotalExpenses) }}</p>

                    <p class="text-muted-foreground text-sm">Total Past Month Expenses</p>
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative overflow-hidden rounded-xl border p-5">
                    <p class="text-foreground text-2xl font-medium">{{ formatCurrency(pastYearTotalExpenses) }}</p>

                    <p class="text-muted-foreground text-sm">Total Past Year Expenses</p>
                </div>
            </div>

            <div class="border-sidebar-border/70 dark:border-sidebar-border relative rounded-xl border p-5">
                <AreaChart :data="monthlyExpenses" index="month" :categories="['expenses']" :show-legend="false" :y-formatter="yTickFormatter" />
            </div>

            <div class="border-sidebar-border/70 dark:border-sidebar-border relative rounded-xl border p-5">
                <Table class="caption-top">
                    <TableCaption>Last expenses.</TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> Creation date </TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead class="text-right"> Amount </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        ><template v-if="latestExpenses.length > 0"
                            ><TableRow v-for="expense in latestExpenses" :key="expense.id">
                                <TableCell class="font-medium">
                                    {{ dayjs(expense.createdAt).format('l') }}
                                </TableCell>
                                <TableCell>{{ expense.category }}</TableCell>
                                <TableCell class="text-right"> {{ formatCurrency(expense.amount) }} </TableCell>
                            </TableRow></template
                        ><template v-else
                            ><TableRow>
                                <TableCell :colspan="3" class="h-24 text-center"> No data available. </TableCell>
                            </TableRow></template
                        >
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
