<?php
declare(strict_types=1);

namespace App\Repository\Customer;

use App\Domain\MoneyAmount;

interface CustomerRepository
{
    public function getRevenueById(string $customerId): ?MoneyAmount;
}
