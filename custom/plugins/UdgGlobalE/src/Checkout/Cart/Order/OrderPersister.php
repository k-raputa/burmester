<?php declare(strict_types=1);

namespace UdgGlobalE\Checkout\Cart\Order;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException;
use Shopware\Core\Checkout\Cart\Exception\InvalidCartException;
use Shopware\Core\Checkout\Cart\Order\OrderConversionContext;
use Shopware\Core\Checkout\Cart\Order\OrderConverter;
use Shopware\Core\Checkout\Order\Exception\DeliveryWithoutAddressException;
use Shopware\Core\Checkout\Order\Exception\EmptyCartException;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\ParameterBag;
use UdgGlobalE\Converter\OrderConverter as RequestOrderConverter;

class OrderPersister
{
    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var OrderConverter
     */
    private $converter;

    /**
     * @var RequestOrderConverter
     */
    private $requestOrderConverter;

    public function __construct(
        EntityRepositoryInterface $repository,
        OrderConverter $converter,
        RequestOrderConverter $requestOrderConverter
    ) {
        $this->orderRepository = $repository;
        $this->converter = $converter;
        $this->requestOrderConverter = $requestOrderConverter;
    }

    /**
     * @param Cart $cart
     * @param ParameterBag $parameterBag
     * @param SalesChannelContext $context
     * @return string
     * @throws CustomerNotLoggedInException
     * @throws DeliveryWithoutAddressException
     * @throws EmptyCartException
     * @throws InvalidCartException
     * @throws \UdgGlobalE\Exception\InvalidParameterException
     */
    public function persist(Cart $cart, ParameterBag $parameterBag, SalesChannelContext $context): string
    {
        if ($cart->getErrors()->blockOrder()) {
            throw new InvalidCartException($cart->getErrors());
        }

        if (!$context->getCustomer()) {
            throw new CustomerNotLoggedInException();
        }
        if ($cart->getLineItems()->count() <= 0) {
            throw new EmptyCartException();
        }

        $order = $this->converter->convertToOrder($cart, $context, new OrderConversionContext());
        $order = $this->requestOrderConverter->extendOrderWithRequest($cart, $order, $parameterBag, $context);

        $context->getContext()->scope(Context::SYSTEM_SCOPE, function (Context $context) use ($order): void {
            $this->orderRepository->create([$order], $context);
        });

        return $order['id'];
    }
}
