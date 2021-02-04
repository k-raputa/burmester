<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\C10QuoteSlider;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1582730894DemoC10QuoteSlider extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1582730894;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.3';
        $pageName = '(C10.00): Quote-Slider';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        C10QuoteSlider::createSection(
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
