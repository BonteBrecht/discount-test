<?php
declare(strict_types=1);

namespace App\Domain;

final class MoneyAmount
{
    private function __construct(
        private int $amount,
    ) {
    }

    public static function fromString(string $amount): self
    {
        if (!\is_numeric($amount)) {
            throw new \InvalidArgumentException(\sprintf('Price amounts should be numeric, received "%s"', $amount));
        }
        return self::fromFloat((float)$amount);
    }

    public static function fromFloat(float $amount): self
    {
        return new self((int)\round($amount * 1_0000));
    }

    public static function fromInt(int $amount): self
    {
        return new self($amount);
    }

    public function add(MoneyAmount $other): self
    {
        return MoneyAmount::fromInt($other->toInt() + $this->amount);
    }

    public function multiply(float $multiplier): self
    {
        return MoneyAmount::fromFloat($this->toFloat() * $multiplier);
    }

    public function toInt(): int
    {
        return $this->amount;
    }

    public function toString(): string
    {
        return \NumberFormatter::create('en_US', \NumberFormatter::PATTERN_DECIMAL, '#0.00')
            ->format($this->toFloat());
    }

    public function toFloat(): float
    {
        return (float)$this->amount / 1_0000.0;
    }
}
