<?php declare(strict_types=1);

namespace UdgGlobalE\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\DefaultPayment;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;

use UdgBurmesterTheme\Migration\Traits\InitCategories;

class Migration1590001995PaymentMethod extends MigrationStep
{

    public function getCreationTimestamp(): int
    {
        return 1590001995;
    }

    public function update(Connection $connection): void
    {
        $paymentMethodId = $this->createPaymentMethod($connection);
        $this->setPaymentMethodToSalesChannel($connection, $paymentMethodId);
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createPaymentMethod(Connection $connection): string
    {
        $queries = [];
        $queries[] = <<<SQL
            UPDATE `payment_method` SET
                `active` = 0;
SQL;
        $queries[] = <<<SQL
            REPLACE INTO `payment_method` SET 
                `id` = :paymentMethodId, 
                `handler_identifier` = :paymentHandler, 
                `position` = 1, 
                `active` = 1, 
                `created_at` = NOW(); 
SQL;
        $queries[] = <<<SQL
            REPLACE INTO `payment_method_translation` SET 
                `payment_method_id` = :paymentMethodId,
                `language_id` = (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), 
                `name` = 'Global-e',
                `created_at` = NOW(); 
SQL;
        $queries[] = <<<SQL
            REPLACE INTO `payment_method_translation` SET 
                `payment_method_id` = :paymentMethodId,
                `language_id` = (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), 
                `name` = 'Global-e',
                `created_at` = NOW(); 
SQL;

        $paymentMethodId = Uuid::randomHex();
        foreach ($queries as $query) {
            $connection->executeUpdate($query, [
                'paymentMethodId' => Uuid::fromHexToBytes($paymentMethodId),
                'paymentHandler' => DefaultPayment::class
            ]);
        }

        return $paymentMethodId;
    }

    private function setPaymentMethodToSalesChannel(Connection $connection, string $paymentMethodId): void
    {
        $query = <<<SQL
            UPDATE `sales_channel` SET 
                `payment_method_id` = :paymentMethodId,
                `payment_method_ids` = :paymentMethodIds
             WHERE `id` IN    
                (SELECT `sales_channel_id` FROM `sales_channel_translation` WHERE name = 'Storefront');
                
SQL;

        $connection->executeUpdate($query, [
            'paymentMethodId' => Uuid::fromHexToBytes($paymentMethodId),
            'paymentMethodIds' => '["'.$paymentMethodId.'"]',
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
