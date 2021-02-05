<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Test\Storefront\Page\Product;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\SalesChannel\Context\SalesChannelContextFactory;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepositoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Core\System\Tax\TaxDefinition;
use Shopware\Storefront\Page\Product\ProductPage;
use Shopware\Storefront\Page\Product\ProductPageLoadedEvent;
use Swag\CustomizedProducts\Core\Content\Product\ProductWrittenSubscriber;
use Swag\CustomizedProducts\Migration\Migration1565933910TemplateProduct;
use Swag\CustomizedProducts\Storefront\Page\Product\Extensions\EditConfigurationExtension;
use Swag\CustomizedProducts\Storefront\Page\Product\Extensions\ShareConfigurationExtension;
use Swag\CustomizedProducts\Storefront\Page\Product\ProductPageSubscriber;
use Swag\CustomizedProducts\Template\Aggregate\TemplateConfiguration\TemplateConfigurationDefinition;
use Swag\CustomizedProducts\Template\Aggregate\TemplateConfiguration\TemplateConfigurationEntity;
use Swag\CustomizedProducts\Template\Aggregate\TemplateOption\TemplateOptionEntity;
use Swag\CustomizedProducts\Template\Aggregate\TemplateOptionValue\TemplateOptionValueEntity;
use Swag\CustomizedProducts\Template\SalesChannel\Price\PriceService;
use Swag\CustomizedProducts\Template\TemplateEntity;
use Swag\CustomizedProducts\Test\Helper\ServicesTrait;
use Symfony\Component\HttpFoundation\Request;

class ProductPageSubscriberTest extends TestCase
{
    use ServicesTrait;

    private const SALES_CHANNEL_TOKEN = 'valid-token';

    /**
     * @var ProductPageSubscriber
     */
    private $productPageSubscriber;

    /**
     * @var SalesChannelContextFactory
     */
    private $salesChannelContextFactory;

    /**
     * @var PriceService
     */
    private $priceService;

    /**
     * @var string
     */
    private $taxId;

    protected function setUp(): void
    {
        $container = $this->getContainer();
        $this->salesChannelContextFactory = $container->get(SalesChannelContextFactory::class);
        $this->productPageSubscriber = $container->get(ProductPageSubscriber::class);
        $this->priceService = $container->get(PriceService::class);
        $this->taxId = $this->createTaxId(Context::createDefaultContext());
    }

    public function testGetSubscribedEvents(): void
    {
        static::assertSame(
            [
                ProductPageLoadedEvent::class => [
                    ['removeCustomizedProductsTemplateFromNoneInheritedVariant', 400],
                    ['enrichOptionPriceAbleDisplayPrices', 300],
                    ['addEditConfigurationExtension', 200],
                    ['addShareConfigurationExtension', 100],
                ],
            ],
            $this->productPageSubscriber::getSubscribedEvents()
        );
    }

    public function testGetOptionDisplaySurchargeWithDefaultCurrency(): void
    {
        $expected = $netPrice = 15.0;
        $salesChannelContext = $this->createMockSalesChannelContext();

        $this->assertTemplatePriceAble($netPrice, $expected, $salesChannelContext);
    }

    /**
     * @dataProvider getOptionAndValueDisplaySurchargeWithDifferentCurrencyProvider
     */
    public function testGetOptionDisplaySurchargeWithDifferentCurrency(array $input, float $expected): void
    {
        $salesChannelContext = $this->createMockSalesChannelContextWithDifferentCurrency([
            'factor' => $input['currencyFactor'],
        ]);

        $this->assertTemplatePriceAble($input['netPrice'], $expected, $salesChannelContext);
    }

    public function getOptionAndValueDisplaySurchargeWithDifferentCurrencyProvider(): array
    {
        return [
            [
                ['netPrice' => 800, 'currencyFactor' => 1],
                800,
            ],
            [
                ['netPrice' => 15, 'currencyFactor' => 100],
                1500,
            ],
            [
                ['netPrice' => 123.45, 'currencyFactor' => 1],
                123.45,
            ],
            [
                ['netPrice' => 123.45, 'currencyFactor' => 100],
                12345,
            ],
            [
                ['netPrice' => 123.45, 'currencyFactor' => 0.01],
                1.23,
            ],
            [
                ['netPrice' => 123.45, 'currencyFactor' => 0.01],
                1.23,
            ],
            [
                ['netPrice' => 567.89, 'currencyFactor' => 0.01],
                5.68,
            ],
            [
                ['netPrice' => 999.99, 'currencyFactor' => 0.01],
                10,
            ],
        ];
    }

    public function testRemoveCustomizedProductsTemplateFromNoneInheritedVariantWithoutParentId(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $product = new SalesChannelProductEntity();
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            new TemplateEntity()
        );
        $page = new ProductPage();
        $page->setProduct($product);
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->removeCustomizedProductsTemplateFromNoneInheritedVariant($event);

        static::assertTrue($product->hasExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
        static::assertInstanceOf(TemplateEntity::class, $product->getExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
    }

    public function testRemoveCustomizedProductsTemplateFromNoneInheritedVariantWithoutCustomFields(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $product = new SalesChannelProductEntity();
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            new TemplateEntity()
        );
        $product->setParentId(Uuid::randomHex());
        $page = new ProductPage();
        $page->setProduct($product);
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->removeCustomizedProductsTemplateFromNoneInheritedVariant($event);

        static::assertTrue($product->hasExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
        static::assertInstanceOf(TemplateEntity::class, $product->getExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
    }

    public function testRemoveCustomizedProductsTemplateFromNoneInheritedVariantWithCustomFieldFalse(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $product = new SalesChannelProductEntity();
        $product->setId(Uuid::randomHex());
        $product->setParentId(Uuid::randomHex());
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            new TemplateEntity()
        );
        $product->setCustomFields([
            ProductWrittenSubscriber::SWAG_CUSTOMIZED_PRODUCTS_TEMPLATE_INHERITED_CUSTOM_FIELD => true,
        ]);
        $page = new ProductPage();
        $page->setProduct($product);
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->removeCustomizedProductsTemplateFromNoneInheritedVariant($event);

        static::assertTrue($product->hasExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
        static::assertInstanceOf(TemplateEntity::class, $product->getExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
    }

    public function testRemoveCustomizedProductsTemplateFromNoneInheritedVariantWithOwnTemplateAssigned(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $product = $this->getVariant($this->taxId, $context);
        $product->setCustomFields([
            ProductWrittenSubscriber::SWAG_CUSTOMIZED_PRODUCTS_TEMPLATE_INHERITED_CUSTOM_FIELD => false,
        ]);
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            new TemplateEntity()
        );
        $page = new ProductPage();
        $page->setProduct($product);
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->removeCustomizedProductsTemplateFromNoneInheritedVariant($event);

        static::assertTrue($product->hasExtension(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN));
    }

    public function testRemoveCustomizedProductsTemplateFromNoneInheritedVariant(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $product = new SalesChannelProductEntity();
        $product->setId(Uuid::randomHex());
        $product->setParentId(Uuid::randomHex());
        $product->setCustomFields([
            ProductWrittenSubscriber::SWAG_CUSTOMIZED_PRODUCTS_TEMPLATE_INHERITED_CUSTOM_FIELD => false,
        ]);
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            new TemplateEntity()
        );
        $page = new ProductPage();
        $page->setProduct($product);
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->removeCustomizedProductsTemplateFromNoneInheritedVariant($event);
        $extensions = $product->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddEditConfigurationExtensionWithoutEditConfigurationParameterDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->addEditConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddEditConfigurationExtensionWithoutValidUuidDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::EDIT_CONFIGURATION_PARAMETER => 'not-a-valid-uuid',
            ])
        );

        $this->productPageSubscriber->addEditConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddEditConfigurationExtensionWithoutExistingConfigurationDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::EDIT_CONFIGURATION_PARAMETER => Uuid::randomHex(),
            ])
        );

        $this->productPageSubscriber->addEditConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddEditConfigurationExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $templateId = Uuid::randomHex();
        $this->createTemplate($templateId, $context->getContext());
        /** @var EntityRepositoryInterface $configurationRepository */
        $configurationRepository = $this->getContainer()->get(TemplateConfigurationDefinition::ENTITY_NAME . '.repository');
        $configId = Uuid::randomHex();
        $hash = Uuid::randomHex();
        $configurationRepository->create(
            [
                [
                    'id' => $configId,
                    'templateId' => $templateId,
                    'hash' => $hash,
                    'configuration' => [],
                ],
            ],
            $context->getContext()
        );

        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::EDIT_CONFIGURATION_PARAMETER => $configId,
            ])
        );

        $this->productPageSubscriber->addEditConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertCount(1, $extensions);
        $editExtension = $extensions[ProductPageSubscriber::EDIT_CONFIGURATION_PARAMETER];
        static::assertNotNull($editExtension);
        static::assertInstanceOf(EditConfigurationExtension::class, $editExtension);
        static::assertSame($hash, $editExtension->getOldHash());
    }

    public function testAddShareConfigurationExtensionWithoutShareConfigurationParameterDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request()
        );

        $this->productPageSubscriber->addShareConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddShareConfigurationExtensionWithoutValidUuidDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER => 'not-a-valid-uuid',
            ])
        );

        $this->productPageSubscriber->addShareConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddShareConfigurationExtensionWithoutExistingShareDoesNotAddExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER => Uuid::randomHex(),
            ])
        );

        $this->productPageSubscriber->addShareConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertEmpty($extensions);
    }

    public function testAddShareConfigurationExtensionWithOneTimeConfiguration(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $templateId = Uuid::randomHex();
        $this->createTemplate($templateId, $context->getContext());
        /** @var EntityRepositoryInterface $configurationRepository */
        $configurationRepository = $this->getContainer()->get(TemplateConfigurationDefinition::ENTITY_NAME . '.repository');
        $configId = Uuid::randomHex();
        $shareId = Uuid::randomHex();
        $configuration = ['foo' => 'bar'];
        $configurationRepository->create(
            [
                [
                    'id' => $configId,
                    'templateId' => $templateId,
                    'hash' => Uuid::randomHex(),
                    'configuration' => $configuration,
                    'templateConfigurationShares' => [
                        [
                            'id' => $shareId,
                            'oneTime' => true,
                        ],
                    ],
                ],
            ],
            $context->getContext()
        );

        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER => $shareId,
            ])
        );

        // Assert that the share extension got actually added
        $this->productPageSubscriber->addShareConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertCount(1, $extensions);
        $shareExtension = $extensions[ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER];
        static::assertNotNull($shareExtension);
        static::assertInstanceOf(ShareConfigurationExtension::class, $shareExtension);
        static::assertSame($configuration, $shareExtension->getConfiguration());

        // Make sure that the one time share got deleted
        $criteria = new Criteria([$configId]);
        $criteria->addAssociation('templateConfigurationShares');
        /** @var TemplateConfigurationEntity|null $config */
        $config = $configurationRepository->search($criteria, $context->getContext())->first();
        static::assertNotNull($config);
        $templateConfigurationShareCollection = $config->getTemplateConfigurationShares();
        static::assertNotNull($templateConfigurationShareCollection);
        static::assertCount(0, $templateConfigurationShareCollection);
    }

    public function testAddShareConfigurationExtension(): void
    {
        $context = $this->salesChannelContextFactory->create(self::SALES_CHANNEL_TOKEN, Defaults::SALES_CHANNEL);
        $templateId = Uuid::randomHex();
        $this->createTemplate($templateId, $context->getContext());
        /** @var EntityRepositoryInterface $configurationRepository */
        $configurationRepository = $this->getContainer()->get(TemplateConfigurationDefinition::ENTITY_NAME . '.repository');
        $configId = Uuid::randomHex();
        $shareId = Uuid::randomHex();
        $configuration = ['foo' => 'bar'];
        $configurationRepository->create(
            [
                [
                    'id' => $configId,
                    'templateId' => $templateId,
                    'hash' => Uuid::randomHex(),
                    'configuration' => $configuration,
                    'templateConfigurationShares' => [
                        [
                            'id' => $shareId,
                            'oneTime' => false,
                        ],
                    ],
                ],
            ],
            $context->getContext()
        );

        $page = new ProductPage();
        $event = new ProductPageLoadedEvent(
            $page,
            $context,
            new Request([
                ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER => $shareId,
            ])
        );

        // Assert that the share extension got actually added
        $this->productPageSubscriber->addShareConfigurationExtension($event);
        $extensions = $page->getExtensions();
        static::assertCount(1, $extensions);
        $shareExtension = $extensions[ProductPageSubscriber::SHARE_CONFIGURATION_PARAMETER];
        static::assertNotNull($shareExtension);
        static::assertInstanceOf(ShareConfigurationExtension::class, $shareExtension);
        static::assertSame($configuration, $shareExtension->getConfiguration());

        // Make sure that the share did not get deleted
        $criteria = new Criteria([$configId]);
        $criteria->addAssociation('templateConfigurationShares');
        /** @var TemplateConfigurationEntity|null $config */
        $config = $configurationRepository->search($criteria, $context->getContext())->first();
        static::assertNotNull($config);
        $templateConfigurationShareCollection = $config->getTemplateConfigurationShares();
        static::assertNotNull($templateConfigurationShareCollection);
        static::assertCount(1, $templateConfigurationShareCollection);
        $share = $templateConfigurationShareCollection->first();
        static::assertNotNull($share);
        static::assertSame($shareId, $share->getId());
    }

    private function assertTemplatePriceAble(float $netPrice, float $expected, SalesChannelContext $salesChannelContext): void
    {
        $resultTemplate = $this->createMockTemplate($netPrice, $salesChannelContext->getContext());
        $resultEventTemplate = clone $resultTemplate;
        $event = $this->getMockProductPageSubscriberEvent($resultEventTemplate, $salesChannelContext);

        $this->priceService->calculateCurrencyPrices($resultTemplate, $salesChannelContext);
        $this->assertTemplateCalculatedPrices($resultTemplate, $expected);

        $this->productPageSubscriber->enrichOptionPriceAbleDisplayPrices($event);
        $this->assertTemplateCalculatedPrices($resultEventTemplate, $expected);
    }

    private function assertTemplateCalculatedPrices(
        TemplateEntity $resultTemplate,
        float $expected
    ): void {
        static::assertNotNull($resultTemplate->getOptions());

        /** @var TemplateOptionEntity $resultTemplateOption */
        $resultTemplateOption = $resultTemplate->getOptions()->first();

        static::assertNotNull($resultTemplateOption->getValues());

        /** @var TemplateOptionValueEntity $resultTemplateOptionValueFirst */
        $resultTemplateOptionValueFirst = $resultTemplateOption->getValues()->first();
        /** @var TemplateOptionValueEntity $resultTemplateOptionValueLast */
        $resultTemplateOptionValueLast = $resultTemplateOption->getValues()->last();

        $optionPrice = $resultTemplateOption->getCalculatedPrice();
        $optionValueFirstPrice = $resultTemplateOptionValueFirst->getCalculatedPrice();
        $optionValueLastPrice = $resultTemplateOptionValueLast->getCalculatedPrice();

        static::assertNotNull($optionPrice);
        static::assertNotNull($optionValueFirstPrice);
        static::assertNotNull($optionValueLastPrice);

        static::assertEquals($expected, $optionPrice->getTotalPrice());
        static::assertEquals($expected, $optionValueFirstPrice->getTotalPrice());
        static::assertEquals($expected, $optionValueLastPrice->getTotalPrice());
    }

    private function getVariant(string $taxId, SalesChannelContext $salesChannelContext): SalesChannelProductEntity
    {
        /** @var SalesChannelRepositoryInterface $salesChannelRepo */
        $salesChannelRepo = $this->getContainer()->get('sales_channel.product.repository');
        /** @var EntityRepositoryInterface $productRepo */
        $productRepo = $this->getContainer()->get(ProductDefinition::ENTITY_NAME . '.repository');
        $parentId = Uuid::randomHex();
        $variantId = Uuid::randomHex();
        $templateId = Uuid::randomHex();

        // Create Container Product
        $productRepo->create([
            [
                'id' => $parentId,
                'stock' => \random_int(1, 5),
                'taxId' => $taxId,
                'price' => [
                    'net' => [
                        'currencyId' => Defaults::CURRENCY,
                        'net' => 74.49,
                        'gross' => 89.66,
                        'linked' => true,
                    ],
                ],
                'productNumber' => 'seg-1337',
                'translations' => [
                    Defaults::LANGUAGE_SYSTEM => [
                        'name' => 'example-product',
                    ],
                ],
            ],
        ], $salesChannelContext->getContext());

        $this->createTemplate(
            $templateId,
            $salesChannelContext->getContext()
        );

        //Create Variant
        $productRepo->create([
            [
                'id' => $variantId,
                'parentId' => $parentId,
                'stock' => \random_int(1, 5),
                'taxId' => $taxId,
                'swagCustomizedProductsTemplateId' => $templateId,
                'visibilities' => [
                    [
                        'productId' => $variantId,
                        'salesChannelId' => $salesChannelContext->getSalesChannel()->getId(),
                        'visibility' => 30,
                    ],
                ],
                'price' => [
                    'net' => [
                        'currencyId' => Defaults::CURRENCY,
                        'net' => 74.49,
                        'gross' => 89.66,
                        'linked' => true,
                    ],
                ],
                'productNumber' => 'seg-1338',
                'translations' => [
                    Defaults::LANGUAGE_SYSTEM => [
                        'name' => 'example-product-variant',
                    ],
                ],
            ],
        ], $salesChannelContext->getContext());

        $criteria = new Criteria([$variantId]);
        $criteria->addAssociation(Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN);
        $products = $salesChannelRepo->search(new Criteria(), $salesChannelContext);
        static::assertCount(1, $products);
        $product = $products->first();
        static::assertInstanceOf(SalesChannelProductEntity::class, $product);

        return $product;
    }

    private function createTaxId(Context $context): string
    {
        /** @var EntityRepositoryInterface $taxRepo */
        $taxRepo = $this->getContainer()->get(TaxDefinition::ENTITY_NAME . '.repository');
        $taxId = Uuid::randomHex();

        $taxRepo->create([
            [
                'id' => $taxId,
                'taxRate' => 19.0,
                'name' => 'testTaxRate',
            ],
        ], $context);

        return $taxId;
    }

    private function createMockTemplate(float $netPrice, Context $context): TemplateEntity
    {
        $templateRepo = $this->getTemplateRepository();
        $templateId = Uuid::randomHex();
        $additionalData = $this->prepareAdditionalTemplateData($netPrice);

        $this->createTemplate($templateId, $context, $additionalData);

        // Gather the created data from the DAL
        $criteria = (new Criteria([$templateId]))
            ->addAssociation('options')
            ->addAssociation('options.values');

        return $templateRepo->search($criteria, $context)->getEntities()->first();
    }

    private function createMockSalesChannelContext(): SalesChannelContext
    {
        $salesChannelContextFactory = $this->getContainer()->get(SalesChannelContextFactory::class);
        $salesChannelContext = $salesChannelContextFactory->create(
            Uuid::randomHex(),
            Defaults::SALES_CHANNEL
        );
        $salesChannelContext->setTaxState('net');

        return $salesChannelContext;
    }

    private function createMockSalesChannelContextWithDifferentCurrency(array $currencyProperties = []): SalesChannelContext
    {
        $salesChannelContext = $this->createMockSalesChannelContext();
        $testCurrency = $this->getTestCurrency($salesChannelContext->getContext(), $currencyProperties);

        $salesChannelContext->assign(['currency' => $testCurrency]);

        return $salesChannelContext;
    }

    private function getMockProductPageSubscriberEvent(
        TemplateEntity $templateEntity,
        SalesChannelContext $salesChannelContext
    ): ProductPageLoadedEvent {
        $product = new SalesChannelProductEntity();
        $product->addExtension(
            Migration1565933910TemplateProduct::PRODUCT_TEMPLATE_INHERITANCE_COLUMN,
            $templateEntity
        );
        $page = new ProductPage();
        $page->setProduct($product);

        return new ProductPageLoadedEvent(
            $page,
            $salesChannelContext,
            new Request()
        );
    }

    private function prepareAdditionalTemplateData(float $netPrice): array
    {
        $price = [
            'currencyId' => Defaults::CURRENCY,
            'net' => $netPrice,
            'gross' => $netPrice * 1.19,
            'linked' => true,
        ];

        $additionalDataOptionValues = [
            'price' => [$price],
            'taxId' => $this->taxId,
        ];

        $additionalDataOptions = [
            'price' => [$price],
            'taxId' => $this->taxId,
            'values' => [
                $this->getTemplateOptionValuesArrayForCreation($additionalDataOptionValues),
                $this->getTemplateOptionValuesArrayForCreation($additionalDataOptionValues),
            ],
        ];

        return [
            'options' => [$this->getTemplateOptionsArrayForCreation($additionalDataOptions)],
        ];
    }
}
