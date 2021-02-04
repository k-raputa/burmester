<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageOverlappingTextLeft;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageOverlappingTextRight;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583773562DemoC02OverlappingImageText extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583773562;
    }

    public function update(Connection $connection): void
    {

        $pageTree = '99.2.19';
        $pageName = '(C02.01): Overlapping-Image & Text';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C02ImageOverlappingTextRight::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            2
        );
        C02ImageOverlappingTextLeft::createSection(
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
