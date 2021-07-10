<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Domain\Discount\AppliedDiscounts;
use App\Dto\Order\Order;
use App\Repository\Customer\CustomerRepository;
use App\Repository\Product\ProductRepository;

final class DiscountCalculator
{
    public function __construct(
        private CustomerRepository $customerRepository,
        private ProductRepository $productRepository,
    ) {
    }

    public function calculateDiscounts(Order $order): AppliedDiscounts
    {
        throw new \RuntimeException('Not yet implemented');
    }
}
