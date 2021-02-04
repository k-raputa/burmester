<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\S01Stage1Image;
use UdgBurmesterTheme\Migration\ContentSections\S01Stage1Video;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583745577DemoS01Stage1 extends MigrationStep
{
    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583745577;
    }

    public function update(Connection $connection): void
    {
        $pageTree = '99.2.15';
        $pageName = '(S01.00): Stage01';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        S01Stage1Image::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            1
        );

        S01Stage1Video::createSection(
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
