<?php declare(strict_types=1);

namespace UdgStorefinder\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use UdgStorefinder\Service\LocationService;

/**
 * @RouteScope(scopes={"storefront"})
 */
class UdgStorefinderController extends StorefrontController
{
    /**
     * @Route("/udg-storefinder/index", name="udg.storefinder.index")
     */
    public function index(SalesChannelContext $context)
    {
        $searchResult = $this->getLocationService()->getStorefinderLocationsAsJson($context);

        return new JsonResponse($searchResult, 200, array(), true);
    }

    /**
     * @Route("/udg-storefinder/search", name="udg.storefinder.search", defaults={"csrf_protected"=false}, methods={"GET", "POST"})
     */
    public function search(SalesChannelContext $context, Request $request)
    {
        $searchResult = $this->getLocationService()->getStorefinderLocationsBySearchAsJson($context, $request);

        return new JsonResponse($searchResult, 200, array(), true);
    }

    /**
     * @Route("/udg-storefinder/location/{id}", name="udg.storefinder.location", defaults={"csrf_protected"=false}, methods={"GET", "POST"})
     */
    public function location(SalesChannelContext $context, Request $request, string $id)
    {
        $searchResult = $this->getLocationService()->getStorefinderLocationsByIdAsJson($context, $id);

        return new JsonResponse($searchResult, 200, array(), true);
    }


    /**
     * @return LocationService
     */
    private function getLocationService(): LocationService
    {
        /** @var LocationService $service */
        $service = $this->get('udg.storefinder.location');

        return $service;
    }
}
