<?php
declare(strict_types=1);

namespace App\Repository\Product;

interface ProductRepository
{
    public function getCategoryIdById(string $productId): ?string;
}
