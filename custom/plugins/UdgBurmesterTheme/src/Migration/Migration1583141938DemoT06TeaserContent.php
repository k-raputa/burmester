<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\T06TeaserContent;

use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1583141938DemoT06TeaserContent extends MigrationStep
{

    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1583141938;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.2.9';
        $pageName = '(T06.00): Teaser-Content';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        T06TeaserContent::createSection(
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
