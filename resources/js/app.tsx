import './bootstrap';
import 'antd/dist/reset.css';

import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';
import { createRoot } from 'react-dom/client';

type GuestExperienceData = {
    companyName: string;
    companyEmail: string;
    companyPhone: string;
    companyAddress: string;
};

type DashboardExperienceData = {
    summary: {
        income: number;
        expense: number;
        balance: number;
        transactionsCount: number;
    };
    monthlyTrend: {
        labels: string[];
        income: number[];
        expense: number[];
    };
    announcements: Array<{
        id: number;
        title: string;
        content: string;
        publishedAt: string | null;
    }>;
    recentTransactions: Array<{
        id: number;
        title: string;
        type: 'income' | 'expense';
        amount: number;
        categoryName: string | null;
        transactionDate: string | null;
        transactionBs: string | null;
        showUrl: string;
    }>;
};

(window as typeof window & { Alpine?: typeof Alpine; flatpickr?: typeof flatpickr }).Alpine = Alpine;
(window as typeof window & { Alpine?: typeof Alpine; flatpickr?: typeof flatpickr }).flatpickr = flatpickr;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll<HTMLElement>('[data-flatpickr]').forEach((element) => {
        flatpickr(element, {
            dateFormat: 'Y-m-d',
        });
    });

    void mountGuestExperience();
    void mountDashboardExperience();
});

async function mountGuestExperience() {
    const rootElement = document.getElementById('guest-experience-root');
    const data = readJsonScript<GuestExperienceData>('guest-experience-data');

    if (!rootElement || !data) {
        return;
    }

    const { GuestExperience } = await import('./react/GuestExperience');

    createRoot(rootElement).render(<GuestExperience {...data} />);
}

async function mountDashboardExperience() {
    const rootElement = document.getElementById('dashboard-experience-root');
    const data = readJsonScript<DashboardExperienceData>('dashboard-experience-data');

    if (!rootElement || !data) {
        return;
    }

    const { DashboardExperience } = await import('./react/DashboardExperience');

    createRoot(rootElement).render(<DashboardExperience {...data} />);
}

function readJsonScript<T>(id: string): T | null {
    const scriptElement = document.getElementById(id);

    if (!(scriptElement instanceof HTMLScriptElement) || !scriptElement.textContent) {
        return null;
    }

    try {
        return JSON.parse(scriptElement.textContent) as T;
    } catch {
        return null;
    }
}
