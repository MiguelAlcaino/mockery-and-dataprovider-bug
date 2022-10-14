<?php

declare(strict_types=1);

namespace Malcaino\MockeryBug\Test\Unit;

use PHPUnit\Framework\TestCase;

class AnotherA
{
    public function getFirstNumber(): int
    {
        return 2;
    }
}

class AnotherB
{
    public function getSecondNumber(): int
    {
        return 3;
    }
}

class MyAnotherService
{
    private AnotherA $classA;
    private AnotherB $classB;

    public function __construct(AnotherA $classA, AnotherB $classB)
    {
        $this->classA = $classA;
        $this->classB = $classB;
    }

    public function sumValues(): int
    {
        return $this->classA->getFirstNumber() + $this->classB->getSecondNumber();
    }
}

class WithPhpUnitTest extends TestCase
{
    /**
     * @dataProvider myDataProvider
     */
    public function testMySuperFunctionality(AnotherA $aInstance, AnotherB $bInstance, int $expectedResult): void
    {
        $service = new MyAnotherService($aInstance, $bInstance);
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

    private function createAMock(int $number): AnotherA
    {
        $mock = $this->createMock(AnotherA::class);
        $mock
            ->expects($this->once())
            ->method('getFirstNumber')
            ->willReturn($number);

        return $mock;
    }

    private function createBMock(int $number): AnotherB
    {
        $prophecy = $this->createMock(AnotherB::class);
        $prophecy
            ->expects($this->once())
            ->method('getSecondNumber')
            ->willReturn($number);

        return $prophecy;
    }
}
