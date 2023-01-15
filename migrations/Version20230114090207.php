<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230114090207 extends AbstractMigration
{
    private const DESCRIPTION_LENGTH = 1000;

    public function getDescription(): string
    {
        return 'Creates payments table';
    }

    public function up(Schema $schema): void
    {
        $payments = $schema->createTable('payments');

        $payments
            ->addColumn('id', Types::STRING)
            ->setLength(36);

        $payments
            ->addColumn('loan_id', Types::STRING)
            ->setLength(36)
            ->setNotnull(false);

        $payments->addColumn('firstname', Types::STRING);
        $payments->addColumn('lastname', Types::STRING);

        $payments->addColumn('payment_date', Types::DATETIME_MUTABLE);

        $payments
            ->addColumn('amount', Types::BIGINT)
            ->setUnsigned(true);

        $payments
            ->addColumn('description', Types::STRING)
            ->setLength(self::DESCRIPTION_LENGTH);

        $payments
            ->addColumn('ref_id', Types::STRING)
            ->setLength(36);

        $payments
            ->addColumn('status', Types::SMALLINT)
            ->setNotnull(false);

        $payments->setPrimaryKey(['id']);

        $payments->addUniqueConstraint(['ref_id']);

        $payments->addIndex(['payment_date']);
        $payments->addIndex(['ref_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('payments');
    }
}
