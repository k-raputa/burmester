<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;
use UdgBurmesterTheme\Migration\Traits\InitCategories;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1581602590DemoColorSwitchHeader extends MigrationStep
{


    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1581602590;
    }

    /**
     * @param Connection $connection
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.1';
        $pageName = 'Color Switch Header';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
            ]
        );

        $cmsBaseId = substr($pageTree. 'ColorSwitchHeader', 0, 12);
        $cmsSectionId = CmsSection::createCmsSection(
            $connection,
            [
                'cms_page_id' => $cmsPageId,
                'cms_section_id' => $cmsBaseId,
            ]
        );

        $cmsBlockId = $cmsSectionId . '-1';
        CmsSection::createCmsBlockWithElements(
            $connection,
            [
                'cmsBlock' => [
                    'cms_section_id' => $cmsSectionId,
                    'cms_block_id' => $cmsBlockId,
                    'position' => 1,
                    'type' => 'category-navigation',
                    'name' => '',
                    'css_class' => 'offset-lg-2 col-lg-8 offset-md-1 col-md-10',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId.'-1',
                        'type' => 'category-navigation',
                        'slot' => 'content',
                        'en_config' => '{}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );

        $position = 1;
        $colors = [
            'theme-cool-copper-dark',
            'theme-cool-copper-medium',
            'theme-cool-copper-light',
            'theme-copper-green-dark',
            'theme-copper-green-medium',
            'theme-copper-green-light',
            'theme-signal-red-dark',
            'theme-signal-red-medium',
            'theme-signal-red-light',
            'theme-silver-grey-dark',
            'theme-silver-grey-medium',
            'theme-silver-grey-light',
            'theme-taube-dark',
            'theme-taube-medium',
            'theme-taube-light',
            'theme-warm-copper-dark',
            'theme-warm-copper-medium',
            'theme-warm-copper-light',
            'theme-white',
        ];
        foreach ($colors as $color) {

            $this->createCategory(
                $connection,
                [
                    'no' => $pageTree . '.' . $position,
                    'en_name' => 'DEMO_' . $pageName . '_' . $color,
                ]
            );

            $cmsPageId = $this->createCmsPage(
                $connection,
                [
                    'no' => $pageTree . '.' . $position,
                    'en_name' => 'DEMO_' . $pageName . '_' . $color,
                    'en_custom_fields' => '{"udgextendedpagetemplate_css": "'.$color.'"}',
                ]
            );

            $position++;
        }
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
