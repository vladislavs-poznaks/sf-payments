<?php

namespace App\Commands;

use App\Dtos\Payments\PaymentDTO;
use App\Models\Payment;
use App\Models\ValueObjects\Amount;
use App\Models\ValueObjects\Exceptions\NegativeAmountException;
use Carbon\Carbon;
use App\Repositories\Payments\PaymentsRepository;
use Carbon\Exceptions\InvalidFormatException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PaymentsImportCommand extends Command implements NegativeAmountInterface, InvalidDateInterface
{
    public const INPUT_DATE_FORMAT = 'YmdHms';

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

        $file = fopen($path, 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            try {
                $dto = $this->createPaymentDto($data);
            } catch (InvalidFormatException) {
                return self::INVALID_DATE;
            } catch (NegativeAmountException) {
                return self::NEGATIVE_AMOUNT;
            }

            if (!is_null($this->repository->getByRefId($dto->getRefId()))) {
                return Command::FAILURE;
            }

            $payment = Payment::make($dto);

            $this->repository->persistAndSync($payment);
        }

        fclose($file);

        return Command::SUCCESS;
    }

    private function createPaymentDto(array $data): PaymentDTO
    {
        $paymentDate = Carbon::createFromFormat(static::INPUT_DATE_FORMAT, $data[0]);

        $amount = Amount::make(ceil($data[3] * 100));

        $refId = Uuid::fromString($data[6]);

        $firstName = $data[1];
        $lastName = $data[2];

        $description = $data[5];

        return new PaymentDTO(
            firstName: $firstName,
            lastName: $lastName,
            description: $description,
            amount: $amount,
            paymentDate: $paymentDate,
            refId: $refId
        );
    }
}