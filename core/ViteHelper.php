<?php
declare(strict_types=1);

namespace Core;

/**
 * Vite Manifest Helper
 * 
 * Reads Vite's generated manifest.json and emits
 * the correct production CSS/JS tags.
 */
class ViteHelper
{
    private static ?array $manifest = null;
    private static string $manifestPath = __DIR__ . '/../public/build/.vite/manifest.json';

    public static function getManifest(): ?array
    {
        if (self::$manifest !== null) {
            return self::$manifest;
        }

        if (!file_exists(self::$manifestPath)) {
            return null;
        }

        $content = file_get_contents(self::$manifestPath);
        if ($content === false) {
            return null;
        }

        $manifest = json_decode($content, true);
        if (!is_array($manifest)) {
            return null;
        }

        self::$manifest = $manifest;
        return self::$manifest;
    }

    public static function asset(string $entry): string
    {
        $manifest = self::getManifest();
        if ($manifest === null) {
            return '';
        }

        if (!isset($manifest[$entry])) {
            return '';
        }

        $entryData = $manifest[$entry];
        $tags = '';

        // CSS files
        if (isset($entryData['css']) && is_array($entryData['css'])) {
            foreach ($entryData['css'] as $css) {
                $tags .= '<link rel="stylesheet" href="/build/' . htmlspecialchars($css, ENT_QUOTES) . '">' . "\n";
            }
        }

        // JS file
        if (isset($entryData['file'])) {
            $tags .= '<script type="module" src="/build/' . htmlspecialchars($entryData['file'], ENT_QUOTES) . '"></script>' . "\n";
        }

        return $tags;
    }

    public static function isDev(): bool
    {
        // Check if Vite dev server is running
        $context = stream_context_create(['http' => ['timeout' => 1]]);
        $result = @file_get_contents('http://localhost:5173', false, $context);
        return $result !== false;
    }

    public static function devTags(string $entry): string
    {
        if (!self::isDev()) {
            return self::asset($entry);
        }

        $tags = '';
        $tags .= '<script type="module" src="http://localhost:5173/@vite/client"></script>' . "\n";
        $tags .= '<script type="module" src="http://localhost:5173/src/main.js"></script>' . "\n";
        return $tags;
    }
}