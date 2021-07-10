<?php
declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\MoneyAmount;

final class AppliedDiscounts implements \JsonSerializable
{
    public function __construct(
        /** @var array<AppliedDiscount> */
        private array $discounts,
    ) {
    }

    public function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function getTotal(): MoneyAmount
    {
        $totalDiscount = \array_reduce(
            $this->discounts,
            static fn (MoneyAmount $total, AppliedDiscount $discount): MoneyAmount => $total->add($discount->getDiscount()),
            MoneyAmount::fromInt(0),
        );
        \assert($totalDiscount instanceof MoneyAmount);

        return $totalDiscount;
    }

    public function jsonSerialize(): array
    {
        return [
            'discounts' => \array_map(
                static fn (AppliedDiscount $discount): array => $discount->toArray(),
                $this->discounts,
            ),
            'total' => $this->getTotal()->toString(),
        ];
    }
}
