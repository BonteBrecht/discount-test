<?php
declare(strict_types=1);

namespace App\Tests\Domain;

use App\Domain\MoneyAmount;
use PHPUnit\Framework\TestCase;

final class MoneyAmountTest extends TestCase
{
    /** @test */
    public function it_should_throw_an_exception_when_receiving_non_numeric_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MoneyAmount::fromString('just a test');
    }

    /**
     * @test
     * @dataProvider stringAmountProvider
     */
    public function it_should_be_able_to_convert_strings_to_amounts(string $stringValue, int $amount): void
    {
        self::assertSame($amount, MoneyAmount::fromString($stringValue)->toInt());
    }

    /**
     * @test
     * @dataProvider amountStringProvider
     */
    public function it_should_be_able_to_format_amounts(int $amount, string $stringValue): void
    {
        self::assertSame($stringValue, MoneyAmount::fromInt($amount)->toString());
    }

    /**
     * @test
     * @dataProvider floatAmountProvider
     */
    public function it_should_be_able_to_convert_floats_to_amounts(float $floatValue, int $amount): void
    {
        self::assertSame($amount, MoneyAmount::fromFloat($floatValue)->toInt());
    }

    /**
     * @test
     * @dataProvider amountFloatProvider
     */
    public function it_should_be_able_to_convert_amounts_to_float(int $amount, float $floatValue): void
    {
        self::assertSame($floatValue, MoneyAmount::fromInt($amount)->toFloat());
    }

    public function stringAmountProvider(): array
    {
        return [
            ['3', 3_0000],
            ['0.6', 6000],
            ['1.0', 1_0000],
            ['-5.2', -5_2000],
            ['35.2315', 35_2315],
            ['35.23154', 35_2315],
            ['35.23155', 35_2316],
        ];
    }

    public function amountStringProvider(): array
    {
        return [
            [3_0000, '3.00'],
            [6000, '0.60'],
            [-5_2000, '-5.20'],
            [35_2315, '35.23'],
            [35_2355, '35.24'],
        ];
    }

    public function floatAmountProvider(): array
    {
        return [
            [0.6, 6000],
            [1.0, 1_0000],
            [-5.2, -5_2000],
            [35.2315, 35_2315],
            [35.23154, 35_2315],
            [35.23155, 35_2316],
        ];
    }

    public function amountFloatProvider(): array
    {
        return [
            [6000, 0.6],
            [1_0000, 1.0],
            [-5_2000, -5.2],
            [35_2315, 35.2315],
        ];
    }
}
