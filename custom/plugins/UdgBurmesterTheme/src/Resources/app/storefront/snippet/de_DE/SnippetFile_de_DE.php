<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Resources\app\storefront\snippet\de_DE;

use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

class SnippetFile_de_DE implements SnippetFileInterface
{
    public function getName(): string
    {
        return 'udg-burmester-theme.de-DE';
    }

    public function getPath(): string
    {
        return __DIR__ . '/udg-burmester-theme.de-DE.json';
    }

    public function getIso(): string
    {
        return 'de-DE';
    }

    public function getAuthor(): string
    {
        return 'Udg';
    }

    public function isBase(): bool
    {
        return false;
    }
}
