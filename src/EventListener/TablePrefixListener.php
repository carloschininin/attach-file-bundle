<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\EventListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

final class TablePrefixListener
{
    protected ?string $prefix;

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        if (!$this->prefix) {
            return;
        }

        $classMetadata = $eventArgs->getClassMetadata();
        if ('attach_file' === $classMetadata->getTableName()) {
            $classMetadata->setPrimaryTable(['name' => $this->prefix.'attach_file']);
        }
    }
}
