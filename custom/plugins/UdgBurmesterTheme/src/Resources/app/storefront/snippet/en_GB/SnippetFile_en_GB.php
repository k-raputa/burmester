<?php declare(strict_types=1);

namespace UdgBurmesterTheme\Resources\app\storefront\snippet\en_GB;

use Shopware\Core\System\Snippet\Files\SnippetFileInterface;

class SnippetFile_en_GB implements SnippetFileInterface
{
    public function getName(): string
    {
        return 'udg-burmester-theme.en-GB';
    }

    public function getPath(): string
    {
        return __DIR__ . '/udg-burmester-theme.en-GB.json';
    }

    public function getIso(): string
    {
        return 'en-GB';
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
