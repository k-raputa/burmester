<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Core\Content\Seo;

use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandler as ShopwareSeoUrlPlaceholderHandler;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class SeoUrlPlaceholderHandler extends ShopwareSeoUrlPlaceholderHandler
{

    private $categoryRepository;

    public function setCategoryRepo(EntityRepository $repo)
    {

        $this->categoryRepository = $repo;
    }

    /**
     * @param string $name
     */
    public function generate($name, array $parameters = []): string
    {

        if ($name == 'frontend.maintenance.singlepage') {

            $criteria = new Criteria();
            $criteria->addFilter(new EqualsFilter('cmsPageId', $parameters['id']));
            $criteria->addFilter(new EqualsFilter('active', true));

            /** @var CategoryCollection $categories */
            $categories = $this->categoryRepository->search($criteria, Context::createDefaultContext())->getEntities();
            if ($categories->count() == 1) {
                $name = 'frontend.navigation.page';
                $parameters = ['navigationId' => $categories->first()->getId()];
            }
        }

        return parent::generate($name, $parameters);
    }
}
