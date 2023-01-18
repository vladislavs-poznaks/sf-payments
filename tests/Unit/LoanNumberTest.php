<?php

namespace Tests\Unit;

use App\Models\ValueObjects\LoanNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoanNumberTest extends TestCase
{
    /**
     * @test
     * @dataProvider getLoanNumbers
     */
    public function it_validates_loan_numbers($loanNumber, $expected)
    {
        if ($expected === true) {
            $this->assertTrue(LoanNumber::isValid($loanNumber));
        }

        if ($expected === false) {
            $this->assertFalse(LoanNumber::isValid($loanNumber));
        }
    }

    /**
     * @test
     * @dataProvider getInvalidLoanNumbers
     */
    public function it_throws_an_exception_if_created_with_invalid_number($invalidLoanNumber)
    {
        $this->expectException(InvalidArgumentException::class);

        new LoanNumber($invalidLoanNumber);
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_string()
    {
        $loanNumber = 'LN12345678';

        $this->assertEquals($loanNumber, (string) (new LoanNumber($loanNumber)));
    }

    /**
     * @test
     */
    public function it_can_be_created_with_a_factory_method()
    {
        $loanNumber = 'LN12345678';

        $this->assertInstanceOf(LoanNumber::class, LoanNumber::make($loanNumber));
    }

    public function getLoanNumbers()
    {
        return array_merge(
            $this->getInvalidLoanNumbers(),
            $this->getValidLoanNumbers()
        );
    }

    public function getInvalidLoanNumbers()
    {
        return [
            ['LN', false],
            ['ln12345678', false],
            ['LL12345678', false],
            ['NL12345678', false],
            ['NL1234567', false],
            ['NL123456789', false],
            ['NL123AA678', false],
        ];
    }

    public function getValidLoanNumbers()
    {
        return [
            ['LN12345678', true],
        ];
    }
}
