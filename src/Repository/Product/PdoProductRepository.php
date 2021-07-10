<?php
declare(strict_types=1);

namespace App\Repository\Product;

final class PdoProductRepository implements ProductRepository
{
    public function __construct(
        private \PDO $db,
    ) {
    }

    public function getCategoryIdById(string $productId): ?string
    {
        $stmt = $this->db->query('SELECT product.category FROM product WHERE product.id = :id');
        \assert($stmt !== false);
        $stmt->bindParam('id', $productId);

        /** @var string|false $categoryId */
        $categoryId = $stmt->fetchColumn();

        return $categoryId !== false ? $categoryId : null;
    }
}
