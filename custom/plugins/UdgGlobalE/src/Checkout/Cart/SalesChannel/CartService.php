<?php declare(strict_types=1);

namespace UdgGlobalE\Checkout\Cart\SalesChannel;

use Doctrine\DBAL\Connection;
use Shopware\Core\Checkout\Cart\CartPersister;
use Shopware\Core\Checkout\Cart\CartPersisterInterface;
use Shopware\Core\Checkout\Cart\Event\CheckoutOrderPlacedEvent;
use Shopware\Core\Checkout\Cart\Exception\CartTokenNotFoundException;
use Shopware\Core\Checkout\Cart\Exception\InvalidCartException;
use Shopware\Core\Checkout\Cart\Exception\OrderNotFoundException;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountRegistrationService;
use Shopware\Core\Checkout\Customer\SalesChannel\AccountService;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\DataBag\DataBag;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextService;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\Checkout\Cart\SalesChannel\CartService as ShopwareCartService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use UdgGlobalE\Checkout\Cart\Order\OrderPersister;
use UdgGlobalE\Converter\CartConverter;
use UdgGlobalE\Converter\OrderConverter;
use UdgGlobalE\Exception\InvalidParameterException;


class CartService
{

    /**
     * @var ShopwareCartService
     */
    private $cartService;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityRepositoryInterface
     */
    private $customerRepository;

    /**
     * @var SalesChannelContextFactory
     */
    private $salesChannelContextFactory;


    /**
     * @var AccountService
     */
    private $accountService;

    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var CartConverter
     */
    private $cartConverter;

    /**
     * @var CartPersister
     */
    private $cartPersister;

    /**
     * @var OrderConverter
     */
    private $orderConverter;

    /**
     * @var OrderPersister
     */
    private $orderPersister;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * CartService constructor.
     * @param ShopwareCartService $cartService
     * @param Connection $connection
     * @param EntityRepositoryInterface $customerRepository
     * @param SeoUrlPlaceholderHandlerInterface $seoUrlReplacer
     */
    public function __construct(
        ShopwareCartService $cartService,
        Connection $connection,
        EntityRepositoryInterface $customerRepository,
        AccountRegistrationService $accountRegistrationService,
        AccountService $accountService,
        SalesChannelContextFactory $salesChannelContextFactory,
        EntityRepositoryInterface $orderRepository,
        CartConverter $cartConverter,
        CartPersisterInterface $cartPersister,
        OrderConverter $orderConverter,
        OrderPersister $orderPersister,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->cartService = $cartService;
        $this->connection = $connection;
        $this->customerRepository = $customerRepository;
        $this->orderRepository = $orderRepository;

        $this->accountRegistrationService = $accountRegistrationService;
        $this->accountService = $accountService;
        $this->salesChannelContextFactory = $salesChannelContextFactory;

        $this->cartConverter = $cartConverter;
        $this->cartPersister = $cartPersister;
        $this->orderConverter = $orderConverter;
        $this->orderPersister = $orderPersister;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param string $cartToken
     * @param string $languageId
     * @param SalesChannelContext $salesChannelContext
     * @return array
     * @throws CartTokenNotFoundException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    public function getCurrentCartInfo(string $cartToken, string $languageId, SalesChannelContext $salesChannelContext): array
    {
        $newSalesChannelContext = $this->createSalesChannelContext(
            $cartToken,
            null,
            $languageId,
            null,
            $salesChannelContext
        );

        $cart = $this->cartService->getCart($cartToken, $newSalesChannelContext);
        $customer = $this->getCartCustomer($cartToken, $newSalesChannelContext);

        return $this->cartConverter->convertCart2GlobalE($cart, $customer, $newSalesChannelContext);
    }

    /**
     * @param string $cartToken
     * @param SalesChannelContext $salesChannelContext
     * @return CustomerEntity|null
     * @throws CartTokenNotFoundException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidException
     * @throws \Shopware\Core\Framework\Uuid\Exception\InvalidUuidLengthException
     */
    private function getCartCustomer(string $cartToken, SalesChannelContext $salesChannelContext): ?CustomerEntity
    {
        $customerId = $this->connection->fetchColumn(
            'SELECT `cart`.`customer_id` FROM cart WHERE `token` = :token',
            ['token' => $cartToken]
        );

        if ($customerId === false) {
            throw new CartTokenNotFoundException($cartToken);
        }

        if (is_null($customerId)) {
            return null;
        }

        $customerId = Uuid::fromBytesToHex((string)$customerId);
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('customer.id', $customerId));
        $criteria->addAssociation('defaultBillingAddress.salutation');
        $criteria->addAssociation('defaultBillingAddress.country');
        $criteria->addAssociation('defaultBillingAddress.countryState');
        $criteria->addAssociation('defaultShippingAddress.salutation');
        $criteria->addAssociation('defaultShippingAddress.country');
        $criteria->addAssociation('defaultShippingAddress.countryState');

        $result = $this->customerRepository->search($criteria, $salesChannelContext->getContext());
        return $result->first();
    }

    /**
     * @param ParameterBag $parameterBag
     * @param SalesChannelContext $salesChannelContext
     * @return array
     * @throws CartTokenNotFoundException
     * @throws InvalidCartException
     * @throws InvalidParameterException
     * @throws OrderNotFoundException
     * @throws \Shopware\Core\Checkout\Cart\Exception\CustomerNotLoggedInException
     * @throws \Shopware\Core\Checkout\Customer\Exception\BadCredentialsException
     * @throws \Shopware\Core\Checkout\Order\Exception\DeliveryWithoutAddressException
     * @throws \Shopware\Core\Checkout\Order\Exception\EmptyCartException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function createOrderByRequest(ParameterBag $parameterBag, SalesChannelContext $salesChannelContext): array
    {
        if (!$parameterBag->has('CartId') || !is_string($parameterBag->get('CartId')) || strpos($parameterBag->get('CartId'), '-') === false) {
            throw new InvalidParameterException('Invalid CartId');
        }

        list($cartToken, $languageId) = explode('-', $parameterBag->get('CartId'));

        $newSalesChannelContext = $this->createSalesChannelContext(
            $cartToken,
            null,
            $languageId,
            null,
            $salesChannelContext
        );
        $cart = $this->cartService->getCart($cartToken, $newSalesChannelContext);

        if (0 === $cart->getLineItems()->count()) {
            throw new InvalidParameterException('No filled cart found for token.');
        }

        $cartHash = $this->cartConverter->getCartHash($cart, $salesChannelContext);

        if (!$parameterBag->has('CartHash') || $parameterBag->getAlnum('CartHash') !== $cartHash) {
            throw new InvalidParameterException(
                sprintf('Invalid CartHash. (Expected: %s)', $cartHash)
            );
        }
        if ($parameterBag->has('AllowMailsFromMerchant') && $parameterBag->getBoolean('AllowMailsFromMerchant')) {
            // add newsletter recipient
            // @later?
        }

        if ($this->existsOrderWithOrderNumber($parameterBag->get('OrderId'), $salesChannelContext)) {
           throw new InvalidParameterException('OrderId already exists');
        }

        $cartCustomer = $this->getCartCustomer($cartToken, $salesChannelContext);
        if (!$cartCustomer instanceof CustomerEntity) {
            // create guest customer
            $custerData = $this->orderConverter->getCustomerData($parameterBag);
            $customerId = $this->accountRegistrationService->register(new DataBag($custerData), true, $salesChannelContext);
            $loginContextToken = $this->accountService->login($custerData['email'], $salesChannelContext, true);
        } else {
            $loginContextToken = $this->accountService->login($cartCustomer->getEmail(), $salesChannelContext, $cartCustomer->getGuest());
            $customerId = $cartCustomer->getId();
        }

        $newSalesChannelContext = $this->createSalesChannelContext(
            $loginContextToken,
            $customerId,
            $salesChannelContext->getContext()->getLanguageId(),
            $this->getPaymentMethodId($salesChannelContext),
            $salesChannelContext
        );

        $calculatedCart = $this->cartService->recalculate($cart, $newSalesChannelContext);
        $orderId = $this->orderPersister->persist($calculatedCart, $parameterBag, $newSalesChannelContext);

        $orderEntity = $this->getOrderById($orderId, $newSalesChannelContext);

        if ($parameterBag->has('SendConfirmation') && $parameterBag->getBoolean('SendConfirmation')) {
            $orderPlacedEvent = new CheckoutOrderPlacedEvent(
                $newSalesChannelContext->getContext(),
                $orderEntity,
                $newSalesChannelContext->getSalesChannel()->getId()
            );

            $this->eventDispatcher->dispatch($orderPlacedEvent);
        }

        $this->cartPersister->delete($cartToken, $newSalesChannelContext);

        return [
            'InternalOrderId' => $orderEntity->getId(),
            'OrderId' => $orderEntity->getOrderNumber(),
            'Success' => true,
        ];
    }

    /**
     * @param SalesChannelContext $salesChannelContext
     * @return string
     */
    private function getPaymentMethodId(SalesChannelContext $salesChannelContext): string
    {
        return $salesChannelContext->getSalesChannel()->getPaymentMethodId();
    }

    /**
     * Since the guest customer was logged in, the context changed in the system,
     * but this doesn't effect the context given as parameter.
     * Because of that, a new context for the following operations is created
     */
    private function createSalesChannelContext(string $newToken, ?string $customerId, ?string $languageId, ?string $paymentMethodId, SalesChannelContext $context): SalesChannelContext
    {
        $options = [
            SalesChannelContextService::CUSTOMER_ID => $customerId,
            SalesChannelContextService::LANGUAGE_ID => $languageId,
        ];
        if (!is_null($paymentMethodId)) {
            $options[SalesChannelContextService::PAYMENT_METHOD_ID] = $paymentMethodId;
        }


        $salesChannelContext = $this->salesChannelContextFactory->create(
            $newToken,
            $context->getSalesChannel()->getId(),
            $options
        );

        $salesChannelContext->setRuleIds($context->getRuleIds());

        return $salesChannelContext;
    }

    /**
     * @throws OrderNotFoundException
     */
    private function getOrderById(string $orderId, SalesChannelContext $context): OrderEntity
    {
        $criteria = new Criteria([$orderId]);
        $criteria
            ->addAssociation('lineItems.payload')
            ->addAssociation('deliveries.shippingCosts')
            ->addAssociation('deliveries.shippingMethod')
            ->addAssociation('deliveries.shippingOrderAddress.country')
            ->addAssociation('cartPrice.calculatedTaxes')
            ->addAssociation('transactions.paymentMethod')
            ->addAssociation('currency')
            ->addAssociation('addresses.country');

        $order = $this->orderRepository->search($criteria, $context->getContext())->get($orderId);

        if ($order === null) {
            throw new OrderNotFoundException($orderId);
        }

        return $order;
    }

    /**
     * @param string $orderNumber
     * @param SalesChannelContext $context
     * @return bool
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function existsOrderWithOrderNumber(string $orderNumber, SalesChannelContext $context): bool
    {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('orderNumber', $orderNumber));

        $order = $this->orderRepository->search($criteria, $context->getContext())->first();

        if ($order === null) {
            return false;
        }

        return true;
    }

}
