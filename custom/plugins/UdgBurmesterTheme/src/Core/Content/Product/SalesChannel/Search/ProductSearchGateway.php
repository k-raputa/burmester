<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Core\Content\Product\SalesChannel\Search;

use Shopware\Core\Content\Product\SalesChannel\Search\ProductSearchGateway as ShopwareProductSearchGateway;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

class ProductSearchGateway extends ShopwareProductSearchGateway
{

    public function search(Request $request, SalesChannelContext $context): EntitySearchResult
    {
        if (!$request->query->has('limit')) {
            // raise default limit for search
            $request->query->set('limit', 60);
        }
        return parent::search($request, $context);
    }
}
