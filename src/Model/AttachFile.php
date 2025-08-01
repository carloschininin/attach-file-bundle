<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Model;

use CarlosChininin\AttachFile\Helper\FileHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class AttachFile
{
    public const NAME_LENGTH = 64;
    public const SECURE_LENGTH = 21;
    public const FOLDER_LENGTH = 64;

    public const INLINE = ResponseHeaderBag::DISPOSITION_INLINE;
    public const ATTACHMENT = ResponseHeaderBag::DISPOSITION_ATTACHMENT;

    private ?int $id = null;
    private ?string $name = null;
    private ?string $secure = null;
    private ?string $folder = null;
    private ?string $attachDirectory = null;

    private ?UploadedFile $file = null;

    private \DateTimeImmutable $updatedAt;
    private bool $isDeleted = false;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

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
        $this->name = FileHelper::sanitizeFilename($name, self::NAME_LENGTH);
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
        $folder = FileHelper::sanitizeFolder($folder, self::FOLDER_LENGTH);
        if (empty($folder)) {
            $this->folder = '';

            return;
        }

        if (!str_starts_with($folder, '/')) {
            $folder = '/'.$folder;
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
            $this->updatedAt = new \DateTimeImmutable();
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

    public function isDeleted(): bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }

    public function __serialize(): array
    {
        return [$this->id, $this->secure, $this->updatedAt];
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $this->secure, $this->updatedAt] = $data;
    }
}
