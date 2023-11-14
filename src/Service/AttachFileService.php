<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Service;

use CarlosChininin\AttachFile\Exception\FileCreateException;
use CarlosChininin\AttachFile\Model\AttachFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachFileService
{
    public function __construct(
        private readonly string $publicDirectory,
        private readonly string $attachFileDirectory
    ) {
    }

    public function uploadFile(object $entity): void
    {
        if (!$entity instanceof AttachFile) {
            return;
        }

        $file = $entity->file();
        if (!$file instanceof UploadedFile) {
            return;
        }

        $previousPath = $entity->filePath();
        $secure = $this->upload($file, $entity->folder());
        $entity->setSecure($secure);
        $entity->setAttachDirectory($this->attachFileDirectory);
        $this->remove($previousPath);
    }

    public function upload(UploadedFile $file, string $folder = null): string
    {
        $secure = $this->createName($file, $folder);

        try {
            $path = $this->getTargetDirectory().$folder;
            $file->move($path, $secure);
        } catch (FileException) {
            throw new FileCreateException(sprintf('The file %s could not be created in %s', $secure, $path));
        }

        return $secure;
    }

    public function removeFile(object $entity): void
    {
        if (!$entity instanceof AttachFile) {
            return;
        }

        $filepath = $entity->filePath();
        $this->remove($filepath);
    }

    public function remove(?string $fileName): void
    {
        if (null === $fileName || '' === trim($fileName)) {
            return;
        }

        $file = $this->publicDirectory.$fileName;

        if (file_exists($file)) {
            unlink($file);
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->publicDirectory.$this->attachFileDirectory;
    }

    protected function createName(UploadedFile $file, ?string $folder): string
    {
        $extension = $file->getClientOriginalExtension();
        $path = $this->getTargetDirectory().$folder.'/';
        do {
            $fileName = uniqid().'.'.$extension;
        } while (file_exists($path.$fileName)); // Repeat if file name exists.

        return $fileName;
    }
}
