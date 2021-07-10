<?php
declare(strict_types=1);

namespace App\Dto\Order;

use App\Domain\MoneyAmount;
use Assert\Assert;

final class Order
{
    public function __construct(
        private string $id,
        private string $customerId,
        /** @var array<OrderItem> */
        private array $orderItems,
        private MoneyAmount $total,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        Assert::lazy()
            ->that($data['id'] ?? null, 'id')->string()
            ->that($data['customer-id'] ?? null, 'customer-id')->string()
            ->that($data['items'] ?? [], 'items')->isArray()
            ->that($data['total'] ?? null, 'total')->numeric()
            ->verifyNow();

        return new self(
            $data['id'],
            $data['customer-id'],
            \array_map(
                static fn (array $orderItemData): OrderItem => OrderItem::createFromArray($orderItemData),
                $data['items'] ?? [],
            ),
            MoneyAmount::fromString($data['total']),
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    /** @return array<OrderItem> */
    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function getTotal(): MoneyAmount
    {
        return $this->total;
    }
}
