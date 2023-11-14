<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Service;

use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\HttpFoundation\UrlHelper;

class AttachFileUrlService
{
    public function __construct(
        private readonly UrlHelper $urlHelper,
    ) {
    }

    public function get(?AttachFile $attachFile): ?string
    {
        if (null === $attachFile) {
            return null;
        }

        return $this->urlHelper->getAbsoluteUrl($attachFile->filePath());
    }
}
