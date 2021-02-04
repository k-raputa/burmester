<?php declare(strict_types=1);

namespace UdgGlobalE\Routing;

use Shopware\Core\Framework\Routing\AbstractRouteScope;
use Shopware\Core\Framework\Routing\SalesChannelContextRouteScopeDependant;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpFoundation\Request;

class UdgGlobalERouteScope extends AbstractRouteScope implements SalesChannelContextRouteScopeDependant
{
    public const ID = 'global-e';

    /**
     * @var string[]
     */
    protected $allowedPaths = ['global-e'];

    /**
     * @var SystemConfigService
     */
    private $systemConfig;

    /**
     * @param SystemConfigService $systemConfig
     */
    public function setSystemConfigService(SystemConfigService $systemConfig): void {
        $this->systemConfig = $systemConfig;
    }

    public function isAllowed(Request $request): bool
    {
        return true;
    }

    public function getId(): string
    {
        return self::ID;
    }
}
