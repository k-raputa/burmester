<?php declare(strict_types=1);

namespace UdgMediaCommand\Service;

use Shopware\Core\Content\Media\File\FileFetcher;
use Shopware\Core\Content\Media\File\FileSaver;
use Shopware\Core\Content\Media\MediaService;
use Shopware\Core\Framework\Context;
use Symfony\Component\HttpFoundation\Request;

class MediaUploader
{
    /**
     * @var FileFetcher
     */
    private $fileFetcher;

    /**
     * @var FileSaver
     */
    private $fileSaver;

    /**
     * @var MediaService
     */
    private $mediaService;

    public function __construct(MediaService $mediaService, FileSaver $fileSaver, FileFetcher $fileFetcher)
    {
        $this->mediaService = $mediaService;
        $this->fileSaver = $fileSaver;
        $this->fileFetcher = $fileFetcher;
    }

    public function upload(string $fileUrl, string $filename, string $folder, string $fileExtension)
    {

        $tempFile = tempnam(sys_get_temp_dir(), '');

        $request = new Request();
        $request->request->set('url', $fileUrl);
        $request->query->set('extension', $fileExtension);

        try {
            $context = Context::createDefaultContext();
            $mediaFile = $this->fileFetcher->fetchFileFromURL($request, $tempFile);

            $mediaId = $this->mediaService->createMediaInFolder($folder, $context, false);


            $this->fileSaver->persistFileToMedia(
                $mediaFile,
                pathinfo($filename, PATHINFO_FILENAME),
                $mediaId,
                $context
            );

        } finally {
            unlink($tempFile);
        }
    }
}
