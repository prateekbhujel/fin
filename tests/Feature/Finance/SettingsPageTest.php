<?php

namespace Tests\Feature\Finance;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\CreatesFinanceAdmin;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use CreatesFinanceAdmin;
    use RefreshDatabase;

    public function test_settings_page_uses_finance_defaults_when_no_settings_have_been_saved(): void
    {
        $user = $this->createFinanceAdmin();

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertOk();
        $response->assertSee('value="Fin"', escape: false);
        $response->assertSee('value="info@example.com"', escape: false);
        $response->assertSee('value="accounts@fin.test"', escape: false);
    }
}
