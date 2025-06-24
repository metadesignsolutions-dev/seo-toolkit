<?php namespace Metadesignsolutions\Mdsoctoberseo\Models;

use Model;
use Config;
use Lang;

class SeoSettings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'metadesignsolutions_mdsoctoberseo_settings';

    public $settingsFields = 'fields.yaml';

    protected $cache = [];

    public function getPostTypeOptions()
    {
        return [
            'show_seo_cms_pages' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.post_types.cms_pages'),
            'show_seo_blog_posts' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.post_types.blog_posts'),
            'show_seo_static_pages' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.post_types.static_pages'),
            'show_seo_news_posts' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.post_types.news_posts'),
            'show_seo_tailor_entries' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.post_types.tailor_entries')
        ];
    }

    public function getHtaccessContent()
    {
        $htaccessPath = base_path('.htaccess');
        if (file_exists($htaccessPath)) {
            return file_get_contents($htaccessPath);
        }
        return '';
    }
} 