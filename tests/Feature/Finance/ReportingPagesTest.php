<?php

namespace Tests\Feature\Finance;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesFinanceAdmin;
use Tests\TestCase;

class ReportingPagesTest extends TestCase
{
    use CreatesFinanceAdmin;
    use RefreshDatabase;

    public function test_dashboard_and_reports_render_financial_summaries(): void
    {
        $user = $this->createFinanceAdmin();

        $incomeCategory = Category::create([
            'name' => 'Sales',
            'type' => 'income',
            'description' => 'Sales income',
            'is_active' => true,
        ]);

        $expenseCategory = Category::create([
            'name' => 'Office Rent',
            'type' => 'expense',
            'description' => 'Office expense',
            'is_active' => true,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $incomeCategory->id,
            'title' => 'Consulting Income',
            'type' => 'income',
            'amount' => 1500,
            'transaction_date' => '2026-04-05',
            'transaction_bs' => bs_date('2026-04-05'),
            'payment_method' => 'cash',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $expenseCategory->id,
            'title' => 'Office Rent Payment',
            'type' => 'expense',
            'amount' => 500,
            'transaction_date' => '2026-04-12',
            'transaction_bs' => bs_date('2026-04-12'),
            'payment_method' => 'bank_transfer',
        ]);

        $dashboard = $this->actingAs($user)->get(route('dashboard'));

        $dashboard->assertOk();
        $dashboard->assertSee('Current Balance');
        $dashboard->assertSee('Consulting Income');
        $dashboard->assertSee('NPR 1,000.00');

        $reports = $this->actingAs($user)->get(route('reports.index'));

        $reports->assertOk();
        $reports->assertSee('Monthly Report');
        $reports->assertSee('2026-04');
        $reports->assertSee('Sales');
        $reports->assertSee('Office Rent');
    }
}
