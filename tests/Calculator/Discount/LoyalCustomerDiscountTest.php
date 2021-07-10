<?php
declare(strict_types=1);

namespace App\Tests\Calculator\Discount;

use App\Calculator\Discount\LoyalCustomerDiscount;
use App\Domain\MoneyAmount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Customer\TestCustomerRepository;
use PHPUnit\Framework\TestCase;

final class LoyalCustomerDiscountTest extends TestCase
{
    private LoyalCustomerDiscount $discount;

    /** @test */
    public function it_should_not_apply_discount_for_customers_with_low_revenue(): void
    {
        $order = new Order(
            '1',
            '1',
            [
                new OrderItem('1', 100, MoneyAmount::fromInt(50_0000), MoneyAmount::fromInt(5000_0000)),
            ],
            MoneyAmount::fromInt(5000_0000),
        );

        self::assertCount(0, $this->discount->calculateDiscounts($order));
    }

    /** @test */
    public function it_should_apply_discount_for_customers_with_high_revenue(): void
    {
        $order = new Order(
            '2',
            '2',
            [
                new OrderItem('2', 1, MoneyAmount::fromInt(5_0000), MoneyAmount::fromInt(5_0000)),
            ],
            MoneyAmount::fromInt(5_0000),
        );

        $discounts = $this->discount->calculateDiscounts($order);
        self::assertCount(1, $discounts);
        self::assertSame(5000, $discounts[0]->getDiscount()->toInt());
    }

    protected function setUp(): void
    {
        $this->discount = new LoyalCustomerDiscount(new TestCustomerRepository());
    }
}
