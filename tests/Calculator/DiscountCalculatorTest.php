<?php
declare(strict_types=1);

namespace App\Tests\Calculator;

use App\Calculator\Discount\Buy6Get1FreeSwitchDiscount;
use App\Calculator\Discount\LoyalCustomerDiscount;
use App\Calculator\Discount\ToolDiscount;
use App\Calculator\DiscountCalculator;
use App\Domain\MoneyAmount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Customer\TestCustomerRepository;
use App\Repository\Product\TestProductRepository;
use PHPUnit\Framework\TestCase;

final class DiscountCalculatorTest extends TestCase
{
    private DiscountCalculator $calculator;

    /**
     * @test
     * @dataProvider orderDiscountProvider
     */
    public function it_should_calculate_correct_total_discount(Order $order, int $discountTotal): void
    {
        $appliedDiscounts = $this->calculator->calculateDiscounts($order);

        self::assertSame($discountTotal, $appliedDiscounts->getTotal()->toInt());
    }

    public function orderDiscountProvider(): array
    {
        $order1 = new Order(
            '1',
            '1',
            [
                new OrderItem('B102', 10, MoneyAmount::fromInt(4_9900), MoneyAmount::fromInt(49_9000)),
            ],
            MoneyAmount::fromInt(49_9000),
        );
        $order2 = new Order(
            '2',
            '2',
            [
                new OrderItem('B102', 5, MoneyAmount::fromInt(4_9900), MoneyAmount::fromInt(24_9500)),
            ],
            MoneyAmount::fromInt(24_9500),
        );
        $order3 = new Order(
            '3',
            '3',
            [
                new OrderItem('A101', 2, MoneyAmount::fromInt(9_7500), MoneyAmount::fromInt(19_5000)),
                new OrderItem('A102', 1, MoneyAmount::fromInt(49_5000), MoneyAmount::fromInt(49_5000)),
            ],
            MoneyAmount::fromInt(69_0000),
        );

        return [
            [$order1, 4_9900], // ❌ Customer not over 1000    ✔ 10 switch products        ❌ no tools        => 100% on one switch product
            [$order2, 2_4950], // ✔ Customer over 1000        ❌ only 5 switch products    ❌ no tools        => 10% on total
            [$order3, 3_9000], // ❌ Customer not over 1000    ❌ no switch products        ✔ 2 or more tools => 20% on cheapest (A101)
        ];
    }

    protected function setUp(): void
    {
        $customerRepository = new TestCustomerRepository();
        $productRepository = new TestProductRepository();

        $this->calculator = new DiscountCalculator([
            new LoyalCustomerDiscount($customerRepository),
            new Buy6Get1FreeSwitchDiscount($productRepository),
            new ToolDiscount($productRepository),
        ]);
    }
}
