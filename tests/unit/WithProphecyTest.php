<?php

declare(strict_types=1);

namespace Malcaino\MockeryBug\Test\Unit;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class OtherA
{
    public function getFirstNumber(): int
    {
        return 2;
    }
}

class OtherB
{
    public function getSecondNumber(): int
    {
        return 3;
    }
}

class MyOtherService
{
    private OtherA $classA;
    private OtherB $classB;

    public function __construct(OtherA $classA, OtherB $classB)
    {
        $this->classA = $classA;
        $this->classB = $classB;
    }

    public function sumValues(): int
    {
        return $this->classA->getFirstNumber() + $this->classB->getSecondNumber();
    }
}

class WithProphecyTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @dataProvider myDataProvider
     */
    public function testMySuperFunctionality(OtherA $aInstance, OtherB $bInstance, int $expectedResult): void
    {
        $service = new MyOtherService($aInstance, $bInstance);
        $result  = $service->sumValues();

        self::assertEquals($expectedResult, $result);
    }

    public function myDataProvider(): array
    {
        return [
            '1st case' => [
                $this->createAMock(2),
                $this->createBMock(3),
                5,
            ],
            '2nd case' => [
                $this->createAMock(3),
                $this->createBMock(4),
                7,
            ],
        ];
    }

    private function createAMock(int $number): OtherA
    {
        $prophecy = $this->prophesize(OtherA::class);
        $prophecy->getFirstNumber()->willReturn($number)->shouldBeCalledOnce();

        return $prophecy->reveal();
    }

    private function createBMock(int $number): OtherB
    {
        $prophecy = $this->prophesize(OtherB::class);
        $prophecy->getSecondNumber()->willReturn($number)->shouldBeCalledOnce();

        return $prophecy->reveal();
    }
}
