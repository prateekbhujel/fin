<?php

namespace Tests\Feature\Finance;

use App\Mail\TransactionCreatedMail;
use App\Models\Category;
use App\Models\Document;
use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\CreatesFinanceAdmin;
use Tests\TestCase;

class TransactionWorkflowTest extends TestCase
{
    use CreatesFinanceAdmin;
    use RefreshDatabase;

    public function test_transaction_can_be_created_from_bs_date_and_notifies_via_email_when_enabled(): void
    {
        Mail::fake();

        $user = $this->createFinanceAdmin();
        $category = Category::create([
            'name' => 'Service Charge',
            'type' => 'income',
            'description' => 'Service income',
            'is_active' => true,
        ]);

        Setting::create([
            'group' => 'mail',
            'key' => 'mail.notification_email',
            'value' => 'accounts@example.com',
            'type' => 'string',
        ]);

        Setting::create([
            'group' => 'mail',
            'key' => 'mail.send_transaction_notifications',
            'value' => '1',
            'type' => 'boolean',
        ]);

        $bsDate = '2082-01-15';
        $adDate = ad_date_from_bs($bsDate);

        $response = $this->actingAs($user)->post(route('transactions.store'), [
            'title' => 'Consulting Fee',
            'type' => 'income',
            'category_id' => $category->id,
            'amount' => '1250.50',
            'transaction_date_bs' => $bsDate,
            'payment_method' => 'bank_transfer',
            'reference_no' => 'INV-2082-001',
            'notes' => 'Recorded from Bikram Sambat entry.',
        ]);

        $response->assertRedirect(route('transactions.index'));

        $this->assertDatabaseHas('transactions', [
            'title' => 'Consulting Fee',
            'transaction_bs' => $bsDate,
            'email_notified' => true,
        ]);

        $transaction = Transaction::query()->where('title', 'Consulting Fee')->firstOrFail();

        $this->assertSame($adDate, $transaction->transaction_date?->format('Y-m-d'));

        Mail::assertSent(TransactionCreatedMail::class, function (TransactionCreatedMail $mail) {
            return $mail->hasTo('accounts@example.com')
                && $mail->transaction->title === 'Consulting Fee';
        });
    }

    public function test_deleting_a_transaction_also_removes_attached_documents_and_files(): void
    {
        Storage::fake('public');

        $user = $this->createFinanceAdmin();
        $category = Category::create([
            'name' => 'Office Rent',
            'type' => 'expense',
            'description' => 'Recurring rent',
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Office Rent April',
            'type' => 'expense',
            'amount' => 25000,
            'transaction_date' => '2026-04-01',
            'transaction_bs' => bs_date('2026-04-01'),
            'payment_method' => 'bank_transfer',
        ]);

        Storage::disk('public')->put('documents/receipt-april.pdf', 'receipt');

        $document = Document::create([
            'transaction_id' => $transaction->id,
            'user_id' => $user->id,
            'original_name' => 'receipt-april.pdf',
            'stored_name' => 'receipt-april.pdf',
            'path' => 'documents/receipt-april.pdf',
            'disk' => 'public',
            'mime_type' => 'application/pdf',
            'size' => 7,
        ]);

        $response = $this->actingAs($user)->delete(route('transactions.destroy', $transaction));

        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseMissing('transactions', ['id' => $transaction->id]);
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing('documents/receipt-april.pdf');
    }
}
