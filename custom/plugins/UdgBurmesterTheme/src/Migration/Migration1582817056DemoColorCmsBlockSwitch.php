<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Shopware\Core\Framework\Migration\MigrationStep;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;
use UdgBurmesterTheme\Migration\Traits\InitCmsPage;

class Migration1582817056DemoColorCmsBlockSwitch extends MigrationStep
{
    use InitCmsPage;

    public function getCreationTimestamp(): int
    {
        return 1582817056;
    }

    /**
     * @param Connection $connection
     * @throws DBALException
     */
    public function update(Connection $connection): void
    {
        $pageTree = '99.3';
        $pageName = 'Color Switch for CMS Sections';

        $cmsPageId = $this->createNewCmsPage(
            $connection,
            $pageTree,
            [
                'en_name' => 'DEMO_' . $pageName,
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
        foreach ($colors as $cssClass) {

            $cmsBaseId = substr('DemoColorSwitch', 0, 9);
            $cmsBaseId .=  '-' .$position;


            $cmsSectionId = CmsSection::createCmsSection(
                $connection,
                [
                    'cms_page_id' => $cmsPageId,
                    'cms_section_id' => $cmsBaseId,
                    'css_class' => $cssClass,
                ]
            );

            $cmsBlockId = $cmsSectionId . '-1';
            CmsSection::createCmsBlockWithElements(
                $connection,
                [
                    'cmsBlock' => [
                        'cms_section_id' => $cmsSectionId,
                        'cms_block_id' => $cmsBlockId,
                        'position' => $position,
                        'type' => 'text',
                        'name' => '',
                        'css_class' => 'offset-lg-2 col-lg-8 offset-md-1 col-md-10',
                    ],
                    'cmsSlots' => [
                        [
                            'cms_slot_id' => $cmsBlockId.'-1',
                            'type' => 'text',
                            'slot' => 'content',
                            'en_config' => '{"content": {"value": "<p>\\n'.$cssClass.'\\n</p>\\n\\n<p class=\\"h1\\">\\n\\tZauber der Musik. Bewahrer und als Mittler von Kunst und Kultur – Art For The Ear. Die innovativen Technologien ebenso wie die hohe Qualität der einzelnen Bauteile und der Verarbeitung verspricht ein außergewöhnliches Erlebnis und dient alleine einem Ziel: Der Entfaltung des Zaubers der Musik.\\n</p>", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                            'en_custom_fields' => '{}',
                        ],
                    ]
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
