<?php
declare(strict_types=1);

namespace App\Repository\Customer;

use App\Command\Infrastructure\LoadFixturesCommand;
use App\Domain\MoneyAmount;

final class TestCustomerRepository implements CustomerRepository
{
    public function getRevenueById(string $customerId): ?MoneyAmount
    {
        foreach (LoadFixturesCommand::CUSTOMERS as ['id' => $fixtureId, 'revenue' => $revenue]) {
            if ($fixtureId === $customerId) {
                return MoneyAmount::fromString($revenue);
            }
        }

        return null;
    }
}
