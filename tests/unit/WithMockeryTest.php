<?php

declare(strict_types=1);

namespace Malcaino\MockeryBug\Test\Unit;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class A
{
    public function getFirstNumber(): int
    {
        return 2;
    }
}

class B
{
    public function getSecondNumber(): int
    {
        return 3;
    }
}

class MyService
{
    private A $classA;
    private B $classB;

    public function __construct(A $classA, B $classB)
    {
        $this->classA = $classA;
        $this->classB = $classB;
    }

    public function sumValues(): int
    {
        return $this->classA->getFirstNumber() + $this->classB->getSecondNumber();
    }
}

class WithMockeryTest extends MockeryTestCase
{
    /**
     * @dataProvider myDataProvider
     */
    public function testMySuperFunctionality(A $aInstance, B $bInstance, int $expectedResult): void
    {
        $service = new MyService($aInstance, $bInstance);
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

    private function createAMock(int $number): A
    {
        $mock = \Mockery::mock(A::class);
        $mock->allows('getFirstNumber')->andReturn($number)->once();

        return $mock;
    }

    private function createBMock(int $number): B
    {
        $mock = \Mockery::mock(B::class);
        $mock->allows('getSecondNumber')->andReturn($number)->once();

        return $mock;
    }
}
