<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Migration\ContentSections;

use Doctrine\DBAL\Connection;

interface ContentSectionInterface
{
    public static function createSection(Connection $connection,  string $cmsPageId, string $cmsSectionId, int $position);
}
