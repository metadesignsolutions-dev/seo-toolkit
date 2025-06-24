<?php

/**
 * SitemapService
 * Generates XML sitemap for SEO.
 */
namespace MetadesignSolutions\Mdsoctoberseo\Classes;

use Metadesignsolutions\Mdsoctoberseo\Models\SeoSettings;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Exception;

class SitemapService
{
    /**
     * Generate XML sitemap for the site.
     *
     * @return string
     * @throws Exception
     */
    public static function generateSitemap()
    {
        try {
            $theme = Theme::getActiveTheme();
            $pages = Page::listInTheme($theme, true);
            $baseUrl = SeoSettings::instance()->site_url ?: url('/');
            $xml = [];
            $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
            foreach ($pages as $page) {
                $xml[] = '  <url>';
                $xml[] = '    <loc>' . e($baseUrl . $page->url) . '</loc>';
                $xml[] = '    <changefreq>weekly</changefreq>';
                $xml[] = '    <priority>0.5</priority>';
                $xml[] = '  </url>';
            }
            $xml[] = '</urlset>';
            return implode("\n", $xml);
        } catch (Exception $e) {
            if (class_exists('\Log')) {
                \Log::error('[Metadesign SEO] Sitemap error: ' . $e->getMessage());
            }
            return '<!-- Sitemap error: ' . e($e->getMessage()) . ' -->';
        }
    }
} 
 