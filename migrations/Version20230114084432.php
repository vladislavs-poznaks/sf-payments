<?php

declare(strict_types=1);

namespace Migrations;

use App\Models\Loan;
use App\Models\ValueObjects\LoanNumber;
use App\Repositories\DatabaseRepository;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114084432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates loans table';
    }

    public function up(Schema $schema): void
    {
        $loans = $schema->createTable('loans');

        $loans
            ->addColumn('id', Types::STRING)
            ->setLength(36);

        $loans
            ->addColumn('customer_id', Types::STRING)
            ->setLength(36);

        $loans
            ->addColumn('reference', Types::STRING)
            ->setLength(LoanNumber::getMaxLength());

        $loans->addColumn('state', Types::STRING);

        $loans
            ->addColumn('amount_issued', Types::BIGINT)
            ->setUnsigned(true);

        $loans
            ->addColumn('amount_owed', Types::BIGINT)
            ->setUnsigned(true);

        $loans
            ->setPrimaryKey(['id']);
    }

    public function postUp(Schema $schema): void
    {
        // Seed data if local environment
        require_once __DIR__ . '/../bootstrap.php';

        if ($_ENV['APP_ENV'] !== 'development') {
            return;
        }

        $entityManager = new EntityManager(
            (new DatabaseRepository())->getConnection(),
            ORMSetup::createAttributeMetadataConfiguration([__DIR__ . '/../src/Models'])
        );

        $loans = json_decode(file_get_contents(__DIR__ . '/data/loans.json'));

        foreach ($loans as $attributes) {
            $entityManager->persist(Loan::make((array) $attributes));
        }

        $entityManager->flush();
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('loans');
    }
}
