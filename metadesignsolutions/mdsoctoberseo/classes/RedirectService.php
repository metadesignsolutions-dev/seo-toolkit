<?php

/**
 * RedirectService
 * Handles redirect logic for Metadesign SEO plugin.
 */
namespace Metadesignsolutions\Mdsoctoberseo\Classes;

use Metadesignsolutions\Mdsoctoberseo\Models\Redirect;
use Exception;

class RedirectService
{
    /**
     * Find a redirect for a given URL.
     *
     * @param string $url
     * @return array|null
     * @throws Exception
     */
    public static function findRedirect($url)
    {
        try {
            $redirect = Redirect::where('from_url', $url)->first();
            if ($redirect) {
                return [
                    'to' => $redirect->to_url,
                    'type' => $redirect->type,
                ];
            }
            return null;
        } catch (Exception $e) {
            if (class_exists('\Log')) {
                \Log::error('[Metadesign SEO] Redirect error: ' . $e->getMessage());
            }
            return null;
        }
    }
} 