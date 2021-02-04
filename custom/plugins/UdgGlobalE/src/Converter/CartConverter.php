<?php declare(strict_types=1);

namespace UdgGlobalE\Converter;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressEntity;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\System\Country\Aggregate\CountryState\CountryStateEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use UdgGlobalE\Helper\SeoProductUrlGenerator;

class CartConverter
{

    /**
     * @var SeoProductUrlGenerator
     */
    private $seoProductUrlGenerator;

    public function __construct(
        SeoProductUrlGenerator $seoProductUrlGenerator
    ) {

        $this->seoProductUrlGenerator = $seoProductUrlGenerator;
    }

    public function getCartHash(Cart $cart, SalesChannelContext $salesChannelContext)
    {
        $hashValues = [];
        foreach ($cart->getLineItems() as $lineItem) {
            $product = null;
            if ($lineItem->hasChildren()) {
                foreach ($lineItem->getChildren() as $childElements) {
                    if ('product' === $childElements->getType()) {
                        $product = $childElements;
                    }
                }
            } else {
                if ('product' === $lineItem->getType()) {
                    $product = $lineItem;
                }
            }

            $hashValues[] = [
                'CartItemId' => $lineItem->getId(),
                'ProductCode' => $product->getPayloadValue('productNumber'),
                'OrderedQuantity' => $product->getQuantity()
            ];
        }

        return md5(serialize($hashValues));
    }

    public function convertCart2GlobalE(Cart $cart, ?CustomerEntity $customer, SalesChannelContext $salesChannelContext): array
    {
        $globaleEResponseJson = [];
        $globaleEResponseJson['productsList'] = $this->getProductList(
            $cart,
            $salesChannelContext
        );
        $globaleEResponseJson['merchantCartHash'] = $this->getCartHash($cart, $salesChannelContext);

        if ($customer instanceof CustomerEntity) {
            if ($customer->getDefaultBillingAddress() instanceof CustomerAddressEntity) {
                $globaleEResponseJson['billingDetails'] = $this->getAddressDetails($customer, $customer->getDefaultBillingAddress());
            }
            if ($customer->getDefaultShippingAddress() instanceof CustomerAddressEntity) {
                $globaleEResponseJson['shippingDetails'] = $this->getAddressDetails($customer, $customer->getDefaultShippingAddress());
            }
        }

        return $globaleEResponseJson;
    }

    private function getProductList(Cart $cart, SalesChannelContext $salesChannelContext): array
    {
        $cartItems = [];
        foreach ($cart->getLineItems() as $lineItem) {
            /** @var LineItem $lineItem */

            $product = null;
            $productOption = null;

            if ($lineItem->hasChildren()) {
                foreach ($lineItem->getChildren() as $childElements) {
                    if ('customized-products-option' === $childElements->getType()) {
                        $productOption = $childElements;
                    } elseif ('product' === $childElements->getType()) {
                        $product = $childElements;
                    }
                }
            } else {
                if ('product' === $lineItem->getType()) {
                    $product = $lineItem;
                }
            }

            /** @var LineItem $product */
            $cartItem = [
                'CartItemId' => $lineItem->getId(),
                'ProductCode' => $product->getPayloadValue('productNumber'),
                'Name' => $product->getLabel(),
                'Description' => $product->getDescription(),
                'IsFixedPrice' => false,
                'OrderedQuantity' => $product->getQuantity(),
                'URL' => $this->seoProductUrlGenerator->getSeoProductUrl($product->getReferencedId(), $salesChannelContext),
                'OriginalSalePrice' => $product->getPrice()->getUnitPrice(),
                'VATRateType' => [
                    'VATRateTypeCode' => 'vat' . $product->getPrice()->getTaxRules()->first()->getTaxRate(),
                    'Rate' => $product->getPrice()->getTaxRules()->first()->getTaxRate(),
                ],
                'ImageURL' => $product->getCover()->getUrl(),
                'ImageHeight' => $product->getCover()->getMetaData()['height'],
                'ImageWidth' => $product->getCover()->getMetaData()['width'],
            ];

            foreach ($product->getPayloadValue('options') as $options) {
                $cartItem['MetaData']['Attributes'][] = [
                    'AttributeKey' => $options['group'],
                    'AttributeValue' => $options['option'],
                ];
            }

            if ($productOption instanceof LineItem) {
                $cartItem['MetaData']['Attributes'][] = [
                    'AttributeKey' => $productOption->getLabel(),
                    'AttributeValue' => $productOption->getPayloadValue('value'),
                ];
            }
            $cartItems[] = $cartItem;
        }

        return $cartItems;
    }

    private function getAddressDetails(CustomerEntity $customer, CustomerAddressEntity $address): array
    {
        $addressData = [
            'UserId' => $customer->getId(),
            'Email' => $customer->getEmail(),
            'Salutation' => $address->getSalutation()->getDisplayName(),
            'FirstName' => $address->getFirstName(),
            'LastName' => $address->getLastName(),
            'Address1' => $address->getStreet(),
            'Address2' => $address->getAdditionalAddressLine1() ."\n". $address->getAdditionalAddressLine2(),
            'City' => $address->getCity(),
            'Zip' => $address->getZipcode(),
            'CountryCode' => $address->getCountry()->getIso3(),
            'Phone1' => $address->getPhoneNumber(),
        ];

        if ($address->getCountryState() instanceof CountryStateEntity) {
            $addressData['StateOrProvice'] = $address->getCountryState()->getName();
        }

        return $addressData;
    }
}
