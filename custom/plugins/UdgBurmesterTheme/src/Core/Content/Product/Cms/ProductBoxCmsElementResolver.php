<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Core\Content\Product\Cms;

use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Content\Product\Cms\ProductBoxCmsElementResolver as ShopwareProductBoxCmsElementResolver;

class ProductBoxCmsElementResolver extends ShopwareProductBoxCmsElementResolver {

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        $config = $slot->getFieldConfig();
        $productConfig = $config->get('product');

        if (!$productConfig || $productConfig->isMapped() || $productConfig->getValue() === null) {
            return null;
        }

        $criteria = (new Criteria([$productConfig->getValue()]))
            ->addAssociation('properties.group');

        $criteriaCollection = new CriteriaCollection();
        $criteriaCollection->add('product_' . $slot->getUniqueIdentifier(), ProductDefinition::class, $criteria);

        return $criteriaCollection;
    }
}
