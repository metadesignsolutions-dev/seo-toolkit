<?php namespace MetadesignSolutions\Mdsoctoberseo\Components;

use Cms\Classes\ComponentBase;
use MetadesignSolutions\Mdsoctoberseo\Models\SeoSettings;
use Lang;

class SchemaJsonLd extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.schemajsonld.name'),
            'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.schemajsonld.description')
        ];
    }

    public function onRun()
    {
        $settings = SeoSettings::instance();

        // Try to get the model from controller vars
        $model = $this->controller->vars['post']
            ?? $this->controller->vars['entry']
            ?? $this->controller->vars['page']
            ?? $this->controller->vars['record']
            ?? null;

        // Page-specific first, then fallback to global
        $siteTitle = $model->seo_title ?? $model->title ?? $settings->site_title ?? '';
        $siteDescription = $model->seo_description ?? $model->description ?? $settings->site_description ?? '';
        $metaKeywords = $model->seo_keywords ?? $settings->site_keywords ?? '';
        $ogTitle = $model->og_title ?? $siteTitle;
        $ogDescription = $model->og_description ?? $siteDescription;
        $ogImage = !empty($model->og_image) ? \Media\Classes\MediaLibrary::url($model->og_image) : (!empty($settings->default_social_image) ? \Media\Classes\MediaLibrary::url($settings->default_social_image) : '');
        $twitterTitle = $model->twitter_title ?? $ogTitle;
        $twitterDescription = $model->twitter_description ?? $ogDescription;
        $twitterCard = $model->twitter_card ?? $settings->twitter_card ?? 'summary_large_image';

        // Schema JSON-LD: page-specific, then global, then default
        $schemaJsonLd = $model->schema_jsonld
            ?? $settings->global_schema_jsonld
            ?? json_encode([
                '@context' => 'https://schema.org',
                '@type' => 'WebPage',
                'name' => $siteTitle,
                'description' => $siteDescription,
                'url' => url()->current(),
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $settings->site_name ?? '',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => $ogImage ?? ''
                    ]
                ]
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        $this->page['seoMeta'] = [
            'site_title' => $siteTitle,
            'site_description' => $siteDescription,
            'meta_keywords' => $metaKeywords,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'og_type' => 'website',
            'schema_custom_jsonld' => $schemaJsonLd,
            'twitter_title' => $twitterTitle,
            'twitter_description' => $twitterDescription,
            'twitter_card' => $twitterCard,
        ];
    }
}
