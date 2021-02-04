<?php declare(strict_types=1);

namespace UdgStorefinder\Storefront\Framework\Twig\Extension;

use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use UdgStorefinder\Service\LocationService;

/**
 * Class UdgStorefinderExtension
 * @package UdgStorefinder\Storefront\Framework\Twig\Extension
 */
class UdgStorefinderExtension extends AbstractExtension
{
    /**
     * @var LocationService
     */
    private $locationService;


    /**
     * UdgStorefinderExtension constructor.
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService)
    {
        $this->setLocationService($locationService);
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('udg_storefinder', [$this, 'getStorefinderData']),
        ];
    }

    /**
     * @param SalesChannelContext $context
     * @return array
     */
    public function getStorefinderData(SalesChannelContext $context): array
    {
        return array(
            'entities' => $this->getLocationService()->getStorefinderLocations($context),
            'entitiesForJs' => $this->getLocationService()->getStorefinderLocationsAsJson($context),
        );
    }

    /**
     * @return LocationService
     */
    private function getLocationService(): LocationService
    {
        return $this->locationService;
    }

    /**
     * @param LocationService $locationService
     */
    private function setLocationService(LocationService $locationService): void
    {
        $this->locationService = $locationService;
    }
}
