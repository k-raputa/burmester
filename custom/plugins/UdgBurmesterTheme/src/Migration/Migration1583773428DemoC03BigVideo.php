<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideo;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideoWithPreviewVideo;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideoWithPreviewVideoAutoplay;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583773428DemoC03BigVideo extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583773428;
    }

    public function update(Connection $connection): void
    {
        $pageTree = '99.2.17';
        $pageName = '(C03.00): Big Video';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C03BigVideo::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            1
        );
        C03BigVideoWithPreviewVideo::createSection(
            $connection,
            $cmsPageId,
            preg_replace('/Migration\d*/', '', $this->getBaseClassname()),
            2
        );
        C03BigVideoWithPreviewVideoAutoplay::createSection(
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
