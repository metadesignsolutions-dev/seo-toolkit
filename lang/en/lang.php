<?php

return [
    'plugin' => [
        'name' => 'SEO Toolkit',
        'description' => 'Advanced SEO and redirection toolkit with robots.txt and sitemap control'
    ],
    'permissions' => [
        'tab' => 'MDS October SEO',
        'manage_seo' => 'Manage SEO settings'
    ],
    'settings' => [
        'category' => 'Search Optimization',
        'post_types' => [
            'cms_pages' => 'CMS Pages',
            'blog_posts' => 'Blog Posts',
            'static_pages' => 'Static Pages',
            'news_posts' => 'News Posts',
            'tailor_entries' => 'Tailor Entries'
        ],
        'documentation' => [
            'label' => 'Documentation',
            'description' => 'How to use the Best SEO Toolkit plugin and its features.'
        ],
        'seo' => [
            'label' => 'SEO Meta Manager',
            'description' => 'Manage meta tags, Open Graph, Twitter cards, and schema markup.'
        ],
        'sitemap' => [
            'label' => 'Sitemap Settings',
            'description' => 'Configure sitemap generation and robots.txt settings.'
        ],
        'redirects' => [
            'label' => 'Redirect Manager',
            'description' => 'Handle 301/302 redirects to improve SEO health and fix broken links.'
        ],
        'sitemap_manager' => [
            'label' => 'Sitemap Manager',
            'description' => 'Create, preview, and submit dynamic XML sitemaps to search engines.'
        ]
    ],
    'navigation' => [
        'label' => 'SEO Toolkit'
    ],
    'seo_manager' => [
        'title' => 'SEO Manager'
    ],
    'redirect_manager' => [
        'title' => 'Redirect Manager',
        'import_title' => 'Import Redirects',
        'no_file' => 'Please upload a valid CSV file.',
        'import_success' => 'Import successful. Created: :created, Updated: :updated',
        'import_error' => 'Error importing CSV file. Please check the file format.',
        'export_error' => 'Error exporting CSV file.'
    ],
    'sitemap_manager' => [
        'title' => 'Sitemap Manager',
        'generated_success' => 'Sitemap generated successfully',
        'no_data' => 'No sitemap data generated',
        'generation_error' => 'Error generating sitemap'
    ],
    'components' => [
        'metatags' => [
            'name' => 'Meta Tags',
            'description' => 'Injects SEO meta tags into the page'
        ],
        'seomanager' => [
            'name' => 'SEO Manager',
            'description' => 'Manages SEO settings for the current page',
            'use_page_meta' => 'Use Page Meta',
            'use_page_meta_desc' => 'Use page-specific meta data if available'
        ],
        'schemajsonld' => [
            'name' => 'Schema JSON-LD',
            'description' => 'Outputs schema.org JSON-LD structured data'
        ],
        'sitemapgenerator' => [
            'name' => 'Sitemap Generator',
            'description' => 'Generates sitemap.xml and robots.txt'
        ]
    ],
    'fields' => [
        'seo_tab' => 'SEO',
        'preview_section' => 'Preview & Analysis',
        'content_section' => 'SEO Content',
        'social_section' => 'Social Media',
        'schema_section' => 'Schema.org (Advanced)',
        'snippet_preview' => 'Search Result Preview',
        'snippet_preview_comment' => 'How your site appears in search results',
        'seo_score' => 'SEO Score',
        'seo_score_comment' => 'Current SEO score based on your settings',
        'seo_title' => 'SEO Title',
        'seo_title_comment' => 'Recommended length: 30-60 characters',
        'seo_description' => 'Meta Description',
        'seo_description_comment' => 'Recommended length: 120-160 characters',
        'seo_keywords' => 'Keywords',
        'seo_keywords_comment' => 'Comma-separated keywords (max 255 characters)',
        'og_title' => 'OG Title',
        'og_title_comment' => 'Open Graph title for social sharing',
        'og_image' => 'OG Image',
        'og_image_comment' => 'Open Graph image for social sharing',
        'og_description' => 'OG Description',
        'og_description_comment' => 'Open Graph description for social sharing',
        'og_type' => 'OG Type',
        'og_type_comment' => 'Open Graph type',
        'og_type_website' => 'Website',
        'og_type_article' => 'Article',
        'og_type_profile' => 'Profile',
        'og_type_book' => 'Book',
        'og_type_video' => 'Video',
        'twitter_title' => 'X Title',
        'twitter_title_comment' => 'X Card title (overrides OG title on X)',
        'twitter_description' => 'X Description',
        'twitter_description_comment' => 'X Card description (overrides OG description on X)',
        'twitter_card' => 'X Card Type',
        'twitter_card_comment' => 'Type of X Card',
        'twitter_card_summary' => 'Summary',
        'twitter_card_summary_large' => 'Summary Large Image',
        'twitter_card_app' => 'App',
        'twitter_card_player' => 'Player',
        'schema_jsonld' => 'Page Schema JSON-LD',
        'schema_jsonld_comment' => 'Paste custom JSON-LD for this page (optional)',
        'schema_section_comment' => 'Paste custom JSON-LD for this page (overrides global setting)'
    ],
    'validation' => [
        'seo_title_required' => 'The SEO title field is required',
        'seo_title_length' => 'Title must be between 30-60 characters',
        'seo_description_required' => 'The meta description field is required',
        'seo_description_length' => 'Description must be between 120-160 characters',
        'seo_keywords_format' => 'Keywords must be comma-separated and not exceed 255 characters',
        'from_url_required' => 'The from URL field is required',
        'from_url_format' => 'From URL must be a valid path starting with /',
        'from_url_unique' => 'This from URL already exists',
        'to_url_required' => 'The to URL field is required',
        'to_url_format' => 'To URL must be a valid path starting with /',
        'status_code_required' => 'The status code field is required',
        'status_code_invalid' => 'Status code must be either 301 or 302'
    ],
    'redirect' => [
        'status_301' => '301 - Permanent Redirect',
        'status_302' => '302 - Temporary Redirect'
    ]
]; 