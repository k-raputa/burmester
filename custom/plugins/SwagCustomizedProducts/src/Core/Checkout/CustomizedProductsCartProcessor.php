<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Core\Checkout;

use Shopware\Core\Checkout\Cart\Cart;
use Shopware\Core\Checkout\Cart\CartBehavior;
use Shopware\Core\Checkout\Cart\CartProcessorInterface;
use Shopware\Core\Checkout\Cart\Exception\MissingLineItemPriceException;
use Shopware\Core\Checkout\Cart\LineItem\CartDataCollection;
use Shopware\Core\Checkout\Cart\LineItem\LineItem;
use Shopware\Core\Checkout\Cart\LineItem\LineItemCollection;
use Shopware\Core\Checkout\Cart\Price\PercentagePriceCalculator;
use Shopware\Core\Checkout\Cart\Price\QuantityPriceCalculator;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Price\Struct\PercentagePriceDefinition;
use Shopware\Core\Checkout\Cart\Price\Struct\PriceCollection;
use Shopware\Core\Checkout\Cart\Price\Struct\QuantityPriceDefinition;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Swag\CustomizedProducts\Core\Checkout\Cart\Error\SwagCustomizedProductsNotAvailableError;
use Swag\CustomizedProducts\Template\Exception\NoProductException;

class CustomizedProductsCartProcessor implements CartProcessorInterface
{
    /**
     * @var QuantityPriceCalculator
     */
    private $quantityPriceCalculator;

    /**
     * @var PercentagePriceCalculator
     */
    private $percentagePriceCalculator;

    public function __construct(
        QuantityPriceCalculator $quantityPriceCalculator,
        PercentagePriceCalculator $percentagePriceCalculator
    ) {
        $this->quantityPriceCalculator = $quantityPriceCalculator;
        $this->percentagePriceCalculator = $percentagePriceCalculator;
    }

    public function process(
        CartDataCollection $data,
        Cart $original,
        Cart $toCalculate,
        SalesChannelContext $context,
        CartBehavior $behavior
    ): void {
        $customizedProductsLineItems = $original->getLineItems()->filterType(CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_TEMPLATE_LINE_ITEM_TYPE);

        if ($customizedProductsLineItems->count() === 0) {
            return;
        }

        $this->groupLineItemsByConfigurationHash($customizedProductsLineItems, $toCalculate);

        foreach ($customizedProductsLineItems as $customizedProductsLineItem) {
            $referencedId = $customizedProductsLineItem->getReferencedId();
            if ($referencedId === null) {
                $toCalculate->addErrors(
                    new SwagCustomizedProductsNotAvailableError($customizedProductsLineItem->getId())
                );
                continue;
            }

            $this->calculateProduct(
                $customizedProductsLineItem->getChildren()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE),
                $context,
                $referencedId
            );

            $this->calculatePrices(
                $customizedProductsLineItem->getChildren()->filterType(CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_OPTION_LINE_ITEM_TYPE),
                $customizedProductsLineItem->getChildren()->filterType(LineItem::PRODUCT_LINE_ITEM_TYPE),
                $context
            );

            $priceCollection = new PriceCollection($this->getPrices($customizedProductsLineItem));

            $customizedProductsLineItem->setPrice(
                $priceCollection->sum()
            );

            $toCalculate->add($customizedProductsLineItem);
        }
    }

    private function calculateProduct(LineItemCollection $products, SalesChannelContext $context, string $templateId): void
    {
        $customizedProduct = $products->first();
        if ($customizedProduct === null) {
            throw new NoProductException($templateId);
        }

        /** @var QuantityPriceDefinition|null $priceDefinition */
        $priceDefinition = $customizedProduct->getPriceDefinition();

        if ($priceDefinition === null) {
            throw new MissingLineItemPriceException($customizedProduct->getId());
        }

        $customizedProduct->setPrice(
            $this->quantityPriceCalculator->calculate($priceDefinition, $context)
        );
    }

    private function calculatePrices(LineItemCollection $optionLineItems, LineItemCollection $products, SalesChannelContext $context): void
    {
        foreach ($optionLineItems as $optionLineItem) {
            if ($optionLineItem->hasChildren()) {
                $this->calculatePrices(
                    $optionLineItem->getChildren()->filterType(CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_OPTION_VALUE_LINE_ITEM_TYPE),
                    $products,
                    $context
                );
            }

            $priceDefinition = $optionLineItem->getPriceDefinition();
            if ($priceDefinition === null) {
                continue;
            }

            switch (\get_class($priceDefinition)) {
                case QuantityPriceDefinition::class:
                    $price = $this->quantityPriceCalculator->calculate($priceDefinition, $context);
                    break;
                case PercentagePriceDefinition::class:
                    $price = $this->percentagePriceCalculator->calculate(
                        $priceDefinition->getPercentage(),
                        $this->getPercentagePrices($products, $optionLineItem, $context),
                        $context
                    );
                    break;
                default:
                    throw new MissingLineItemPriceException($optionLineItem->getId());
            }

            $optionLineItem->setPrice($price);
        }
    }

    /**
     * @return array<CalculatedPrice|null>
     */
    private function getPrices(LineItem $lineItem): array
    {
        $prices = [];

        foreach ($lineItem->getChildren() as $childLineItem) {
            if ($childLineItem->hasChildren()) {
                foreach ($this->getPrices($childLineItem) as $price) {
                    $prices[] = $price;
                }
            }

            if (!$childLineItem->getPrice() instanceof CalculatedPrice) {
                continue;
            }

            $prices[] = $childLineItem->getPrice();
        }

        return $prices;
    }

    private function groupLineItemsByConfigurationHash(LineItemCollection $customizedProductsLineItems, Cart $toCalculate): void
    {
        $hashLineItemMap = [];
        foreach ($customizedProductsLineItems as $lineItem) {
            if (!$lineItem->hasPayloadValue(CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_TEMPLATE_LINE_ITEM_CONFIGURATION_HASH)) {
                $customizedProductsLineItems->remove($lineItem->getId());
                $toCalculate->addErrors(new SwagCustomizedProductsNotAvailableError($lineItem->getId()));
                continue;
            }

            $key = $lineItem->getPayloadValue(CustomizedProductsCartDataCollector::CUSTOMIZED_PRODUCTS_TEMPLATE_LINE_ITEM_CONFIGURATION_HASH);

            $hashLineItemMap[$key][] = $lineItem;
        }

        foreach ($hashLineItemMap as $lineItems) {
            if (\count($lineItems) <= 1) {
                continue;
            }

            $firstLineItem = \array_pop($lineItems);

            $finalQuantity = $firstLineItem->getQuantity();
            foreach ($lineItems as $lineItem) {
                $finalQuantity += $lineItem->getQuantity();
                $customizedProductsLineItems->remove($lineItem->getId());
            }

            $firstLineItem->setStackable(true);
            $firstLineItem->setQuantity($finalQuantity);
            $firstLineItem->setStackable(false);
        }
    }

    private function getPercentagePrices(LineItemCollection $products, LineItem $optionLineItem, SalesChannelContext $context): PriceCollection
    {
        $prices = $products->getPrices();

        if (!$optionLineItem->getPayloadValue('isOneTimeSurcharge')) {
            return $prices;
        }

        $unitPrices = [];
        foreach ($prices as $price) {
            $unitPriceDefinition = new QuantityPriceDefinition(
                $price->getUnitPrice(),
                $price->getTaxRules(),
                $context->getCurrency()->getDecimalPrecision(),
                1,
                true
            );

            $unitPrices[] = $this->quantityPriceCalculator->calculate($unitPriceDefinition, $context);
        }

        return new PriceCollection($unitPrices);
    }
}
