<?php declare(strict_types=1);

namespace UdgGlobalE\Controller;

use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Controller\StorefrontController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UdgGlobalE\Service\CartService;

/**
 * @RouteScope(scopes={"storefront"})
 */
class CheckoutController extends StorefrontController
{

    /**
     * @Route("/checkout/global-e", name="frontend.checkout.global-e.page", options={"seo"="false"}, methods={"GET"})
     */
    public function getCheckoutCartInfo(Request $request, SalesChannelContext $salesChannelContext): Response
    {
        return $this->renderStorefront('@Storefront/storefront/page/checkout/global-e/index.html.twig');
    }
}
