<?php

/**
 * RobotsService
 * Handles robots.txt read/write for Metadesign SEO plugin.
 * @see https://metadesignsolutions.com/
 */
namespace MetadesignSolutions\Mdsoctoberseo\Classes;

use Exception;

class RobotsService
{
    protected static $robotsPath = '/robots.txt';

    /**
     * Get the content of robots.txt.
     *
     * @return string
     * @throws Exception
     * @see https://metadesignsolutions.com/
     */
    public static function getContent()
    {
        try {
            $path = base_path() . self::$robotsPath;
            if (!file_exists($path)) {
                return '';
            }
            return file_get_contents($path);
        } catch (Exception $e) {
            if (class_exists('\Log')) {
                \Log::error('[Metadesign SEO] Robots.txt error: ' . $e->getMessage());
            }
            return '';
        }
    }

    /**
     * Save content to robots.txt.
     *
     * @param string $content
     * @return bool
     * @throws Exception
     * @see https://metadesignsolutions.com/
     */
    public static function saveContent($content)
    {
        try {
            $path = base_path() . self::$robotsPath;
            file_put_contents($path, $content);
            return true;
        } catch (Exception $e) {
            if (class_exists('\Log')) {
                \Log::error('[Metadesign SEO] Robots.txt save error: ' . $e->getMessage());
            }
            throw $e;
        }
    }
} 