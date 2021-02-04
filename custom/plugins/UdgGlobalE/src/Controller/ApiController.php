<?php declare(strict_types=1);

namespace UdgGlobalE\Controller;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Framework\Cache\CacheResponseSubscriber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use UdgGlobalE\Checkout\Cart\SalesChannel\OrderPayStatusService;
use UdgGlobalE\Exception\InvalidParameterException;
use UdgGlobalE\Checkout\Cart\SalesChannel\CartService;

/**
 * @RouteScope(scopes={"global-e"})
 */
class ApiController extends AbstractController
{

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var OrderPayStatusService
     */
    private $orderPayStatusService;

    /**
     * @var SystemConfigService
     */
    private $systemConfigService;

    /**
     * ApiController constructor.
     * @param CartService $cartService
     * @param OrderPayStatusService $orderPayStatusService
     * @param SystemConfigService $systemConfigService
     */
    public function __construct(CartService $cartService, OrderPayStatusService $orderPayStatusService, SystemConfigService $systemConfigService)
    {
        $this->cartService = $cartService;
        $this->orderPayStatusService = $orderPayStatusService;
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @param Request $request
     */
    private function validateAclPermissions(Request $request): void
    {

        $allowed = false;
        $merchantGuid = $this->systemConfigService->get('UdgGlobalE.config.MerchantGUID');

        if (Request::METHOD_GET === $request->getMethod()) {
            if ($request->cookies->has(CacheResponseSubscriber::SYSTEM_STATE_COOKIE)) {
                $allowed = $request->cookies->get(CacheResponseSubscriber::SYSTEM_STATE_COOKIE) === CacheResponseSubscriber::STATE_CART_FILLED;
            }
        } else {
            $requestJson = json_decode($request->getContent(), true);

            $allowed = (
                is_array($requestJson)
                && array_key_exists('MerchantGUID', $requestJson)
                && $requestJson['MerchantGUID'] === $merchantGuid
            );
        }

        if (!$allowed) {
            throw new AccessDeniedHttpException('Missing privileges');
        }
    }

    /**
     * @Route("/global-e/api/GetCheckoutCartInfo", name="api.action.global-e.get-checkout-cart-info", methods={"GET", "POST"}, defaults={"XmlHttpRequest"=true})
     */
    public function getCheckoutCartInfo(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        try {
            $this->validateAclPermissions($request);

            if (Request::METHOD_GET === $request->getMethod()) {
                // for GET-request just get the current cart
                $merchantCartToken = $request->get('merchantCartToken');
                if (!is_string($merchantCartToken) || strpos($merchantCartToken, '-') === false) {
                    throw new InvalidParameterException('Invalid merchantCartToken');
                }
                list(, $languageId) = explode('-', $merchantCartToken);
                $cartToken = $salesChannelContext->getToken();
            } else {
                // for other request, use Token from post-data (MerchantGUID was validated in RouteScope)
                $requestJson = json_decode($request->getContent(), true);
                if (is_array($requestJson)
                    && array_key_exists('merchantCartToken', $requestJson)
                ) {
                    $merchantCartToken = $requestJson['merchantCartToken'];
                    if (!is_string($merchantCartToken) || strpos($merchantCartToken, '-') === false) {
                        throw new InvalidParameterException('Invalid merchantCartToken');
                    }
                    list($cartToken, $languageId) = explode('-', $merchantCartToken);
                }
            }
            $cartInfo = $this->cartService->getCurrentCartInfo($cartToken, $languageId, $salesChannelContext);

            return new JsonResponse($cartInfo);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'Success' => false,
                    'Message' => $e->getMessage(),
                ], 400);
        }
    }

    /**
     * @Route("/global-e/api/SendOrderToMerchant", name="api.action.global-e.send-order-to-merchant", methods={"POST"})
     */
    public function getSendOrderToMerchant(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        try {
            $this->validateAclPermissions($request);

            $requestJson = json_decode($request->getContent(), true);
            $parameterBag = new ParameterBag($requestJson);

            $createCartInfo = $this->cartService->createOrderByRequest($parameterBag, $salesChannelContext);

            return new JsonResponse($createCartInfo);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'OrderId' => ($parameterBag->has('OrderId') && is_string($parameterBag->get('OrderId')) ? $parameterBag->get('OrderId') : ''),
                    'Success' => false,
                    'Message' => $e->getMessage(),
                ], 400);
        }
    }

    /**
     * @Route("/global-e/api/PerformOrderPayment", name="api.action.global-e.perform-order-payment", methods={"POST"})
     */
    public function getPerformOrderPayment(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        try {
            $this->validateAclPermissions($request);

            $requestJson = json_decode($request->getContent(), true);
            $parameterBag = new ParameterBag($requestJson);

            $createCartInfo = $this->orderPayStatusService->markOrderAsPayed($parameterBag, $salesChannelContext);

            return new JsonResponse($createCartInfo);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'InternalOrderId' => ($parameterBag->has('MerchantOrderId') && is_string($parameterBag->get('MerchantOrderId')) ? $parameterBag->get('MerchantOrderId') : ''),
                    'OrderId' => ($parameterBag->has('OrderId') && is_string($parameterBag->get('OrderId')) ? $parameterBag->get('OrderId') : ''),
                    'Success' => false,
                    'Message' => $e->getMessage(),
                ], 400);
        }
    }

    /**
     * @Route("/global-e/api/UpdateOrderStatus", name="api.action.global-e.update-order-status", methods={"POST"})
     */
    public function getUpdateOrderStatus(Request $request, SalesChannelContext $salesChannelContext): JsonResponse
    {
        try {
            $this->validateAclPermissions($request);

            $requestJson = json_decode($request->getContent(), true);
            $parameterBag = new ParameterBag($requestJson);

            if ($parameterBag->has('StatusCode') && 'canceled' === $parameterBag->get('StatusCode')) {
                $createCartInfo = $this->orderPayStatusService->markOrderAsPayCanceled($parameterBag, $salesChannelContext);
                return new JsonResponse($createCartInfo);
            }
            throw new InvalidParameterException(sprintf('Unknown StatusCode (Allowed: "canceled")'));

        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'InternalOrderId' => ($parameterBag->has('MerchantOrderId') && is_string($parameterBag->get('MerchantOrderId')) ? $parameterBag->get('MerchantOrderId') : ''),
                    'OrderId' => ($parameterBag->has('OrderId') && is_string($parameterBag->get('OrderId')) ? $parameterBag->get('OrderId') : ''),
                    'Success' => false,
                    'Message' => $e->getMessage(),
                ], 400);
        }
    }


    /**
     * @param string $languageId
     * @param SalesChannelContext $salesChannelContext
     * @return SalesChannelContext
     */
    private function updateSalesChannelWithLanguageFilter(string $languageId, string $cartToken, SalesChannelContext $salesChannelContext): SalesChannelContext
    {

        $languageIdChain = $salesChannelContext->getContext()->getLanguageIdChain();

        if (in_array($languageId, $languageIdChain)) {
            $languageIdChain = [$languageId];

            $context = new Context(
                $salesChannelContext->getContext()->getSource(),
                $salesChannelContext->getContext()->getRuleIds(),
                $salesChannelContext->getContext()->getCurrencyId(),
                $languageIdChain,
                $salesChannelContext->getContext()->getVersionId(),
                $salesChannelContext->getContext()->getCurrencyFactor(),
                $salesChannelContext->getContext()->getCurrencyPrecision(),
                $salesChannelContext->getContext()->considerInheritance(),
                $salesChannelContext->getContext()->getTaxState()
            );

            $salesChannel = $salesChannelContext->getSalesChannel();
            $salesChannel->setLanguageId($languageId);
            $salesChannelContext = new SalesChannelContext(
                $context,
                $cartToken,
                $salesChannel,
                $salesChannelContext->getCurrency(),
                $salesChannelContext->getCurrentCustomerGroup(),
                $salesChannelContext->getFallbackCustomerGroup(),
                $salesChannelContext->getTaxRules(),
                $salesChannelContext->getPaymentMethod(),
                $salesChannelContext->getShippingMethod(),
                $salesChannelContext->getShippingLocation(),
                $salesChannelContext->getCustomer(),
                $salesChannelContext->getRuleIds()
            );
        }

        return $salesChannelContext;
    }

}
