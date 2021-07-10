<?php
declare(strict_types=1);

namespace App\Tests\Util;

use App\Util\MoneyUtil;
use PHPUnit\Framework\TestCase;

final class MoneyUtilTest extends TestCase
{
    /** @test */
    public function it_should_throw_an_exception_when_receiving_non_numeric_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        MoneyUtil::amountFromString('just a test');
    }

    /**
     * @test
     * @dataProvider stringAmountProvider
     */
    public function it_should_be_able_to_convert_strings_to_amounts(string $stringValue, int $amount): void
    {
        self::assertSame($amount, MoneyUtil::amountFromString($stringValue));
    }

    /**
     * @test
     * @dataProvider floatAmountProvider
     */
    public function it_should_be_able_to_convert_floats_to_amounts(float $floatValue, int $amount): void
    {
        self::assertSame($amount, MoneyUtil::amountFromFloat($floatValue));
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
}
