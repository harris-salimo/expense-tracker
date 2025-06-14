import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatCurrency(value: string | number) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'MGA',
    }).format(+value);
}

export function formatNumber(value: string | number) {
    return new Intl.NumberFormat('fr-FR', {
        notation: 'compact',
        compactDisplay: 'short',
    }).format(+value);
}
