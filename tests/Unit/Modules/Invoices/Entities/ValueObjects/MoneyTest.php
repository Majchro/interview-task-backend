<?php

declare(strict_types=1);

namespace App\Modules\Invoices\Entities\ValueObjects;

use App\Modules\Invoices\Domain\ValueObjects\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function test_money_value_object(): void
    {
        $money = new Money(100, 'USD');

        $this->assertEquals(100, $money->amount);
        $this->assertEquals('USD', $money->currency);
    }

    public function test_money_to_string(): void
    {
        $money = new Money(100, 'USD');

        $this->assertEquals('1 USD', (string) $money);
    }

    public function test_money_json_serialize(): void
    {
        $money = new Money(100, 'USD');

        $this->assertEquals('"1 USD"', json_encode($money));
    }
}
