<?php namespace Metadesignsolutions\Mdsoctoberseo\Components;

use Cms\Classes\ComponentBase;
use Metadesignsolutions\Mdsoctoberseo\Models\SeoSettings;
use Lang;

class SeoManager extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.seomanager.name'),
            'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.seomanager.description')
        ];
    }

    public function defineProperties()
    {
        return [
            'usePageMeta' => [
                'title'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.seomanager.use_page_meta'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.seomanager.use_page_meta_desc'),
                'type'        => 'checkbox',
                'default'     => true
            ]
        ];
    }

    public function onRun()
    {
        $settings = SeoSettings::instance();
        $this->page['seoSettings'] = $settings;

        // Get the current page
        $page = $this->getPage();

        // Set default meta data
        $this->page['meta_title'] = $settings->site_title;
        $this->page['meta_description'] = $settings->site_description;
        $this->page['meta_keywords'] = $settings->meta_keywords;
        $this->page['og_image'] = $settings->default_image;

        // If usePageMeta is enabled and we have page-specific meta data, use that instead
        if ($this->property('usePageMeta') && $page) {
            if (!empty($page->seo_title)) {
                $this->page['meta_title'] = $page->seo_title;
            }
            if (!empty($page->seo_description)) {
                $this->page['meta_description'] = $page->seo_description;
            }
            if (!empty($page->seo_keywords)) {
                $this->page['meta_keywords'] = $page->seo_keywords;
            }
            if (!empty($page->seo_image)) {
                $this->page['og_image'] = $page->seo_image;
            }
        }
    }

    public function getDefaultTitle()
    {
        return $this->page['meta_title'];
    }

    public function getDefaultDescription()
    {
        return $this->page['meta_description'];
    }

    public function getDefaultKeywords()
    {
        return $this->page['meta_keywords'];
    }

    public function getOgImage()
    {
        return $this->page['og_image'];
    }

    public function getSeoTitle()
    {
        if ($this->entry) {
            return $this->entry->seo_title;
        }
        return $this->page->seo_title;
    }

    public function getSeoDescription()
    {
        if ($this->entry) {
            return $this->entry->seo_description;
        }
        return $this->page->seo_description;
    }

    public function getSeoKeywords()
    {
        if ($this->entry) {
            return $this->entry->seo_keywords;
        }
        return $this->page->seo_keywords;
    }

    public function getSeoImage()
    {
        if ($this->entry) {
            return $this->entry->seo_image;
        }
        return $this->page->seo_image;
    }
}