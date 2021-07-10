<?php
declare(strict_types=1);

namespace App\Calculator\Discount;

use App\Domain\Discount\AppliedDiscount;
use App\Domain\MoneyAmount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Product\ProductRepository;

final class ToolDiscount implements Discount
{
    private const NAME = 'tool_discount';
    private const TOOL_ID = '1';
    private const AMOUNT_THRESHOLD = 2;
    private const DISCOUNT = 0.2;

    /** @var array<string, string|null> */
    private array $productCategoryIds = [];

    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function calculateDiscounts(Order $order): array
    {
        if (!$this->reachesAmountThreshold($order)) {
            return [];
        }
        $cheapestTotal = $this->getCheapestToolTotal($order);
        \assert($cheapestTotal !== null);

        return [
            new AppliedDiscount(self::NAME, $cheapestTotal->multiply(self::DISCOUNT)),
        ];
    }

    private function reachesAmountThreshold(Order $order): bool
    {
        $count = 0;
        foreach ($order->getOrderItems() as $orderItem) {
            if (!$this->isTool($orderItem)) {
                continue;
            }
            $count += $orderItem->getQuantity();
            if ($count >= self::AMOUNT_THRESHOLD) {
                return true;
            }
        }

        return false;
    }

    private function getCheapestToolTotal(Order $order): ?MoneyAmount
    {
        $cheapest = null;
        foreach ($order->getOrderItems() as $orderItem) {
            if (!$this->isTool($orderItem)) {
                continue;
            }
            $orderItemTotal = $orderItem->getTotalAmount()->toInt();
            if ($cheapest === null || $orderItemTotal < $cheapest) {
                $cheapest = $orderItemTotal;
            }
        }

        return $cheapest !== null ? MoneyAmount::fromInt($cheapest) : null;
    }

    private function isTool(OrderItem $orderItem): bool
    {
        return $this->getProductCategoryId($orderItem->getProductId()) === self::TOOL_ID;
    }

    private function getProductCategoryId(string $productId): ?string
    {
        if (!isset($this->productCategoryIds[$productId])) {
            $this->productCategoryIds[$productId] = $this->productRepository->getCategoryIdById($productId);
        }

        return $this->productCategoryIds[$productId];
    }
}
