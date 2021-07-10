<?php
declare(strict_types=1);

namespace App\Calculator\Discount;

use App\Domain\Discount\AppliedDiscount;
use App\Dto\Order\Order;
use App\Repository\Customer\CustomerRepository;

final class LoyalCustomerDiscount implements Discount
{
    private const NAME = 'loyal_customer';
    private const MIN_REVENUE = 1000_0000;
    private const DISCOUNT = 0.1;

    public function __construct(
        private CustomerRepository $customerRepository,
    ) {
    }

    public function calculateDiscounts(Order $order): array
    {
        $customerRevenue = $this->customerRepository->getRevenueById($order->getCustomerId());
        if ($customerRevenue === null || $customerRevenue->toInt() < self::MIN_REVENUE) {
            return [];
        }

        return [
            new AppliedDiscount(self::NAME, $order->getTotal()->multiply(self::DISCOUNT)),
        ];
    }
}
