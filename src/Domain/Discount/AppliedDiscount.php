<?php
declare(strict_types=1);

namespace App\Domain\Discount;

use App\Domain\MoneyAmount;

final class AppliedDiscount
{
    public function __construct(
        private string $name,
        private MoneyAmount $discount,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDiscount(): MoneyAmount
    {
        return $this->discount;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'discount' => $this->discount->toString(),
        ];
    }
}
