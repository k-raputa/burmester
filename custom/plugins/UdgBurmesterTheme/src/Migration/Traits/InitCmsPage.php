<?php

namespace UdgBurmesterTheme\Migration\Traits;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Uuid\Uuid;

trait InitCmsPage
{

    use InitCategories;

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createCmsPages(Connection $connection): void
    {
        $categoryDatas = $this->getDataFromCsv('cms_page');
        foreach ($categoryDatas as $categoryData) {
            $this->createCmsPage($connection, $categoryData);
        }
    }

    /**
     * @param Connection $connection
     * @param array $data
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function createCmsPage(Connection $connection, array $data): string
    {
        if (!array_key_exists('de_name', $data) && array_key_exists('en_name', $data)) {
            $data['de_name'] = $data['en_name'];
        }
        if (array_key_exists('custom_fields', $data)) {
            $data['en_custom_fields'] = $data['custom_fields'];
            $data['de_custom_fields'] = $data['custom_fields'];
        }

        $sqls = [];
        $sqls[] = <<<SQL
            REPLACE INTO `cms_page` SET `id`=:cmsPageId, `type`= :type, `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `cms_page_translation` SET `name`= :en_name, `custom_fields`= :en_custom_fields, `cms_page_id`=:cmsPageId, `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'en-GB')), `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            REPLACE INTO `cms_page_translation` SET `name`= :de_name, `custom_fields`= :de_custom_fields, `cms_page_id`=:cmsPageId, `language_id`= (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = 'de-DE')), `created_at` = NOW();
SQL;
        $sqls[] = <<<SQL
            UPDATE `category` SET `cms_page_id`=:cmsPageId, `updated_at` = NOW() WHERE `id`=FROM_BASE64(:cmsPageId_base64);   
SQL;

        $cmsPageId = substr(
            $data['no'] . '_' . substr(preg_replace('/Migration\d*/', '', $this->getBaseClassname()), 0, 16),
            0,
            16
        );

        $data = array_merge(
            [
                'type' => 'landingpage',
                'en_custom_fields' => '{}',
                'de_custom_fields' => '{}',
                'cmsPageId_base64' => $this->getBaseName($data['no']),
                'cmsPageId' => $cmsPageId,

            ],
            $data
        );

        foreach ($sqls as $sql) {
            $connection->executeQuery(
                $sql,
                $data
            );
        }

        return $cmsPageId;
    }

    /**
     * @param Connection $connection
     * @param string $pageTree
     * @param array $data
     * @return string
     * @throws DBALException
     */
    public function createNewCmsPage(Connection $connection, string $pageTree, array $data): string
    {

        $this->createCategory(
            $connection,
            array_merge(
                [
                    'no' => $pageTree,
                ],
                $data
            )
        );

        return $this->createCmsPage(
            $connection,
            array_merge(
                [
                    'no' => $pageTree,
                ],
                $data
            )
        );
    }

}
