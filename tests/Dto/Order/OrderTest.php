<?php
declare(strict_types=1);

namespace App\Tests\Dto\Order;

use App\Dto\Order\Order;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    /** @test */
    public function it_should_be_able_to_parse_an_order(): void
    {
        $orderJson = <<<'JSON'
            {
              "id": "3",
              "customer-id": "2",
              "items": [
                {
                  "product-id": "A101",
                  "quantity": "2",
                  "unit-price": "9.75",
                  "total": "19.50"
                },
                {
                  "product-id": "A102",
                  "quantity": "1",
                  "unit-price": "49.50",
                  "total": "49.50"
                }
              ],
              "total": "69.00"
            }
        JSON;
        $orderData = \json_decode($orderJson, true);
        \assert(\is_array($orderData));

        $orderDto = Order::createFromArray($orderData);
        self::assertSame('3', $orderDto->getId());
        self::assertSame('2', $orderDto->getCustomerId());
        self::assertCount(2, $orderDto->getOrderItems());
        self::assertSame(69_0000, $orderDto->getTotal()->toInt());

        $firstOrderItem = $orderDto->getOrderItems()[0];
        self::assertSame('A101', $firstOrderItem->getProductId());
        self::assertSame(2, $firstOrderItem->getQuantity());
        self::assertSame(9_7500, $firstOrderItem->getUnitPriceAmount()->toInt());
        self::assertSame(19_5000, $firstOrderItem->getTotalAmount()->toInt());

        $secondOrderItem = $orderDto->getOrderItems()[1];
        self::assertSame('A102', $secondOrderItem->getProductId());
        self::assertSame(1, $secondOrderItem->getQuantity());
        self::assertSame(49_5000, $secondOrderItem->getUnitPriceAmount()->toInt());
        self::assertSame(49_5000, $secondOrderItem->getTotalAmount()->toInt());
    }
}
