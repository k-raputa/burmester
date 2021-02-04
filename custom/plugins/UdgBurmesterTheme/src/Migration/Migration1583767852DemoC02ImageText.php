<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTableRight;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTextLeft;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTextRight;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583767852DemoC02ImageText extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583767852;
    }

    public function update(Connection $connection): void
    {
        $pageTree = '99.2.16';
        $pageName = '(C02.00): Image & Text';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C02ImageTextLeft::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            1
        );

        C02ImageTextRight::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            2
        );

        C02ImageTableRight::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            3
        );
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
