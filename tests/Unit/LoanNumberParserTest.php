<?php

namespace Tests\Unit;

use App\Models\ValueObjects\LoanNumber;
use App\Parsers\Exceptions\MissingLoanNumberParserException;
use App\Parsers\Exceptions\MultipleLoanNumbersParserException;
use App\Parsers\LoanNumberParser;
use PHPUnit\Framework\TestCase;

class LoanNumberParserTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_an_exception_if_no_loan_numbers_are_found()
    {
        $this->expectException(MissingLoanNumberParserException::class);

        $parse = new LoanNumberParser();

        $parse('Some random string without valid loan number');
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_multiple_loan_numbers_are_found()
    {
        $this->expectException(MultipleLoanNumbersParserException::class);

        $parse = new LoanNumberParser();

        $parse('One LN12345678 and another LN87654321');
    }

    /**
     * @test
     */
    public function it_returns_a_loan_number_if_provided_correctly()
    {
        $parse = new LoanNumberParser();

        $loanNumber = $parse('Just LN12345678 ....');

        $this->assertInstanceOf(LoanNumber::class, $loanNumber);
        $this->assertEquals('LN12345678', $loanNumber);
    }
}