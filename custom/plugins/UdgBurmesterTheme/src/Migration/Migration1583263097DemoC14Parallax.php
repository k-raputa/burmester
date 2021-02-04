<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C14ParallaxLeft;
use UdgBurmesterTheme\Migration\ContentSections\C14ParallaxRight;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583263097DemoC14Parallax extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583263097;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.11';
        $pageName = '(C14.00): Image / Text parallax';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C14ParallaxRight::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            1
        );

        C14ParallaxLeft::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            2
        );

    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
