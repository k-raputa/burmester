<?php declare(strict_types=1);

namespace UdgGlobalE\Subscriber;

use Shopware\Core\Checkout\Cart\SalesChannel\CartService;
use Shopware\Core\PlatformRequest;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CartTokenResponseSubscriber implements EventSubscriberInterface
{

    public const GLOBAL_E_CART_TOKEN_COOKIE = 'gem-cart-token';

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * CartTokenResponseSubscriber constructor.
     * @param CartService $cartService
     */
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['setResponseCartTokenCookie', -1400],
            ],
        ];
    }

    /**
     * @param ResponseEvent $event
     */
    public function setResponseCartTokenCookie(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $request = $event->getRequest();

        $context = $request->attributes->get(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_CONTEXT_OBJECT);

        if (!$context instanceof SalesChannelContext) {
            return;
        }

        $this->updateCartToken($context, $request, $response);
    }

    /**
     * Set cookie for Global-E
     *
     * @param SalesChannelContext $context
     * @param Request $request
     * @param Response $response
     */
    private function updateCartToken(SalesChannelContext $context, Request $request, Response $response): void
    {
        $cookieCartToken = null;
        if ($request->cookies->has(self::GLOBAL_E_CART_TOKEN_COOKIE)) {
            $cookieCartToken = $request->cookies->get(self::GLOBAL_E_CART_TOKEN_COOKIE);
        }

        $cart = $this->cartService->getCart($context->getToken(), $context);

        if ($cart->getLineItems()->count() <= 0) {
            $response->headers->removeCookie(self::GLOBAL_E_CART_TOKEN_COOKIE);
            $response->headers->clearCookie(self::GLOBAL_E_CART_TOKEN_COOKIE);
            return;
        }

        $currentCartToken = $context->getToken() . '-' . $context->getSalesChannel()->getLanguageId();

        if ($cookieCartToken === $currentCartToken) {
            return;
        }

        $response->headers->setCookie(
            Cookie::create(
                self::GLOBAL_E_CART_TOKEN_COOKIE,
                $currentCartToken,
                0,
                '/',
                null,
                null,
                false
            )
        );
    }
}
