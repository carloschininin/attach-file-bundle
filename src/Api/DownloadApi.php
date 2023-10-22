<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Api;

use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class DownloadApi
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function __invoke(AttachFile $attachFile, string $disposition = null): Response
    {
        $publicDirectory = $this->parameterBag->get('app.public_directory');
        $attachDirectory = $this->parameterBag->get('app.attach_file_directory');
        $filePath = $publicDirectory.$attachDirectory.$attachFile->filePath();
        $response = new BinaryFileResponse($filePath);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            $disposition ?? ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $attachFile->name() ?? $response->getFile()->getFilename()
        );

        return $response;
    }
}
