export function formatCurrency(amount: number): string {
    return new Intl.NumberFormat('en-NP', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
}

export function formatAdDate(date: string | null): string {
    if (!date) {
        return 'Date pending';
    }

    const parsed = new Date(date);

    if (Number.isNaN(parsed.getTime())) {
        return date;
    }

    return new Intl.DateTimeFormat('en-NP', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    }).format(parsed);
}
