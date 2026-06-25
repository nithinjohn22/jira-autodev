<?php

declare(strict_types=1);

namespace App\Utils;

class StringHelper
{
    public static function truncate(string $text, int $limit, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return mb_substr($text, 0, $limit) . $suffix;
    }

    public static function slugify(string $text): string
    {
        $slug = mb_strtolower($text);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug;
    }
}
