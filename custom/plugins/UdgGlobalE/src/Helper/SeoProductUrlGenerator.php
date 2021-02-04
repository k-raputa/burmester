<?php declare(strict_types=1);

namespace UdgGlobalE\Helper;

use Shopware\Core\Content\Seo\SeoUrlPlaceholderHandlerInterface;
use Shopware\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainEntity;
use Shopware\Core\System\SalesChannel\SalesChannelContext;

class SeoProductUrlGenerator {

    /**
     * @var SeoUrlPlaceholderHandlerInterface
     */
    private $seoUrlReplacer;

    public function __construct(
        SeoUrlPlaceholderHandlerInterface $seoUrlReplacer
    )
    {
        $this->seoUrlReplacer = $seoUrlReplacer;
    }

    public function getSeoProductUrl(string $referencedId, SalesChannelContext $salesChannelContext): string
    {
        $productPageUrl = $this->seoUrlReplacer->generate('frontend.detail.page', ['productId' => $referencedId]);

        $host = '';
        foreach ($salesChannelContext->getSalesChannel()->getDomains() as $domain) {
            /** @var SalesChannelDomainEntity $domain */
            if ($domain->getLanguageId() === $salesChannelContext->getSalesChannel()->getLanguageId()) {
                $host = $domain->getUrl();
                break;
            }
        }

        $productPageUrl = $this->seoUrlReplacer->replace($productPageUrl, $host, $salesChannelContext);

        return $productPageUrl;
    }
}
