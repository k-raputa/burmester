<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C12Fulltext;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1582742681DemoC12Fulltext extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1582742681;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.7';
        $pageName = '(C12.00): Fulltext';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C12Fulltext::createSection(
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
