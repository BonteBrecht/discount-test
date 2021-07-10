<?php
declare(strict_types=1);

namespace App\Calculator\Discount;

use App\Domain\Discount\AppliedDiscount;
use App\Dto\Order\Order;

interface Discount
{
    /** @return array<AppliedDiscount> */
    public function calculateDiscounts(Order $order): array;
}
