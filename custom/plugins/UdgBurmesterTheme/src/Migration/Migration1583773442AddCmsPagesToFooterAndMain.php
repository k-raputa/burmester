<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583773442AddCmsPagesToFooterAndMain extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583773440;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     */
    public function update(Connection $connection): void
    {
        foreach (['footer', 'main'] as $fileType) {
            $categoryDatas = $this->getDataFromCsv($fileType);
            foreach ($categoryDatas as $data) {
                if ('page' == $data['type']) {
                    $data['de_name'] = $data['no'] . ' ' . $data['de_name'];
                    $data['en_name'] = $data['no'] . ' ' . $data['en_name'];
                    $this->createCmsPage(
                        $connection,
                        $data
                    );
                }
            }
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
