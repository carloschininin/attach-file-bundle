<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Service;

use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class AttachFileDownloadService
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function get(?AttachFile $attachFile, string $disposition = AttachFile::INLINE, bool $urlShort = true): ?string
    {
        if (null === $attachFile) {
            return null;
        }

        return $this->urlGenerator->generate(
            name: $urlShort ? 'attach_file_download_short' : 'attach_file_download',
            parameters: [
                'secure' => $attachFile->secure(),
                'disposition' => (AttachFile::INLINE !== $disposition) ? $disposition : null,
            ],
            referenceType: UrlGeneratorInterface::ABSOLUTE_URL,
        );
    }
}
