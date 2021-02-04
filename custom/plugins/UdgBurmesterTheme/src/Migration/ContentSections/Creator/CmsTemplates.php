<?php

namespace UdgBurmesterTheme\Migration\ContentSections\Creator;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Exception;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Uuid\Uuid;
use UdgBurmesterTheme\Migration\ContentSections\C01Intro;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageOverlappingTextLeft;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageOverlappingTextRight;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTableRight;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTextLeft;
use UdgBurmesterTheme\Migration\ContentSections\C02ImageTextRight;
use UdgBurmesterTheme\Migration\ContentSections\C02VideoImageTextRight;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideo;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideoWithPreviewVideo;
use UdgBurmesterTheme\Migration\ContentSections\C03BigVideoWithPreviewVideoAutoplay;
use UdgBurmesterTheme\Migration\ContentSections\C04ShortFacts;
use UdgBurmesterTheme\Migration\ContentSections\C05FactsNumbered;
use UdgBurmesterTheme\Migration\ContentSections\C06Gallery;
use UdgBurmesterTheme\Migration\ContentSections\C10QuoteSlider;
use UdgBurmesterTheme\Migration\ContentSections\C11Accordeon;
use UdgBurmesterTheme\Migration\ContentSections\C12Fulltext;
use UdgBurmesterTheme\Migration\ContentSections\C13Downloads;
use UdgBurmesterTheme\Migration\ContentSections\C14ParallaxLeft;
use UdgBurmesterTheme\Migration\ContentSections\C14ParallaxRight;
use UdgBurmesterTheme\Migration\ContentSections\C15BigContentSliderBig;
use UdgBurmesterTheme\Migration\ContentSections\C15BigContentSliderSmall;
use UdgBurmesterTheme\Migration\ContentSections\ContactForm;
use UdgBurmesterTheme\Migration\ContentSections\NewsletterForm;
use UdgBurmesterTheme\Migration\ContentSections\S01Stage1Image;
use UdgBurmesterTheme\Migration\ContentSections\S01Stage1Video;
use UdgBurmesterTheme\Migration\ContentSections\Storefinder;
use UdgBurmesterTheme\Migration\ContentSections\T02TeaserProductList;
use UdgBurmesterTheme\Migration\ContentSections\T02TeaserProductListIntro;
use UdgBurmesterTheme\Migration\ContentSections\T06TeaserContent;

class CmsTemplates
{

    /**
     * @param Connection $connection
     * @param string $cmsPageName
     * @param string $templateName
     * @throws DBALException
     */
    public static function createSectionsByTemplate(Connection $connection, string $cmsPageName, string $templateName): void
    {

        $templateDefinition = self::getTemplateDefinition($templateName);
        $cmsPageId = self::findCmsPageId($connection, $cmsPageName);
        $cmsBaseId = substr($cmsPageId, 0, 7);

        self::cleanAllSectionsFromPage($connection, $cmsPageId);

        $position = 0;
        foreach ($templateDefinition as $section) {

            $section::createSection(
                $connection,
                $cmsPageId,
                $cmsBaseId,
                $position
            );

            $position++;
        }
    }

    protected static function getTemplateDefinition(string $templateName): array
    {

        switch ($templateName) {
            // home
            case 'TEMP-01-00':
                return [
                    S01Stage1Video::class,
                    C01Intro::class,
                    S01Stage1Image::class,
                    C02ImageTextRight::class,
                    C02ImageTextLeft::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderSmall::class,
                    T06TeaserContent::class,
                    T06TeaserContent::class,
                    C02VideoImageTextRight::class,
                ];
                break;
            // mlp
            case 'TEMP-02-00':
                return [
                    S01Stage1Image::class,
                    C02ImageTextRight::class,
                    C02ImageTextLeft::class,
                    C03BigVideo::class,
                    C04ShortFacts::class,
                    C06Gallery::class,
                    C03BigVideo::class,
                    C02ImageTextRight::class,
                    C02ImageTextLeft::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                ];
                break;
            // PCP - System Overview
            case 'TEMP-03-00':
                return [
                    S01Stage1Image::class,
                    C01Intro::class,
                    T06TeaserContent::class,
                    T06TeaserContent::class,
                    T06TeaserContent::class,
                    S01Stage1Image::class,
                    C10QuoteSlider::class,
                    C02ImageTextLeft::class,
                    C02ImageTextRight::class,
                ];
                break;
            // PCP - Lines Signature
            case 'TEMP-03-01':
                return [
                    S01Stage1Image::class,
                    C01Intro::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                    C06Gallery::class,
                    C02ImageTextLeft::class,
                    C02ImageTextRight::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                    C15BigContentSliderBig::class,
                ];
                break;
            case 'TEMP-04-00':
                // PLP
                return [
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                    S01Stage1Image::class,
                    C10QuoteSlider::class,
                    C02ImageTextLeft::class,
                    C02ImageTextRight::class,
                ];
                break;
            case 'TEMP-05-00':
                // PDP
                return [
                    C06Gallery::class,
                    C10QuoteSlider::class,
                    C02ImageTextLeft::class,
                    C02ImageTextRight::class,
                    C02ImageOverlappingTextLeft::class,
                    C02ImageOverlappingTextRight::class,
                    C11Accordeon::class,
                    C13Downloads::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                ];
                break;
            case 'TEMP-06-00':
                // COP
                return [
                    S01Stage1Image::class,
                    C01Intro::class,
                    C14ParallaxLeft::class,
                    C14ParallaxRight::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderBig::class,
                    C10QuoteSlider::class,
                ];
                break;
            case 'TEMP-07-00':
                // CDP-911
                return [
                    S01Stage1Image::class,
                    C10QuoteSlider::class,
                    C14ParallaxLeft::class,
                    C14ParallaxRight::class,
                    C06Gallery::class,
                    C14ParallaxLeft::class,
                    C14ParallaxRight::class,
                    C15BigContentSliderBig::class,
                ];
                break;
            case 'TEMP-07-01':
                // CDP-Dieter Burmester-Short
                return [
                    S01Stage1Image::class,
                    C10QuoteSlider::class,
                    C15BigContentSliderSmall::class,
                    C14ParallaxLeft::class,
                    C14ParallaxRight::class,
                    C15BigContentSliderSmall::class,
                    C06Gallery::class,
                    C10QuoteSlider::class,
                    T06TeaserContent::class,
                ];
                break;
            case 'TEMP-07-02':
                // CDP-Philosophie
                return [
                    S01Stage1Image::class,
                    C01Intro::class,
                    C12Fulltext::class,
                    C12Fulltext::class,
                    C12Fulltext::class,
                    C12Fulltext::class,
                    C03BigVideo::class,
                    T06TeaserContent::class,
                ];
                break;
            case 'TEMP-07-03':
                // CDP-Technik
                return [
                    S01Stage1Image::class,
                    C01Intro::class,
                    C04ShortFacts::class,
                    C02ImageTextRight::class,
                    C02ImageTextLeft::class,
                    S01Stage1Image::class,
                    C02VideoImageTextRight::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                ];
                break;
            case 'TEMP-09-00':
                return [
                    C01Intro::class,
                    NewsletterForm::class,
                ];
                break;
            case 'TEMP-10-00':
                return [
                    C01Intro::class,
                    ContactForm::class,
                ];
                break;
            case 'TEMP-11-00':
                return [
                    C01Intro::class,
                    Storefinder::class,
                ];
                break;
            case 'TEMP-99-00':
                return [
                    C01Intro::class,
                ];
                break;
            case 'TEMP-999-all':
                return [
                    C01Intro::class,
                    C02ImageOverlappingTextLeft::class,
                    C02ImageOverlappingTextRight::class,
                    C02ImageTableRight::class,
                    C02ImageTextLeft::class,
                    C02ImageTextRight::class,
                    C03BigVideo::class,
                    C03BigVideoWithPreviewVideo::class,
                    C03BigVideoWithPreviewVideoAutoplay::class,
                    C04ShortFacts::class,
                    C05FactsNumbered::class,
                    C06Gallery::class,
                    C10QuoteSlider::class,
                    C11Accordeon::class,
                    C12Fulltext::class,
                    C13Downloads::class,
                    C14ParallaxLeft::class,
                    C14ParallaxRight::class,
                    C15BigContentSliderBig::class,
                    C15BigContentSliderSmall::class,
                    ContactForm::class,
                    NewsletterForm::class,
                    S01Stage1Image::class,
                    S01Stage1Video::class,
                    Storefinder::class,
                    T02TeaserProductListIntro::class,
                    T02TeaserProductList::class,
                    T06TeaserContent::class,
                ];
                break;
            default:
                throw new Exception(sprintf('Missing Templatedefinition for %s', $templateName));
                break;
        }
    }

    protected static function cleanAllSectionsFromPage(Connection $connection, string $cmsPageId): void
    {
        $sqls = [];
        $sqls[] = <<<SQL
            DELETE FROM `cms_section` WHERE `cms_page_id` = :cmsPageId;
SQL;


        $data = [
            'cmsPageId' => $cmsPageId,
        ];

        foreach ($sqls as $sql) {
            $connection->executeQuery(
                $sql,
                $data
            );
        }
    }

    protected static function findCmsPageId(Connection $connection, string $cmsPageName): string
    {
        try {
            $result = $connection->fetchColumn(
                'SELECT `cms_page_id` FROM `cms_page_translation`        
                            WHERE `language_id` IN (SELECT `id` FROM `language` WHERE `locale_id` IN (SELECT `id` FROM `locale` WHERE code = :locale))
                              AND `name` LIKE "' . $cmsPageName . '%"',
                [
                    'locale' => 'en-GB'
                ]
            );
            if (!is_string($result)) {
                throw new Exception(sprintf('Can not find cmsPage for %s', $cmsPageName));
            }
            return (string)$result;
        } catch (DBALException $e) {
            return null;
        }
    }
}
