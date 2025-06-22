<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Categories',
        href: '/expenses',
    },
];
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs"
        ><div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border p-5 md:min-h-min">
            <AppDataTable :columns="[]" :data="[]" /></div
    ></AppLayout>
</template>
