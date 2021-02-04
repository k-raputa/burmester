<?php declare(strict_types=1);

namespace UdgStorefinder\Resources\app\storefront\snippets\de_DE;

use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

/**
 * Class Snippet_de_DE
 * @package UdgStorefinder\Resources\app\storefront\snippet\de_DE
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
        return 'de-DE';
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
