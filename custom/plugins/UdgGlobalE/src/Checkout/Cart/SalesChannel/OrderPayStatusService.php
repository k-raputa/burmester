<?php declare(strict_types=1);

namespace UdgGlobalE\Checkout\Cart\SalesChannel;

use Shopware\Core\Checkout\Cart\Exception\OrderNotFoundException;
use Shopware\Core\Checkout\Cart\Exception\OrderTransactionNotFoundException;
use Shopware\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStateHandler;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\ParameterBag;
use UdgGlobalE\Exception\InvalidParameterException;

class OrderPayStatusService
{

    /**
     * @var OrderTransactionStateHandler
     */
    private $orderTransactionStateHelper;

    /**
     * @var EntityRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $orderTransactionRepository;

    /**
     * OrderPayStatusService constructor.
     * @param OrderTransactionStateHandler $orderTransactionStateHelper
     * @param EntityRepositoryInterface $orderRepository
     * @param EntityRepositoryInterface $orderTransactionRepository
     */
    public function __construct(
        OrderTransactionStateHandler $orderTransactionStateHelper,
        EntityRepositoryInterface $orderRepository,
        EntityRepositoryInterface $orderTransactionRepository
    )
    {
        $this->orderTransactionStateHelper = $orderTransactionStateHelper;
        $this->orderRepository = $orderRepository;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * @param ParameterBag $parameterBag
     * @param SalesChannelContext $context
     * @return array
     * @throws InvalidParameterException
     * @throws OrderNotFoundException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\System\StateMachine\Exception\IllegalTransitionException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidEntityIdException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidStateFieldException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineNotFoundException
     */
    public function markOrderAsPayed(ParameterBag $parameterBag, SalesChannelContext $context): array
    {

        return $this->changePayStatus($parameterBag, 'pay', $context);
    }

    /**
     * @param ParameterBag $parameterBag
     * @param SalesChannelContext $context
     * @return array
     * @throws InvalidParameterException
     * @throws OrderNotFoundException
     * @throws OrderTransactionNotFoundException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Shopware\Core\System\StateMachine\Exception\IllegalTransitionException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidEntityIdException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineInvalidStateFieldException
     * @throws \Shopware\Core\System\StateMachine\Exception\StateMachineNotFoundException
     */
    public function markOrderAsPayCanceled(ParameterBag $parameterBag, SalesChannelContext $context): array
    {

        return $this->changePayStatus($parameterBag, 'cancel', $context);
    }

    private function changePayStatus(ParameterBag $parameterBag, string $transactionSate, SalesChannelContext $context): array
    {
        if (!$parameterBag->has('OrderId') || !is_string($parameterBag->get('OrderId'))) {
            throw new InvalidParameterException('Invalid OrderId');
        }
        if (!$parameterBag->has('MerchantOrderId') || !is_string($parameterBag->get('MerchantOrderId'))) {
            throw new InvalidParameterException('Invalid MerchantOrderId');
        }

        $orderNumber = $parameterBag->get('OrderId');
        $orderId = $parameterBag->get('MerchantOrderId');

        $order = $this->getOrder($orderId, $context);
        if ($orderNumber !== $order->getOrderNumber()) {
            throw new OrderNotFoundException($orderId);
        }
        $transactionId = $this->getOrderTransaction($orderId, $context);


        $this->orderTransactionStateHelper->$transactionSate($transactionId, $context->getContext());

        return [
            'InternalOrderId' => $order->getId(),
            'OrderId' => $order->getOrderNumber(),
            'Success' => true,
        ];
    }

    /**
     * @param string $orderId
     * @param SalesChannelContext $context
     * @return OrderEntity
     * @throws OrderNotFoundException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function getOrder(string $orderId, SalesChannelContext $context): OrderEntity
    {

        $criteria = new Criteria([$orderId]);

        $order = $this->orderRepository->search($criteria, $context->getContext())->get($orderId);

        if ($order === null) {
            throw new OrderNotFoundException($orderId);
        }

        return $order;
    }

    /**
     * @param string $orderId
     * @param SalesChannelContext $context
     * @return string
     * @throws OrderTransactionNotFoundException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function getOrderTransaction(string $orderId, SalesChannelContext $context): string
    {

        $criteria = (new Criteria())->addFilter(new EqualsFilter('orderId', $orderId));
        $orderTransaction = $this->orderTransactionRepository->search($criteria, $context->getContext())->first();

        if ($orderTransaction === null) {
            throw new OrderTransactionNotFoundException($orderId);
        }

        return $orderTransaction->getId();
    }
}
