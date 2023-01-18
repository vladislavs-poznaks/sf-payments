<?php

namespace App\Commands;

use App\Repositories\Payments\PaymentsRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentsReportCommand extends Command implements InvalidDateInterface
{
    public const INPUT_DATE_FORMAT = 'Y-m-d';

    protected static $defaultName = "sf-payments:payments-report";

    protected static $defaultDescription = "Get payments report by date";

    public function __construct(
        private PaymentsRepository $repository
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption(
            name: 'date',
            mode: InputOption::VALUE_REQUIRED,
            description: 'Date for report as YYYY-MM-DD'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $date = Carbon::createFromFormat(static::INPUT_DATE_FORMAT, $input->getOption('date'));
        } catch (InvalidFormatException) {
            $output->write('Invalid date format, must use YYYY-MM-DD', true);

            return self::INVALID_DATE;
        }

        $payments = $this->repository->getByDate($date);

        foreach ($payments->all() as $payment) {
            $output->write(implode(' | ', [
                "RefId: {$payment->getRefId()}",
                "Amount: {$payment->getAmount()}",
                "Status: {$payment->getStatus()->toString()}",
            ]), true);
        }

        return Command::SUCCESS;
    }
}
