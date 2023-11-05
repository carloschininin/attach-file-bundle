<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Exception;

final class FileNotFoundException extends \RuntimeException
{
    public function __construct(string $message = 'File not found')
    {
        parent::__construct($message);
    }
}
