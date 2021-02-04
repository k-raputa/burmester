<?php

namespace UdgBurmesterTheme\Migration\Traits;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;

trait InitCategories
{
    use InitBase;

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createCategories(Connection $connection): void
    {
        $categoryDatas = $this->getDataFromCsv('categories');
        foreach ($categoryDatas as $categoryData) {
            $this->createCategory($connection, $categoryData);
        }
    }

    /**
     * @param Connection $connection
     * @param array $categoryData
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createCategory(Connection $connection, array $categoryData): void
    {

        if (!array_key_exists('de_name', $categoryData) && array_key_exists('en_name', $categoryData)) {
            $categoryData['de_name'] = $categoryData['en_name'];
        }
        if (array_key_exists('level', $categoryData) && empty($categoryData['level'])) {
            $categoryData['level'] = $this->getLevel($categoryData['no']);
        }
        if (!array_key_exists('level', $categoryData)) {
            $categoryData['level'] = $this->getLevel($categoryData['no']);
        }

        $sqls = [];
        $sqls[] = <<<SQL
            REPLACE INTO `category` SET `id`= :categoryId,`version_id`= :versionId, `level`= :level,`active`=1,`child_count`=0,`type`= :type,`created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `category_translation` SET `name`= :en_name, `category_id`= :categoryId,`category_version_id`= :versionId,`language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `category_translation` SET `name`= :de_name, `category_id`= :categoryId,`category_version_id`= :versionId,`language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            UPDATE `category` SET `child_count` = `child_count` + 1 WHERE `id` =  :parentCategoryId;
SQL;
        $sqls[] = <<<SQL
            UPDATE `category` SET `parent_id` = :parentCategoryId, `parent_version_id` = :versionId WHERE `id` =  :categoryId;
SQL;
        $sqls[] = <<<SQL
            UPDATE `category` SET `after_category_version_id` = :versionId WHERE `id` =  :categoryId;
SQL;

        if ($categoryData['level'] == 1) {
            $categoryData['parentCategoryId'] = null;
        } elseif ($categoryData['level'] == 2) {

            $categoryData['parentCategoryId'] = $this->getMainCategoryValue($connection, '`id`');
        } else {
            $categoryData['parentCategoryId'] = base64_decode($this->getBaseName($this->getParentNo($categoryData['no'])));
        }


        $categoryData = array_merge(
            [
                'categoryId' => base64_decode($this->getBaseName($categoryData['no'])),
                'versionId' => Uuid::fromHexToBytes(Defaults::LIVE_VERSION),
                'type' => 'page',
            ],
            $categoryData
        );

        foreach ($sqls as $sql) {
            $connection->executeQuery(
                $sql,
                $categoryData
            );
        }

        $this->updatePathAndBreadcrumb($connection, $categoryData);
    }

    /**
     * @param string $no
     * @return string
     */
    private function getParentNo(string $no): string
    {
        $path = explode('.', $no);
        array_pop($path);
        return implode('.', $path);
    }

    /**
     * @param string $no
     * @return int
     */
    private function getLevel(string $no): int
    {

        return count(explode('.', $no)) + 1;
    }

    /**
     * @param Connection $connection
     * @param string $field
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getMainCategoryValue(Connection $connection, string $field): string
    {
        $sql = <<<SQL
SELECT $field
FROM `category` 
WHERE `level` = 1
ORDER BY auto_increment
SQL;

        return (string)$connection->executeQuery($sql)->fetchColumn();
    }

    /**
     * @param Connection $connection
     * @param array $categoryData
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    private function updatePathAndBreadcrumb(Connection $connection, array $categoryData): void
    {

        $sql = <<<SQL
            
            UPDATE `category_translation` SET `breadcrumb`= :en_breadcrumb  WHERE `category_id` =  :categoryId AND `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB'));
            UPDATE `category_translation` SET `breadcrumb`= :de_breadcrumb  WHERE `category_id` =  :categoryId AND `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE'));
            
            UPDATE `category` SET `path` = :path WHERE `id` =  :categoryId;
SQL;


        if (is_null($categoryData['parentCategoryId'])) {
            $de_breadcrumb = [Uuid::fromBytesToHex($categoryData['categoryId']) => $categoryData['de_name']];
            $en_breadcrumb = [Uuid::fromBytesToHex($categoryData['categoryId']) => $categoryData['en_name']];
        } else {
            $de_language_id = $this->fetchLanguageIdByISO($connection, 'de-DE');
            $de_breadcrumb = $this->getBreadcrumb($connection, $categoryData['parentCategoryId'], $de_language_id);
            $de_breadcrumb[Uuid::fromBytesToHex($categoryData['categoryId'])] = $categoryData['de_name'];

            $en_language_id = $this->fetchLanguageIdByISO($connection, 'en-GB');
            $en_breadcrumb = $this->getBreadcrumb($connection, $categoryData['parentCategoryId'], $en_language_id);
            $en_breadcrumb[Uuid::fromBytesToHex($categoryData['categoryId'])] = $categoryData['en_name'];
        }

        $connection->executeQuery(
            $sql,
            [
                'categoryId' => $categoryData['categoryId'],
                'de_breadcrumb' => json_encode($de_breadcrumb),
                'en_breadcrumb' => json_encode($en_breadcrumb),
                'path' => '|' . implode('|', array_keys($en_breadcrumb)) . '|'
            ]
        );
    }

    /**
     * @param Connection $connection
     * @param string $categoryId
     * @param string $language_id
     * @return array
     */
    private function getBreadcrumb(Connection $connection, string $categoryId, string $language_id): array
    {
        try {

            return json_decode($connection->fetchColumn(
                'SELECT breadcrumb FROM `category_translation` WHERE `category_id` = :category_id AND `language_id` = :language_id',
                [
                    'category_id' => $categoryId,
                    'language_id' => $language_id
                ]
            ), true);
        } catch (DBALException $e) {

            return null;
        }
    }

    private function fetchLanguageIdByISO(Connection $connection, string $isoCode): ?string
    {
        try {
            return (string)$connection->fetchColumn(
                'SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = :locale)',
                ['locale' => $isoCode]
            );
        } catch (DBALException $e) {
            return null;
        }
    }

}
