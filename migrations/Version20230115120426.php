<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230115120426 extends AbstractMigration
{
    private const DESCRIPTION_LENGTH = 1000;

    public function getDescription(): string
    {
        return 'Creates payment orders table';
    }

    public function up(Schema $schema): void
    {
        $orders = $schema->createTable('payment_orders');

        $orders
            ->addColumn('id', Types::STRING)
            ->setLength(36);

        $orders
            ->addColumn('loan_id', Types::STRING)
            ->setLength(36);

        $orders->addColumn('firstname', Types::STRING);
        $orders->addColumn('lastname', Types::STRING);

        $orders->addColumn('payment_date', Types::DATETIME_MUTABLE);

        $orders
            ->addColumn('amount', Types::BIGINT)
            ->setUnsigned(true);

        $orders
            ->addColumn('description', Types::STRING)
            ->setLength(self::DESCRIPTION_LENGTH);

        $orders
            ->addColumn('ref_id', Types::STRING)
            ->setLength(36)
            ->setNotnull(false);

        $orders
            ->addColumn('status', Types::SMALLINT)
            ->setNotnull(false);

        $orders->setPrimaryKey(['id']);

        $orders->addUniqueConstraint(['ref_id']);

        $orders->addIndex(['payment_date']);
        $orders->addIndex(['ref_id']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('payment_orders');
    }
}
