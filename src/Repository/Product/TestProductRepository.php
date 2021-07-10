<?php
declare(strict_types=1);

namespace App\Repository\Product;

use App\Command\Infrastructure\LoadFixturesCommand;

final class TestProductRepository implements ProductRepository
{
    public function getCategoryIdById(string $productId): ?string
    {
        foreach (LoadFixturesCommand::PRODUCTS as ['id' => $fixtureId, 'category' => $categoryId]) {
            if ($fixtureId === $productId) {
                return $categoryId;
            }
        }

        return null;
    }
}
