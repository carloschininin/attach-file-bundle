<?php

declare(strict_types=1);

/*
 * This file is part of the PIDIA.
 * (c) Carlos Chininin <cio@pidia.pe>
 */

namespace CarlosChininin\AttachFile\Helper;

final class FileHelper
{
    /**
     * Limpia y formatea un nombre de archivo eliminando caracteres peligrosos.
     *
     * @param string|null $filename  Nombre original del archivo
     * @param int         $maxLength Longitud máxima permitida (default: 255)
     *
     * @return string|null Nombre del archivo sanitizado
     */
    public static function sanitizeFilename(?string $filename, int $maxLength = 255): ?string
    {
        if (empty($filename)) {
            return null;
        }

        $filename = self::cleanFilename($filename);
        [$name, $extension] = self::extractNameExtension($filename);
        $name = self::reduceLength($name, $extension, $maxLength);
        $name = self::cleanReservedName($name);
        $filename = $name.(empty($extension) ? '' : '.'.$extension);

        return mb_strtolower($filename);
    }

    /**
     * Limpia y formatea un nombre de carpeta eliminando caracteres peligrosos.
     *
     * @param string|null $folderName Nombre original de la carpeta
     * @param int         $maxLength  Longitud máxima permitida (default: 255)
     *
     * @return string|null Nombre de la carpeta sanitizado
     */
    public static function sanitizeFolder(?string $folderName, int $maxLength = 255): ?string
    {
        if (empty($folderName)) {
            return null;
        }

        $folderName = self::cleanFolder($folderName);

        if (mb_strlen($folderName) > $maxLength) {
            $folderName = mb_substr($folderName, 0, $maxLength);
        }

        $folderName = self::cleanReservedName($folderName);

        return mb_strtolower($folderName);
    }

    /**
     * Elimina caracteres peligrosos y normaliza.
     */
    public static function cleanFilename(string $filename): string
    {
        $clean = preg_replace('/[<>:"|?*\/\\\\]/', '_', $filename);
        $clean = preg_replace('/[\x00-\x1F\x7F]/', '', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = str_replace(['../', '..\\'], '', $clean);

        return mb_trim($clean, '. ');
    }

    public static function cleanFolder(string $name): string
    {
        $clean = preg_replace('/[<>:"|?*\\\\]/', '_', $name);
        $clean = preg_replace('/[\x00-\x1F\x7F]/', '', $clean);
        $clean = preg_replace('/\s+/', ' ', $clean);
        $clean = str_replace(['..\\'], '', $clean);

        return mb_trim($clean, '. ');
    }

    public static function extractNameExtension(string $filename): array
    {
        $name = pathinfo($filename, \PATHINFO_FILENAME);
        $extension = pathinfo($filename, \PATHINFO_EXTENSION);

        return [$name, $extension];
    }

    /**
     * Trunca si es necesario preservando la extensión.
     */
    public static function reduceLength(string $name, string $extension, int $maxLength): string
    {
        if ((mb_strlen($name) + mb_strlen($extension)) > $maxLength) {
            $maxNameLength = $maxLength - mb_strlen($extension) - (empty($extension) ? 0 : 1) - 2;
            $name = mb_substr($name, 0, $maxNameLength).rand(10, 99);
        }

        return $name;
    }

    /**
     * Evita nombres reservados del sistema.
     */
    public static function cleanReservedName(string $name): string
    {
        $reservedNames = ['CON', 'PRN', 'AUX', 'NUL'];
        if (\in_array(mb_strtoupper($name), $reservedNames, true)) {
            return $name.'x';
        }

        return $name;
    }
}
