<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C13Downloads;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;


class Migration1583174261DemoC13Downloads extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583174261;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.10';
        $pageName = '(C13.00): Downloads';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C13Downloads::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            1
        );

    }



    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
