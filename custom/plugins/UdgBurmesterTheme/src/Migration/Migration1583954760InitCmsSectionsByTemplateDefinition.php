<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsTemplates;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583954760InitCmsSectionsByTemplateDefinition extends MigrationStep
{
    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583954760;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(Connection $connection): void
    {
        $datas = $this->getDataFromCsv('cmspages');
        foreach ($datas as $data) {
            $cmsPageNameStartsWith = $data['no']. ' ';

            if ($data['type'] != 'page') {
                continue;
            }
            if ($data['template'] == '') {
                continue;
            }
var_dump($data);
            CmsTemplates::createSectionsByTemplate($connection, $cmsPageNameStartsWith, $data['template']);
        }

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
