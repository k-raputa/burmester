<?php declare(strict_types=1);

namespace UdgGlobalE\Converter;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\Order\Transformer\AddressTransformer;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\ParameterBag;
use UdgGlobalE\Exception\InvalidParameterException;

class OrderConverter
{
    /**
     * @var EntityRepositoryInterface
     */
    private $salutationRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $countryStateRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $currencyStateRepository;

    public function __construct(
        EntityRepositoryInterface $salutationRepository,
        EntityRepositoryInterface $countryRepository,
        EntityRepositoryInterface $countryStateRepository,
        EntityRepositoryInterface $currencyStateRepository
    )
    {
        $this->salutationRepository = $salutationRepository;
        $this->countryRepository = $countryRepository;
        $this->countryStateRepository = $countryStateRepository;
        $this->currencyStateRepository = $currencyStateRepository;
    }

    public function extendOrderWithRequest(Cart $cart, array $orderData, ParameterBag $parameterBag, SalesChannelContext $context): array
    {

        if (!$parameterBag->has('CurrencyCode') || $parameterBag->getAlnum('CurrencyCode') !== $context->getCurrency()->getIsoCode()) {
            throw new InvalidParameterException(
                sprintf('Invalid CurrencyCode. (Expected: %s)', $context->getCurrency()->getIsoCode())
            );
        }

        $customerData = $this->getCustomerData($parameterBag);

        foreach (['email', 'firstName', 'lastName', 'company'] as $key) {
            if (array_key_exists($key, $customerData)) {
                $orderData['orderCustomer'][$key] = $customerData[$key];
            }
        }

        if ($this->isAdressChanged(
            AddressTransformer::transform($context->getCustomer()->getActiveBillingAddress()),
            $customerData['billingAddress']
        )) {
            $billingAddress = $customerData['billingAddress'];
            $billingAddress['id'] = Uuid::randomHex();
            $orderData['addresses'] = [$billingAddress];
            $orderData['billingAddressId'] = $billingAddress['id'];
        }

        if ($this->isAdressChanged(
            AddressTransformer::transform($context->getCustomer()->getDefaultShippingAddress()),
            $customerData['shippingAddress']
        )) {

            $shippingAddress = $customerData['shippingAddress'];
            $shippingAddress['id'] = Uuid::randomHex();

            foreach ($orderData['deliveries'] as $deliveriesKey => $deliveryArray) {
                $orderData['deliveries'][$deliveriesKey]['shippingOrderAddress'] = $shippingAddress;
            }
        }

        $orderData['orderNumber'] = $parameterBag->get('OrderId');
        list(, $languageId) = explode('-', $parameterBag->get('CartId'));
        $orderData['languageId'] = $languageId;


        // $orderData['currencyId'] = $this->getCurrencyId($parameterBag->get('InternationalDetails')['CurrencyCode']);
        // @todo update price, shipping cost, shipping method
        // @check quantity and products?

        return $orderData;
    }

    /**
     * @param ParameterBag $parameterBag
     * @return array
     * @throws InvalidParameterException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function getCustomerData(ParameterBag $parameterBag): array
    {

        $data = [
            'accountType' => CustomerEntity::ACCOUNT_TYPE_PRIVATE,
        ];

        if ($parameterBag->has('PrimaryBilling')) {
            foreach (['email' => 'Email', 'firstName' => 'FirstName', 'lastName' => 'LastName', 'company' => 'Company'] as $key => $parambagKey) {
                $value = $parameterBag->get('PrimaryBilling')[$parambagKey];

                if (is_string($value)) {
                    $data[$key] = urldecode($value);
                }
            }
            $data['salutationId'] = $this->getUndefinedSalutationId();

            $data['billingAddress'] = $this->transformRequestAddress($parameterBag->get('PrimaryBilling'));
        }
        if ($parameterBag->has('PrimaryShipping')) {
            $data['shippingAddress'] = $this->transformRequestAddress($parameterBag->get('PrimaryShipping'));
        }
        return $data;
    }

    /**
     * @return string
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function getUndefinedSalutationId(): string
    {

        $criteria = (new Criteria())->setLimit(1);
        $criteria->addFilter(new EqualsFilter('salutationKey', 'not_specified'));

        return $this->salutationRepository->searchIds($criteria, Context::createDefaultContext())->getIds()[0];
    }

    /**
     * @return string
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     * @throws \Exception
     */
    private function getCountryId(string $countryIso3): string
    {

        $criteria = (new Criteria())->setLimit(1);
        $criteria->addFilter(new EqualsFilter('iso3', $countryIso3));

        $countryIds = $this->countryRepository->searchIds($criteria, Context::createDefaultContext())->getIds();

        if (is_array($countryIds) === false || array_key_exists(0, $countryIds) === false) {
            throw new \Exception(sprintf('Could not find country by ISO code "%s"', $countryIso3));
        }

        return $countryIds[0];
    }

    /**
     * @return string
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function getCurrencyId(string $currencyIso3): string
    {

        $criteria = (new Criteria())->setLimit(1);
        $criteria->addFilter(new EqualsFilter('isoCode', $currencyIso3));

        $currencies = $this->currencyStateRepository->searchIds($criteria, Context::createDefaultContext())->getIds();

        if (is_array($currencies) && count($currencies) > 0) {
            return $currencies[0];
        }

        $newCurrencyId = Uuid::randomHex();
        $this->currencyStateRepository->create([[
            'id' => $newCurrencyId,
            'isoCode' => $currencyIso3,
            'shortName' => $currencyIso3,
            'name' => $currencyIso3,
            'symbol' => $currencyIso3,
            'decimalPrecision' => 2,
            'factor' => 1,
        ]], Context::createDefaultContext());

        return $newCurrencyId;
    }

    /**
     * @return string
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    private function getCountryStateId(string $countryIso2, string $shortCode): string
    {

        $criteria = (new Criteria())->setLimit(1);
        $criteria->addFilter(new EqualsFilter('shortCode', $countryIso2 . '-' . $shortCode));

        return $this->countryStateRepository->searchIds($criteria, Context::createDefaultContext())->getIds()[0];
    }

    private function isAdressChanged(array $customerAddress, array $requestAdress): bool
    {

        foreach ($customerAddress as $key => $value) {

            if (in_array($key, ['id'])) {
                continue;
            }

            if (!array_key_exists($key, $customerAddress)) {

                throw new InvalidParameterException(
                    sprintf('Missing address-key. (Expected: %s)', $key)
                );
            }
            if ($customerAddress[$key] !== $requestAdress[$key]) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $address
     * @return array
     * @throws InvalidParameterException
     * @throws \Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException
     */
    public function transformRequestAddress(array $address): array
    {
        // ignore: Fax, Email
        foreach (['FirstName', 'LastName', 'Address1', 'Address2', 'Zip', 'City', 'Phone1', 'CountryCode3'] as $key) {
            if (!array_key_exists($key, $address)) {
                throw new InvalidParameterException(
                    sprintf('Missing key. (Expected: %s)', $key)
                );
            }
        }

        $data = [
            'firstName' => urldecode($address['FirstName']),
            'lastName' => urldecode($address['LastName']),
            'street' => urldecode($address['Address1']),
            'zipcode' => urldecode($address['Zip']),
            'city' => urldecode($address['City']),
            'phoneNumber' => urldecode($address['Phone1']),
            'salutationId' => $this->getUndefinedSalutationId(),
            'countryId' => $this->getCountryId($address['CountryCode3']),
        ];
        if (array_key_exists('Address2', $address) && is_string($address['Address2'])) {
            $data['additionalAddressLine2'] = urldecode($address['Address2']);
        }
        if (array_key_exists('Salutation', $address) && is_string($address['Salutation'])) {
            $data['title'] = urldecode($address['Salutation']);
        }
        if (array_key_exists('Company', $address) && is_string($address['Company'])) {
            $data['company'] = urldecode($address['Company']);
        }
        if (array_key_exists('StateCode', $address) && is_string($address['StateCode'])) {
            $data['countryStateId'] = $this->getCountryStateId($address['CountryCode'], $address['StateCode']);
        }

        return array_filter($data);
    }
}
