<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { AreaChart } from '@/components/ui/chart-area';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { MoveRightIcon } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const pastWeekData = [
    { name: 'Mon', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Tue', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Wed', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Thu', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Fri', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Sat', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Sun', value: Math.floor(Math.random() * 2000) + 500 },
];

const pastMonthData = [
    { name: '1', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '2', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '3', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '4', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '5', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '6', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '7', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '8', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '9', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '10', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '11', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '12', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '13', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '14', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '15', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '16', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '17', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '18', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '19', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '20', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '21', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '22', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '23', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '24', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '25', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '26', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '27', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '28', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '29', value: Math.floor(Math.random() * 2000) + 500 },
    { name: '30', value: Math.floor(Math.random() * 2000) + 500 },
];

const pastYearData = [
    { name: 'Jan', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Feb', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Mar', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Apr', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'May', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Jun', value: Math.floor(Math.random() * 2000) + 500 },
    { name: 'Jul', value: Math.floor(Math.random() * 2000) + 500 },
];

const expenses = [
    {
        id: 1,
        createdAt: '17/4/2025',
        amount: '$250.00',
        category: 'Credit Card',
    },
    {
        id: 2,
        createdAt: '17/4/2025',
        amount: '$150.00',
        category: 'PayPal',
    },
    {
        id: 3,
        createdAt: '17/4/2025',
        amount: '$350.00',
        category: 'Bank Transfer',
    },
    {
        id: 4,
        createdAt: '17/4/2025',
        amount: '$450.00',
        category: 'Credit Card',
    },
    {
        id: 5,
        createdAt: '17/4/2025',
        amount: '$550.00',
        category: 'PayPal',
    },
    {
        id: 6,
        createdAt: '17/4/2025',
        amount: '$250.00',
        category: 'Credit Card',
    },
    {
        id: 7,
        createdAt: '17/4/2025',
        amount: '$150.00',
        category: 'PayPal',
    },
    {
        id: 8,
        createdAt: '17/4/2025',
        amount: '$350.00',
        category: 'Bank Transfer',
    },
    {
        id: 9,
        createdAt: '17/4/2025',
        amount: '$450.00',
        category: 'Credit Card',
    },
    {
        id: 10,
        createdAt: '17/4/2025',
        amount: '$550.00',
        category: 'PayPal',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-5">
                    <AreaChart
                        class="h-full"
                        :data="pastWeekData"
                        index="name"
                        :categories="['value']"
                        :show-legend="false"
                        :show-tooltip="false"
                        :show-x-axis="false"
                        :show-y-axis="false"
                    />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-5">
                    <AreaChart
                        class="h-full"
                        :data="pastMonthData"
                        index="name"
                        :categories="['value']"
                        :show-legend="false"
                        :show-tooltip="false"
                        :show-x-axis="false"
                        :show-y-axis="false"
                    />
                </div>
                <div class="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border p-5">
                    <AreaChart
                        class="h-full"
                        :data="pastYearData"
                        index="name"
                        :categories="['value']"
                        :show-legend="false"
                        :show-tooltip="false"
                        :show-x-axis="false"
                        :show-y-axis="false"
                    />
                </div>
            </div>
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border p-5 md:min-h-min">
                <Table class="caption-top">
                    <TableCaption
                        >A list of your recent expenses.
                        <TextLink class="inline-flex" href="/expenses">View more <MoveRightIcon class="ml-1 w-4" /></TextLink
                    ></TableCaption>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]"> Creation date </TableHead>
                            <TableHead>Category</TableHead>
                            <TableHead class="text-right"> Amount </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody
                        ><TableRow v-for="expense in expenses" :key="expense.id">
                            <TableCell class="font-medium">
                                {{ expense.createdAt }}
                            </TableCell>
                            <TableCell>{{ expense.category }}</TableCell>
                            <TableCell class="text-right">
                                {{ expense.amount }}
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
