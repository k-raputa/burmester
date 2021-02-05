<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Migration\MigrationStep;
use Shopware\Core\Framework\Uuid\Uuid;
use Swag\CustomizedProducts\Template\Aggregate\TemplateOption\Type\FileUpload;
use Swag\CustomizedProducts\Template\Aggregate\TemplateOption\Type\ImageUpload;

class Migration1595737095FileUploadOperators extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1583737095;
    }

    public function update(Connection $connection): void
    {
        $languageIds = $this->getLanguageIdLocaleMapping($connection);

        foreach ($this->getOperatorSet() as $operator) {
            $operatorId = Uuid::randomBytes();
            $translations = $operator['translations'];
            unset($operator['translations']);

            $connection->insert(
                'swag_customized_products_template_exclusion_operator',
                \array_merge(
                    [
                        'id' => $operatorId,
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ],
                    $operator
                )
            );

            $translationsWritten = false;
            foreach ($translations as $locale => $label) {
                $currentLanguageId = $languageIds[$locale];
                if ($currentLanguageId === null) {
                    continue;
                }

                $connection->insert(
                    'swag_customized_products_template_exclusion_operator_translation',
                    [
                        'swag_customized_products_template_exclusion_operator_id' => $operatorId,
                        'language_id' => $currentLanguageId,
                        'label' => $label,
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ]
                );

                $translationsWritten = true;
            }

            if ($translationsWritten === true) {
                continue;
            }

            // If no translations where written, write the english translations to the default language as fallback
            foreach ($translations as $locale => $label) {
                if ($locale !== 'en-GB') {
                    continue;
                }

                $connection->insert(
                    'swag_customized_products_template_exclusion_operator_translation',
                    [
                        'swag_customized_products_template_exclusion_operator_id' => $operatorId,
                        'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
                        'label' => $label,
                        'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
                    ]
                );
            }
        }
    }

    public function updateDestructive(Connection $connection): void
    {
    }

    private function getOperatorSet(): array
    {
        return [
            $this->buildOperator('!X', FileUpload::NAME, 'Nicht hochgeladen', 'Not uploaded'),
            $this->buildOperator('X', FileUpload::NAME, 'Hochgeladen', 'Uploaded'),
            $this->buildOperator('!X', ImageUpload::NAME, 'Nicht hochgeladen', 'Not uploaded'),
            $this->buildOperator('X', ImageUpload::NAME, 'Hochgeladen', 'Uploaded'),
        ];
    }

    private function buildOperator(string $operator, string $optionType, string $deDeLabel, string $enGbLabel): array
    {
        return [
            'operator' => $operator,
            'template_option_type' => $optionType,
            'translations' => [
                'de-DE' => $deDeLabel,
                'en-GB' => $enGbLabel,
            ],
        ];
    }

    private function getLanguageIdLocaleMapping(Connection $connection): array
    {
        $query = <<<SQL
SELECT `locale`.`code`, `language`.`id`
FROM `language`
INNER JOIN `locale` on `language`.`locale_id` = `locale`.`id`
WHERE `locale`.`code` IN ('en-GB', 'de-DE');
SQL;

        return $connection->executeQuery(
            $query
        )->fetchAll(\PDO::FETCH_KEY_PAIR);
    }
}
