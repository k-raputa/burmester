<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C15BigContentSliderBig;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583269395DemoC151BigContentSliderBig extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583269385;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.13';
        $pageName = '(C15.00): Big-Content-Slider - bigger';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C15BigContentSliderBig::createSection(
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
