<?php

namespace App\Commands;

use App\Models\Payment;
use App\Repositories\Payments\PaymentsRepository;
use Carbon\Exceptions\InvalidFormatException;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentsImportCommand extends Command
{
    protected static $defaultName = "sf-payments:payments-import";

    protected static $defaultDescription = "Import payments from CSV";

    public function __construct(
        private PaymentsRepository $repository
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addOption(
            name: 'file',
            mode: InputOption::VALUE_REQUIRED,
            description: 'File path'
        );

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = __DIR__ . '/../../imports/' . $input->getOption('file');

        if (!file_exists($path)) {
            $output->write('Invalid file path', true);
            return Command::FAILURE;
        }

        $records = $this->getParsedRecords($path);

        foreach ($records as $key => $attributes) {
            if ($key === 0) {
                continue;
            }

            [
                $paymentDate, $firstname, $lastname, $amount, $nationalSecurityNumber, $description, $refId
            ] = $attributes;

            try {
                $payment = Payment::make([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'paymentDate' => $paymentDate,
                    'amount' => $amount,
                    'description' => $description,
                    'refId' => $refId
                ]);

                $this->repository->persist($payment);
            } catch (InvalidFormatException|InvalidArgumentException $e) {
                $output->writeln($e->getMessage());
            }
        }

        $this->repository->getEntityManager()->flush();

        return Command::SUCCESS;
    }

    private function getParsedRecords(string $path): array
    {
        $file = fopen($path, 'r');

        $records = [];
        while (($data = fgetcsv($file)) !== FALSE) {
            $records[] = $data;
        }

        fclose($file);

        return $records;
    }
}