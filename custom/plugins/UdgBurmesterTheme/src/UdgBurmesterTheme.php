<?php declare(strict_types=1);

namespace UdgBurmesterTheme;

use Shopware\Core\Framework\Plugin;
use Shopware\Storefront\Framework\ThemeInterface;

/**
 * Class UdgBurmesterTheme
 * @package UdgBurmesterTheme
 */
class UdgBurmesterTheme extends Plugin implements ThemeInterface
{
    /**
     * @return string
     */
    public function getThemeConfigPath(): string
    {
        return 'theme.json';
    }
}
