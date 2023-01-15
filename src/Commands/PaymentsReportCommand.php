<?php

namespace App\Commands;

use App\Repositories\PaymentsDatabaseRepository;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentsReportCommand extends Command
{
    public const INVALID_DATE = 3;

    protected static $defaultName = "sf-payments:payments-report";

    protected static $defaultDescription = "Get payments report by date";

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
            $date = Carbon::createFromFormat('Y-m-d', $input->getOption('date'));
        } catch (InvalidFormatException) {
            $output->write('Invalid date format, must use YYYY-MM-DD', true);
            return PaymentsReportCommand::INVALID_DATE;
        }

        $repository = new PaymentsDatabaseRepository();

        $payments = $repository->getByDate($date);

        foreach ($payments as $payment) {
            // TODO : Implement formatter
            $output->write(implode(' | ', $payment->toArray()), true);
        }

        return Command::SUCCESS;
    }
}