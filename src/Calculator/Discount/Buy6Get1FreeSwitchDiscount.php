<?php
declare(strict_types=1);

namespace App\Calculator\Discount;

use App\Domain\Discount\AppliedDiscount;
use App\Dto\Order\Order;
use App\Dto\Order\OrderItem;
use App\Repository\Product\ProductRepository;

final class Buy6Get1FreeSwitchDiscount implements Discount
{
    private const NAME = 'buy_6_get_1_free_switch';
    private const SWITCH_ID = '2';
    private const FREE_PER = 6;

    /** @var array<string, string|null> */
    private array $productCategoryIds = [];

    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    public function calculateDiscounts(Order $order): array
    {
        $appliedDiscounts = \array_map(
            fn (OrderItem $orderItem): array => $this->calculateDiscountsForOrderItem($orderItem),
            $order->getOrderItems(),
        );

        return $appliedDiscounts === [] ? [] : \array_merge(... $appliedDiscounts);
    }

    /** @return array<AppliedDiscount> */
    private function calculateDiscountsForOrderItem(OrderItem $orderItem): array
    {
        if ($this->getProductCategoryId($orderItem->getProductId()) !== self::SWITCH_ID) {
            return [];
        }
        $freeProducts = (int)($orderItem->getQuantity() / self::FREE_PER);

        return \array_fill(
            0,
            $freeProducts,
            new AppliedDiscount(self::NAME, $orderItem->getUnitPriceAmount()),
        );
    }

    private function getProductCategoryId(string $productId): ?string
    {
        if (!isset($this->productCategoryIds[$productId])) {
            $this->productCategoryIds[$productId] = $this->productRepository->getCategoryIdById($productId);
        }

        return $this->productCategoryIds[$productId];
    }
}
