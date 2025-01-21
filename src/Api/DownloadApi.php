<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Api;

use CarlosChininin\AttachFile\Exception\FileNotFoundException;
use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

final readonly class DownloadApi
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
    ) {
    }

    public function __invoke(AttachFile $attachFile, ?string $disposition = null): Response
    {
        $publicDirectory = $this->parameterBag->get('app.public_directory');
        $filePath = $publicDirectory.$attachFile->filePath();
        if (!file_exists($filePath)) {
            throw new FileNotFoundException(\sprintf('The file %s not found in %s', $attachFile->name(), $filePath));
        }
        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            $disposition ?? AttachFile::INLINE,
            $attachFile->name() ?? $response->getFile()->getFilename()
        );

        return $response;
    }
}
