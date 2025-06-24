<?php namespace Metadesignsolutions\Mdsoctoberseo\Components;

use Cms\Classes\ComponentBase;
use Metadesignsolutions\Mdsoctoberseo\Models\SeoSettings;
use Lang;

class MetaTags extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.metatags.name'),
            'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.components.metatags.description')
        ];
    }

    public function onRun() 
    {
        $settings = SeoSettings::instance();
        $controller = $this->getController();
        
        // Try to get the most relevant model for meta tags
        $model = $this->getRelevantModel($controller);

        // Get SEO values with proper fallbacks
        $seoTitle = $this->getSeoTitle($model, $settings);
        $seoDescription = $this->getSeoDescription($model, $settings);
        $metaKeywords = $this->getSeoKeywords($model, $settings);

        // Get OG specific values, fallback to SEO values if not present
        $ogTitle = $this->getOgTitle($model, $seoTitle);
        $ogDescription = $this->getOgDescription($model, $seoDescription);
        $ogType = $this->getOgType($model);

        // Get Twitter specific values, fallback to SEO values if not present
        $twitterTitle = $this->getTwitterTitle($model, $seoTitle);
        $twitterDescription = $this->getTwitterDescription($model, $seoDescription);
        $twitterCard = $this->getTwitterCard($model);

        // Get OG image with fallbacks
        $ogImage = $this->getOgImage($model, $settings);

        $currentUrl = url()->current();

        // Generate schema JSON-LD
        $schemaJsonLd = $this->generateSchemaJsonLd($model, $settings, $seoTitle, $seoDescription, $currentUrl, $ogImage);

        // Build meta tags array
        $headTags = $this->buildMetaTags(
            $seoTitle, 
            $seoDescription, 
            $metaKeywords, 
            $ogTitle, 
            $ogDescription, 
            $ogType, 
            $currentUrl, 
            $ogImage, 
            $twitterTitle, 
            $twitterDescription, 
            $twitterCard,
            $schemaJsonLd
        );

        $this->page['metaTags'] = implode("\n    ", $headTags);
    }

    /**
     * Get the most relevant model for meta tags
     */
    protected function getRelevantModel($controller)
    {
        $vars = $controller->vars;
        
        // Check for common model variables in order of preference
        $modelKeys = ['wiki', 'post', 'entry', 'page', 'record', 'article', 'product'];
        
        foreach ($modelKeys as $key) {
            if (isset($vars[$key]) && is_object($vars[$key])) {
                return $vars[$key];
            }
        }
        
        // Fallback to CMS page
        return $controller->getPage();
    }

    /**
     * Get SEO title with fallbacks
     */
    protected function getSeoTitle($model, $settings)
    {
        return $model->seo_title 
            ?? ($model->attributes['seo_title'] ?? null)
            ?? $model->title 
            ?? ($model->attributes['title'] ?? null)
            ?? $settings->site_title 
            ?? '';
    }

    /**
     * Get SEO description with fallbacks
     */
    protected function getSeoDescription($model, $settings)
    {
        return $model->seo_description
            ?? ($model->attributes['seo_description'] ?? null)
            ?? $model->description
            ?? ($model->attributes['description'] ?? null)
            ?? $settings->site_description
            ?? '';
    }

    /**
     * Get SEO keywords with fallbacks
     */
    protected function getSeoKeywords($model, $settings)
    {
        return $model->seo_keywords
            ?? ($model->attributes['seo_keywords'] ?? null)
            ?? $settings->site_keywords
            ?? '';
    }

    /**
     * Get OG title with fallback to SEO title
     */
    protected function getOgTitle($model, $seoTitle)
    {
        return $model->og_title
            ?? ($model->attributes['og_title'] ?? null)
            ?? $seoTitle;
    }

    /**
     * Get OG description with fallback to SEO description
     */
    protected function getOgDescription($model, $seoDescription)
    {
        return $model->og_description
            ?? ($model->attributes['og_description'] ?? null)
            ?? $seoDescription;
    }

    /**
     * Get OG type
     */
    protected function getOgType($model)
    {
        return $model->og_type
            ?? ($model->attributes['og_type'] ?? null)
            ?? 'website';
    }

    /**
     * Get Twitter title with fallback to SEO title
     */
    protected function getTwitterTitle($model, $seoTitle)
    {
        return $model->twitter_title
            ?? ($model->attributes['twitter_title'] ?? null)
            ?? $seoTitle;
    }

    /**
     * Get Twitter description with fallback to SEO description
     */
    protected function getTwitterDescription($model, $seoDescription)
    {
        return $model->twitter_description
            ?? ($model->attributes['twitter_description'] ?? null)
            ?? $seoDescription;
    }

    /**
     * Get Twitter card type
     */
    protected function getTwitterCard($model)
    {
        return $model->twitter_card
            ?? ($model->attributes['twitter_card'] ?? null)
            ?? 'summary_large_image';
    }

    /**
     * Get OG image with fallbacks
     */
    protected function getOgImage($model, $settings)
    {
        if (!empty($model->og_image)) {
            return \Media\Classes\MediaLibrary::url($model->og_image);
        }
        
        if (!empty($model->attributes['og_image'])) {
            return \Media\Classes\MediaLibrary::url($model->attributes['og_image']);
        }
        
        if (!empty($settings->default_social_image)) {
            return \Media\Classes\MediaLibrary::url($settings->default_social_image);
        }
        
        return '';
    }

    /**
     * Generate schema JSON-LD
     */
    protected function generateSchemaJsonLd($model, $settings, $seoTitle, $seoDescription, $currentUrl, $ogImage)
    {
        $schemaJsonLd = $model->schema_jsonld ?? $settings->global_schema_jsonld ?? null;
        
        if ($schemaJsonLd) {
            return $schemaJsonLd;
        }

        // Generate default schema
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $seoTitle,
            'description' => $seoDescription,
            'url' => $currentUrl,
            'publisher' => [
                '@type' => 'Organization',
                'name' => $settings->site_name ?? '',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $ogImage ?? ''
                ]
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Build meta tags array
     */
    protected function buildMetaTags($seoTitle, $seoDescription, $metaKeywords, $ogTitle, $ogDescription, $ogType, $currentUrl, $ogImage, $twitterTitle, $twitterDescription, $twitterCard, $schemaJsonLd)
    {
        $headTags = [
            '<meta name="title" content="' . e($seoTitle) . '">',
            '<meta name="description" content="' . e($seoDescription) . '">',
            '<meta name="keywords" content="' . e($metaKeywords) . '">',
            '<meta property="og:title" content="' . e($ogTitle) . '">',
            '<meta property="og:description" content="' . e($ogDescription) . '">',
            '<meta property="og:type" content="' . e($ogType) . '">',
            '<meta property="og:url" content="' . e($currentUrl) . '">',
            '<meta name="twitter:card" content="' . e($twitterCard) . '">',
            '<meta name="twitter:title" content="' . e($twitterTitle) . '">',
            '<meta name="twitter:description" content="' . e($twitterDescription) . '">'
        ];

        // Add OG image tags if available
        if (!empty($ogImage)) {
            $headTags[] = '<meta property="og:image" content="' . e($ogImage) . '">';
            $headTags[] = '<meta name="twitter:image" content="' . e($ogImage) . '">';
        }

        // Add schema JSON-LD if available
        if (!empty($schemaJsonLd)) {
            $headTags[] = '<script type="application/ld+json">' . $schemaJsonLd . '</script>';
        }

        return $headTags;
    }
}