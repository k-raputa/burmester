<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsProduct;
use UdgBurmesterTheme\Migration\ContentSections\Creator\CmsSection;

class T02TeaserProductListIntro implements ContentSectionInterface
{


    /**
     * @param Connection $connection
     * @param string $cmsPageId
     * @param string $cmsBaseId
     * @param int $position
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public static function createSection(Connection $connection, string $cmsPageId, string $cmsBaseId, int $position = 0)
    {

        $cmsSectionId = CmsSection::createCmsSection(
            $connection,
            [
                'cms_page_id' => $cmsPageId,
                'cms_section_id' => substr($cmsBaseId, 0, 10) . '-' . $position,
                'cms_section_name' => 'T02TeaserProductListIntro',
                'position' => $position,
                'css_class' => 'theme-silver-grey-light no-offset',
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
                    'type' => 'text',
                    'name' => '',
                    'css_class' => 'offset-lg-2 col-lg-8 offset-md-1 col-md-10',
                ],
                'cmsSlots' => [
                    [
                        'cms_slot_id' => $cmsBlockId . '-1',
                        'type' => 'text',
                        'slot' => 'content',
                        'en_config' => '{"content": {"value": "<p class=\\"chapter-default\\"\\talign=\\"center\\">\\n\\tChapter\\n</p>\\n<h1\\talign=\\"center\\">\\n\\tAlways Trustworthy\\n</h1>\\n<p class=\\"text-lg\\"\\talign=\\"center\\">\\n\\tIf somebody\'d said before the flight,\\n\\t\\"Are you going to get carried away looking at the earth from the moon?\\"\\n\\tI would have say, \\"No, no way.\\"\\n\\tBut yet when I first looked back at the earth, standing on the moon,\\n\\t<a href=\\"#\\" class=\\"anchor anchor-default\\">I cried</a>.\\n</p>\\n", "source": "static"}, "verticalAlign": {"value": null, "source": "static"}}',
                        'en_custom_fields' => '{}',
                    ],
                ]
            ]
        );
    }
}
