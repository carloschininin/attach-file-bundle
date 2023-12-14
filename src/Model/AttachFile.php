<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AttachFile
{
    public const NAME_LENGTH = 64;
    public const FOLDER_LENGTH = 64;

    public const INLINE = ResponseHeaderBag::DISPOSITION_INLINE;
    public const ATTACHMENT = ResponseHeaderBag::DISPOSITION_ATTACHMENT;

    private ?int $id = null;
    private ?string $name = null;
    private ?string $secure = null;
    private ?string $folder = null;
    private ?string $attachDirectory = null;

    private ?UploadedFile $file = null;

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        if (null !== $name && mb_strlen($name) > self::NAME_LENGTH) {
            $partName = pathinfo($name, PATHINFO_FILENAME);
            $partExt = pathinfo($name, PATHINFO_EXTENSION);
            $length = self::NAME_LENGTH - mb_strlen($partExt) - 5;
            $partName = mb_substr($partName, 0, $length).'-'.mt_rand(100, 999);
            $name = $partName.'.'.$partExt;
        }

        $this->name = $name;
    }

    public function secure(): ?string
    {
        return $this->secure;
    }

    public function setSecure(?string $secure): void
    {
        $this->secure = $secure;
    }

    public function folder(): ?string
    {
        return $this->folder;
    }

    public function setFolder(?string $folder): void
    {
        if (null === $folder || '' === trim($folder)) {
            $this->folder = '';

            return;
        }

        if (!str_starts_with($folder, '/')) {
            $folder = '/'.$folder;
        }

        if (mb_strlen($folder) > self::FOLDER_LENGTH) {
            $folder = mb_substr($folder, 0, self::FOLDER_LENGTH);
        }

        $this->folder = $folder;
    }

    public function attachDirectory(): ?string
    {
        return $this->attachDirectory;
    }

    public function setAttachDirectory(string $attachDirectory): void
    {
        $this->attachDirectory = $attachDirectory;
    }

    public function file(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): void
    {
        if (null !== $file) {
            $this->setName($file->getClientOriginalName());
            $this->file = $file;
        }
    }

    public function filePath(): ?string
    {
        if (null === $this->secure()) {
            return null;
        }

        $path = $this->attachDirectory();
        if (null !== ($folder = $this->folder())) {
            $path .= $folder;
        }

        return $path.'/'.$this->secure();
    }
}
