<?php

declare(strict_types=1);

/**
 * SeoScoreService
 * Analyzes SEO score for a page.
 * @see https://metadesignsolutions.com/
 */
namespace Metadesignsolutions\MdsOctoberSeo\Classes;

use Metadesignsolutions\MdsOctoberSeo\Classes\LicenseService;
use Metadesignsolutions\Mdsoctoberseo\Models\SeoSettings; // Import SeoSettings model
use Exception;

class SeoScoreService
{
    /**
     * Analyze SEO score for a page or settings object.
     *
     * @param object $object The object to analyze (Page model or SeoSettings model).
     * @return array{score: int, suggestions: array}
     * @throws Exception
     * @see https://metadesignsolutions.com/
     */
    public static function analyze($object)
    {
        if (!$object) {
            return [
                'score' => 0,
                'suggestions' => ['No data available for analysis']
            ];
        }

        $score = 0;
        $suggestions = [];

        // Determine which fields to check based on the object type
        $isSettings = $object instanceof \Metadesignsolutions\Mdsoctoberseo\Models\SeoSettings;
        $titleFields = $isSettings
            ? ['site_title', 'og_title', 'twitter_title']
            : ['seo_title', 'og_title', 'twitter_title'];
        $descriptionFields = $isSettings
            ? ['site_description', 'og_description', 'twitter_description']
            : ['seo_description', 'og_description', 'twitter_description'];
        $keywordsField = $isSettings ? 'meta_keywords' : 'seo_keywords';
        $imageFields = $isSettings
            ? ['default_image', 'og_image']
            : ['seo_image', 'og_image'];
        // Twitter image is not present in your model, but can be added if needed

        // Title check (20 points)
        $titleValue = '';
        foreach ($titleFields as $field) {
            if (!empty($object->{$field})) {
                $titleValue = $object->{$field};
                break;
            }
        }
        if ($titleValue) {
            $titleLength = strlen($titleValue);
            if ($titleLength >= 30 && $titleLength <= 60) {
                $score += 20;
            } else {
                $suggestions[] = 'Title length should be between 30-60 characters (SEO, OG, or Twitter)';
            }
        } else {
            $suggestions[] = 'Add a title (SEO, OG, or Twitter) for better analysis';
        }

        // Description check (20 points)
        $descValue = '';
        foreach ($descriptionFields as $field) {
            if (!empty($object->{$field})) {
                $descValue = $object->{$field};
                break;
            }
        }
        if ($descValue) {
            $descLength = strlen($descValue);
            if ($descLength >= 120 && $descLength <= 160) {
                $score += 20;
            } else {
                $suggestions[] = 'Description length should be between 120-160 characters (SEO, OG, or Twitter)';
            }
        } else {
            $suggestions[] = 'Add a description (SEO, OG, or Twitter) for analysis';
        }

        // Image check (20 points)
        $imageValue = '';
        foreach ($imageFields as $field) {
            if (!empty($object->{$field})) {
                $imageValue = $object->{$field};
                break;
            }
        }
        if ($imageValue) {
            $score += 20;
        } else {
            $suggestions[] = 'Add an image (SEO or OG) for analysis';
        }

        // Keywords check (15 points)
        if (!empty($object->{$keywordsField})) {
            $keywords = explode(',', $object->{$keywordsField});
            if (count($keywords) >= 1) {
                $score += 15;
            } else {
                $suggestions[] = 'Add at least one keyword';
            }
        } else {
            $suggestions[] = 'Add keywords for analysis';
        }

        // OG fields present (10 points)
        $ogFieldsPresent = !empty($object->og_title) && !empty($object->og_description) && !empty($object->og_image) && !empty($object->og_type);
        if ($ogFieldsPresent) {
            $score += 15;
        } else {
            $suggestions[] = 'Complete all Open Graph fields for best social sharing';
        }

        // Twitter fields present (10 points)
        $twitterFieldsPresent = !empty($object->twitter_title) && !empty($object->twitter_description) && !empty($object->twitter_card);
        if ($twitterFieldsPresent) {
            $score += 10;
        } else {
            $suggestions[] = 'Complete all Twitter Card fields for best sharing on X/Twitter';
        }

        // Clamp score to 100
        $score = min($score, 100);

        return [
            'score' => $score,
            'suggestions' => $suggestions
        ];
    }
} 