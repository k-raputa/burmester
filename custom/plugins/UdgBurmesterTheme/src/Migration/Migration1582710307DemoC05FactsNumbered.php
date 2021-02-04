<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C05FactsNumbered;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1582710307DemoC05FactsNumbered extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1582710307;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.2';
        $pageName = '(C05.00): Facts-Numbered';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C05FactsNumbered::createSection(
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
