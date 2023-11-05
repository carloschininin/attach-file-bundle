<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Service;

use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AttachFileDownloadService
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function get(?AttachFile $attachFile, string $disposition = ResponseHeaderBag::DISPOSITION_INLINE): ?string
    {
        if (null === $attachFile) {
            return null;
        }

        return $this->urlGenerator->generate('attach_file_download', [
            'secure' => $attachFile->secure(),
            'disposition' => $disposition,
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
