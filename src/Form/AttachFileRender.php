<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Form;

enum AttachFileRender: string
{
    case IMAGE = 'image';
    case LINK = 'link';
    case NAME = 'name';
}
