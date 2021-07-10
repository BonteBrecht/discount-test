<?php
declare(strict_types=1);

namespace App\Tests\Calculator\Discount;

use App\Calculator\Discount\Buy6Get1FreeSwitchDiscount;
use App\Domain\Discount\AppliedDiscount;
use App\Domain\MoneyAmount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Product\TestProductRepository;
use PHPUnit\Framework\TestCase;

final class Buy6Get1FreeSwitchDiscountTest extends TestCase
{
    private Buy6Get1FreeSwitchDiscount $discount;

    /** @test */
    public function it_should_not_apply_discount_if_number_is_too_low(): void
    {
        $order = new Order(
            '1',
            '1',
            [
                new OrderItem('A101', 12, MoneyAmount::fromInt(10_0000), MoneyAmount::fromInt(120_0000)),
                new OrderItem('B101', 4, MoneyAmount::fromInt(1_0000), MoneyAmount::fromInt(4_0000)),
                new OrderItem('B102', 5, MoneyAmount::fromInt(2_0000), MoneyAmount::fromInt(10_0000)),
            ],
            MoneyAmount::fromInt(134_0000),
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
                new OrderItem('A101', 12, MoneyAmount::fromInt(10_0000), MoneyAmount::fromInt(120_0000)),
                new OrderItem('B101', 13, MoneyAmount::fromInt(1_0000), MoneyAmount::fromInt(13_0000)),
                new OrderItem('B102', 8, MoneyAmount::fromInt(2_0000), MoneyAmount::fromInt(16_0000)),
            ],
            MoneyAmount::fromInt(149_0000),
        );

        $discounts = $this->discount->calculateDiscounts($order);
        self::assertCount(3, $discounts);
        $discountAmountCounts = \array_reduce(
            $discounts,
            static function (array $amountCounts, AppliedDiscount $discount): array {
                $amount = $discount->getDiscount()->toInt();
                $amountCounts[$amount] = ($amountCounts[$amount] ?? 0) + 1;

                return $amountCounts;
            },
            [],
        );
        self::assertSame(2, $discountAmountCounts[1_0000] ?? 0, 'No discount added for B101');
        self::assertSame(1, $discountAmountCounts[2_0000] ?? 0, 'No discount added for B102');
    }

    protected function setUp(): void
    {
        $this->discount = new Buy6Get1FreeSwitchDiscount(new TestProductRepository());
    }
}
