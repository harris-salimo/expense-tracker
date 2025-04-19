import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function currencyFormat(value: string | number) {
    // return new Intl.NumberFormat('fr-FR', {
    //     style: 'currency',
    //     currency: 'MGA'
    // }).format(value)
    return `${value} Ar`;
}
