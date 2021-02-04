<?php declare(strict_types=1);

namespace UdgExtendedProductDetailPage\Storefront\Page\Product;

use Shopware\Core\Content\Category\Exception\CategoryNotFoundException;
use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Content\Cms\DataResolver\CmsSlotsDataResolver;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\EntityResolverContext;
use Shopware\Core\Content\Cms\SalesChannel\SalesChannelCmsPageRepository;
use Shopware\Core\Content\Product\Exception\ProductNotFoundException;
use Shopware\Core\Content\Product\ProductDefinition;
use Shopware\Core\Content\Product\ProductEntity;
use Shopware\Core\Content\Product\SalesChannel\SalesChannelProductEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Routing\Exception\MissingRequestParameterException;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelRepositoryInterface;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Shopware\Storefront\Page\GenericPageLoader;
use Shopware\Storefront\Page\Product\Configurator\ProductPageConfiguratorLoader;
use Shopware\Storefront\Page\Product\CrossSelling\CrossSellingLoader;
use Shopware\Storefront\Page\Product\ProductPage;
use Shopware\Storefront\Page\Product\Review\ProductReviewLoader;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Shopware\Storefront\Page\Product\ProductLoader;


use Shopware\Storefront\Page\Product\ProductPageLoader as ShopwareProductPageLoader;

class ProductPageLoader extends ShopwareProductPageLoader
{


    /**
     * @var CmsSlotsDataResolver
     */
    private $slotDataResolver;

    /**
     * @var ProductDefinition
     */
    private $productDefinition;

    /**
     * @var SalesChannelCmsPageRepository
     */
    private $cmsPageRepository;

    /**
     * @var SalesChannelRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        GenericPageLoader $genericLoader,
        SalesChannelRepositoryInterface $productRepository,
        EventDispatcherInterface $eventDispatcher,
        SalesChannelCmsPageRepository $cmsPageRepository,
        CmsSlotsDataResolver $slotDataResolver,
        ProductPageConfiguratorLoader $configuratorLoader,
        ProductDefinition $productDefinition,
        ProductLoader $productLoader,
        ProductReviewLoader $productReviewLoader,
        CrossSellingLoader $crossSellingLoader
    )
    {
        parent::__construct($genericLoader,
            $productRepository,
            $eventDispatcher,
            $cmsPageRepository,
            $slotDataResolver,
            $configuratorLoader,
            $productDefinition,
            $productLoader,
            $productReviewLoader,
            $crossSellingLoader
        );

        $this->productRepository = $productRepository;
        $this->cmsPageRepository = $cmsPageRepository;
        $this->slotDataResolver = $slotDataResolver;
        $this->productDefinition = $productDefinition;
    }

    /**
     * @throws CategoryNotFoundException
     * @throws InconsistentCriteriaIdsException
     * @throws MissingRequestParameterException
     * @throws ProductNotFoundException
     */
    public function load(Request $request, SalesChannelContext $salesChannelContext): ProductPage
    {

        $page = parent::load($request, $salesChannelContext);
        $page->setProduct(
            $this->addParentToProduct($page->getProduct(), $salesChannelContext)
        );

        $cmsPageId = '';
        $customeFields = $page->getProduct()->getCustomFields();
        if (is_array($customeFields) && array_key_exists('udgextendedproductdetailpage_categoryid4cmspage', $customeFields)) {
            $cmsPageId = $customeFields['udgextendedproductdetailpage_categoryid4cmspage'];
        }
        if (empty($cmsPageId)) {
            $parent = $page->getProduct()->getParent();
            if ($parent instanceof ProductEntity) {
                $customeFields = $parent->getCustomFields();
                if (is_array($customeFields) && array_key_exists('udgextendedproductdetailpage_categoryid4cmspage', $customeFields)) {
                    $cmsPageId = $customeFields['udgextendedproductdetailpage_categoryid4cmspage'];
                }
            }
        }

        if (null === $page->getCmsPage() && strlen($cmsPageId) > 0) {
            $cmsPage = $this->getCmsPage($salesChannelContext, $cmsPageId);

            if ($cmsPage instanceof CmsPageEntity) {
                $this->loadSlotData($cmsPage, $salesChannelContext, $page->getProduct());
                $page->setCmsPage($cmsPage);
            }
        }

        return $page;
    }

    private function addParentToProduct(ProductEntity $product, SalesChannelContext $salesChannelContext): ProductEntity
    {

        if ($product->getParentId() !== null && $product->getParent() === null) {
            $criteria = (new Criteria([$product->getParentId()]));

            /** @var SalesChannelProductEntity|null $parent */
            $parent = $this->productRepository->search($criteria, $salesChannelContext)->get($product->getParentId());

            if ($product instanceof ProductEntity) {
                $product->setParent($parent);
            }
        }

        return $product;
    }

    private function getCmsPage(SalesChannelContext $context, string $cmsPageId): ?CmsPageEntity
    {

        $pages = $this->cmsPageRepository->read([$cmsPageId], $context);

        if ($pages->count() === 0) {
            return null;
        }

        /** @var CmsPageEntity $page */
        $page = $pages->first();
        return $page;
    }

    private function loadSlotData(
        CmsPageEntity $page,
        SalesChannelContext $salesChannelContext,
        SalesChannelProductEntity $product
    ): void
    {
        if (!$page->getSections()) {
            return;
        }

        // replace actual request in NEXT-1539
        $request = new Request();

        $resolverContext = new EntityResolverContext($salesChannelContext, $request, $this->productDefinition, $product);

        foreach ($page->getSections() as $section) {
            $slots = $this->slotDataResolver->resolve($section->getBlocks()->getSlots(), $resolverContext);
            $section->getBlocks()->setSlots($slots);
        }
    }
}
