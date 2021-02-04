<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\T02TeaserProductList;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583513618DemoT02TeaserProductList extends MigrationStep
{
    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583513618;
    }

    public function update(Connection $connection): void
    {
        $pageTree = '99.2.14';
        $pageName = '(T02.00): Teaser-Product-List';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        T02TeaserProductList::createSection(
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
