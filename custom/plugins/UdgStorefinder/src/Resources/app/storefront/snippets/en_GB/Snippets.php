<?php declare(strict_types=1);

namespace UdgStorefinder\Resources\app\storefront\snippets\en_GB;

use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

/**
 * Class Snippet
 * @package UdgStorefinder\Resources\app\storefront\snippet\en
 */
class Snippets implements SnippetFileInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'udg.storefinder.' . $this->getIso();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return __DIR__ . '/' . $this->getIso() . '.json';
    }

    /**
     * @return string
     */
    public function getIso(): string
    {
        return 'en-GB';
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return 'Udg';
    }

    /**
     * @return bool
     */
    public function isBase(): bool
    {
        return false;
    }
}
