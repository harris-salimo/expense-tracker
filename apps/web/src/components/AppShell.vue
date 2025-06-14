<script setup lang="ts">
import { SidebarProvider } from '@workspace/ui/components';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = true;
</script>

<template>
    <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
        <slot />
    </div>
    <SidebarProvider v-else :default-open="isOpen">
        <slot />
    </SidebarProvider>
</template>
