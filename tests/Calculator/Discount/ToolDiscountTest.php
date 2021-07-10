<?php
declare(strict_types=1);

namespace App\Tests\Calculator\Discount;

use App\Calculator\Discount\ToolDiscount;
use App\Domain\MoneyAmount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Product\TestProductRepository;
use PHPUnit\Framework\TestCase;

final class ToolDiscountTest extends TestCase
{
    private ToolDiscount $discount;

    /** @test */
    public function it_should_not_apply_discount_if_threshold_is_not_reached(): void
    {
        $order = new Order(
            '1',
            '1',
            [
                new OrderItem('A101', 1, MoneyAmount::fromInt(10_0000), MoneyAmount::fromInt(10_0000)),
                new OrderItem('B101', 4, MoneyAmount::fromInt(1_0000), MoneyAmount::fromInt(4_0000)),
                new OrderItem('B102', 5, MoneyAmount::fromInt(2_0000), MoneyAmount::fromInt(10_0000)),
            ],
            MoneyAmount::fromInt(24_0000),
        );

        self::assertCount(0, $this->discount->calculateDiscounts($order));
    }

    /** @test */
    public function it_should_apply_discount_if_threshold_is_reached(): void
    {
        $order = new Order(
            '1',
            '1',
            [
                new OrderItem('A101', 2, MoneyAmount::fromInt(1_0000), MoneyAmount::fromInt(2_0000)),
                new OrderItem('A102', 1, MoneyAmount::fromInt(10_0000), MoneyAmount::fromInt(10_0000)),
                new OrderItem('B101', 4, MoneyAmount::fromInt(1_0000), MoneyAmount::fromInt(4_0000)),
                new OrderItem('B102', 5, MoneyAmount::fromInt(2_0000), MoneyAmount::fromInt(10_0000)),
            ],
            MoneyAmount::fromInt(24_0000),
        );

        $discounts = $this->discount->calculateDiscounts($order);
        self::assertCount(1, $discounts);
        self::assertSame(4000, $discounts[0]->getDiscount()->toInt());
    }

    protected function setUp(): void
    {
        $this->discount = new ToolDiscount(new TestProductRepository());
    }
}
