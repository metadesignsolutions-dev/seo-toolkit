<?php namespace Metadesignsolutions\Mdsoctoberseo\Components;

use Cms\Classes\ComponentBase;
use Cache;
use Metadesignsolutions\Mdsoctoberseo\Models\SitemapSettings;
use Response;
use Lang;

class SitemapGenerator extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.sitemapgenerator.name'),
            'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.sitemapgenerator.description')
        ];
    }

    public function onRun()
    {
        $url = $this->page->url;
        
        if ($url === '/sitemap.xml') {
            return $this->generateSitemap();
        } elseif ($url === '/robots.txt') {
            return $this->generateRobotsTxt();
        }
    }

    protected function generateSitemap()
    {
        $sitemap = Cache::remember('sitemap.xml', 1440, function() {
            $settings = SitemapSettings::instance();
            return $settings->generateSitemap();
        });

        return Response::make($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Content-Length' => strlen($sitemap)
        ]);
    }

    protected function generateRobotsTxt()
    {
        $robots = Cache::remember('robots.txt', 1440, function() {
            $settings = SitemapSettings::instance();
            return $settings->generateRobotsTxt();
        });

        return Response::make($robots, 200, [
            'Content-Type' => 'text/plain',
            'Content-Length' => strlen($robots)
        ]);
    }
} 