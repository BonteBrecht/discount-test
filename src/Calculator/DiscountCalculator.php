<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Discount\Discount;
use App\Domain\Discount\AppliedDiscounts;
use App\Dto\Order\Order;

final class DiscountCalculator
{
    public function __construct(
        /** @var array<Discount> */
        private array $discounts,
    ) {
    }

    public function calculateDiscounts(Order $order): AppliedDiscounts
    {
        $appliedDiscounts = \array_map(
            static fn (Discount $discount): array => $discount->calculateDiscounts($order),
            $this->discounts,
        );

        return new AppliedDiscounts($appliedDiscounts === [] ? [] : \array_merge(... $appliedDiscounts));
    }
}
