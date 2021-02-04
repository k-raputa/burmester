<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1583348160LanguageLabelSwitch extends MigrationStep
{

    public function getCreationTimestamp(): int
    {
        return 1583348160;
    }

    public function update(Connection $connection): void
    {
        $this->changeLanguageLabel($connection, 'EN', 'en-GB');
        $this->changeLanguageLabel($connection, 'DE', 'de-DE');
    }

    private function changeLanguageLabel(Connection $connection, string $languageLabel, string $locale): void
    {
        $query = <<<SQL
            UPDATE `language` SET
                `name` = :languageLabel
             WHERE `locale_id` IN
                (SELECT `id` FROM `locale` WHERE code = :locale);

SQL;

        $connection->executeUpdate($query, [
            'languageLabel' => $languageLabel,
            'locale' => $locale,
        ]);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
