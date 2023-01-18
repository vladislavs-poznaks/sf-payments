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

        $matchesCount = preg_match_all(LoanNumber::getValidationRegex(), $s, $matches);

        if ($matchesCount === 0) {
            throw new MissingLoanNumberParserException("Description must include a correct Loan number");
        }

        if ($matchesCount > 1) {
            throw new MultipleLoanNumbersParserException("Several loan numbers included in description");
        }

        $firstMatch = is_array($matches[0]) ? $matches[0][0] : $matches[0];

        return LoanNumber::make($firstMatch);
    }
}
