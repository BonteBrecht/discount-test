<?php
declare(strict_types=1);

namespace App\Repository\Customer;

use App\Domain\MoneyAmount;

final class PdoCustomerRepository implements CustomerRepository
{
    public function __construct(
        private \PDO $db,
    ) {
    }

    public function getRevenueById(string $customerId): ?MoneyAmount
    {
        $stmt = $this->db->query('SELECT customer.revenue FROM customer WHERE customer.id = :id');
        \assert($stmt !== false);
        $stmt->bindParam('id', $customerId);

        /** @var int|false $revenue */
        $revenue = $stmt->fetchColumn();

        return $revenue !== false ? MoneyAmount::fromInt($revenue) : null;
    }
}
