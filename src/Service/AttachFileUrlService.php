<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Service;

use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AttachFileUrlService
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function get(?AttachFile $attachFile): ?string
    {
        if (null === $attachFile) {
            return null;
        }

        return $this->urlGenerator->generate('attach_file_download', [
            'secure' => $attachFile->secure(),
        ], UrlGeneratorInterface::NETWORK_PATH);
    }
}
