<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachFile
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $secure = null;
    private ?string $folder = null;

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
        $this->folder = $folder;
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

        return $this->folder().'/'.$this->secure();
    }
}
