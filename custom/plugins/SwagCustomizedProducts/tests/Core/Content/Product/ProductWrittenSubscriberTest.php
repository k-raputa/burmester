<?php declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\CustomizedProducts\Test\Core\Content\Product;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Content\Product\Aggregate\ProductVisibility\ProductVisibilityDefinition;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Uuid\Uuid;
use Swag\CustomizedProducts\Core\Content\Product\ProductWrittenSubscriber;
use Swag\CustomizedProducts\Test\Helper\ServicesTrait;

class ProductWrittenSubscriberTest extends TestCase
{
    use ServicesTrait;

    private const TEMPLATE_DISPLAY_NAME = 'lorem-ipsum-template';

    public function testProductVariantGetInheritedTemplate(): void
    {
        $taxId = $this->getValidTaxId();
        $context = Context::createDefaultContext();
        $productId = Uuid::randomHex();
        $this->createProduct($productId, $context, $taxId);
        $templateId = Uuid::randomHex();
        $templateData = $this->getTemplateData($templateId, $productId, $taxId);

        $this->createTemplate(
            $templateId,
            $context,
            $templateData
        );

        $variantId = Uuid::randomHex();
        $this->createProduct(
            $variantId,
            $context,
            $taxId,
            [
                'parentId' => $productId,
            ]
        );

        $criteria = new Criteria([$variantId]);
        $criteria->addAssociation('swagCustomizedProductsTemplate');
        $context->setConsiderInheritance(true);

        /** @var EntityRepositoryInterface $productRepository */
        $productRepository = $this->getContainer()->get(\sprintf('%s.repository', ProductDefinition::ENTITY_NAME));
        /** @var ProductEntity|null $res */
        $res = $productRepository->search($criteria, $context)->first();

        static::assertInstanceOf(ProductEntity::class, $res);
        static::assertSame($templateId, $res->get('swagCustomizedProductsTemplateId'));

        $customFields = $res->getCustomFields();
        static::assertNotNull($customFields);
        static::assertArrayHasKey(ProductWrittenSubscriber::SWAG_CUSTOMIZED_PRODUCTS_TEMPLATE_INHERITED_CUSTOM_FIELD, $customFields);
        static::assertTrue((bool) $customFields[ProductWrittenSubscriber::SWAG_CUSTOMIZED_PRODUCTS_TEMPLATE_INHERITED_CUSTOM_FIELD]);
    }

    private function getTemplateData(string $templateId, string $productId, string $taxId, array $optionData = []): array
    {
        $templateData = [
            'id' => $templateId,
            'internalName' => 'internalTemplateName',
            'displayName' => self::TEMPLATE_DISPLAY_NAME,
            'active' => true,
        ];

        if (!empty($productId)) {
            $templateData['products'] = [
                [
                    'id' => $productId,
                    'name' => 'Test name of a product',
                    'manufacturer' => [
                        'id' => Uuid::randomHex(),
                        'name' => 'amazing brand',
                    ],
                    'active' => true,
                    'visibilities' => [
                        [
                            'salesChannelId' => Defaults::SALES_CHANNEL,
                            'visibility' => ProductVisibilityDefinition::VISIBILITY_ALL,
                        ],
                    ],
                    'productNumber' => 'P1234',
                    'tax' => ['id' => $taxId],
                    'price' => [
                        [
                            'currencyId' => Defaults::CURRENCY,
                            'gross' => 5,
                            'net' => 8,
                            'linked' => false,
                        ],
                    ],
                    'stock' => 10,
                ],
            ];
        }

        if (!empty($optionData)) {
            $templateData['options'] = $optionData;
        }

        return $templateData;
    }
}
