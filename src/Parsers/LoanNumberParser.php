<?php

namespace App\Parsers;

use App\Models\ValueObjects\LoanNumber;
use App\Parsers\Exceptions\MissingLoanNumberParserException;
use App\Parsers\Exceptions\MultipleLoanNumbersParserException;

class LoanNumberParser
{
    public function __invoke(string $s): LoanNumber
    {
        $matches = [];
        $matchesCount = preg_match(LoanNumber::getValidationRegex(), $s, $matches);

        if ($matchesCount === 0) {
            throw new MissingLoanNumberParserException("Description must include a correct Loan number");
        }

        if ($matchesCount > 1) {
            throw new MultipleLoanNumbersParserException("Several loan numbers included in description");
        }

        return LoanNumber::make($matches[0]);
    }
}
