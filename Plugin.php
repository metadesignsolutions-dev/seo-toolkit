<?php namespace MetadesignSolutions\Mdsoctoberseo;

use Backend;
use BackendAuth;
use System\Classes\PluginBase;
use Event;
use Lang;
use Schema;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => Lang::get('metadesignsolutions.mdsoctoberseo::lang.plugin.name'),
            'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.plugin.description'),
            'author'      => 'MetaDesign Solutions',
            'icon'        => 'icon-search',
            'homepage'    => 'https://metadesignsolutions.com'
        ];
    }

    public function register()
    {
        // Register any custom services here
    }

    public function registerComponents()
    {
        return [
            'MetadesignSolutions\Mdsoctoberseo\Components\MetaTags' => 'metaTags',
            'MetadesignSolutions\Mdsoctoberseo\Components\SeoManager' => 'seoManager',
            'MetadesignSolutions\Mdsoctoberseo\Components\SchemaJsonLd' => 'schemaJsonLd',
            'MetadesignSolutions\Mdsoctoberseo\Components\SitemapGenerator' => 'sitemapGenerator'
        ];
    }

    public function registerPermissions()
    {
        return [
            'metadesignsolutions.mdsoctoberseo.manage_seo' => [
                'tab'   => Lang::get('metadesignsolutions.mdsoctoberseo::lang.permissions.tab'),
                'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.permissions.manage_seo')
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'documentation' => [
                'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.documentation.label'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.documentation.description'),
                'category' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.category'),
                'icon' => 'icon-book',
                'url' => Backend::url('metadesignsolutions/mdsoctoberseo/documentation'),
                'order' => 501,
                'keywords' => 'documentation guide manual help'
            ],
            'settings' => [
                'label'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.seo.label'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.seo.description'),
                'category'    => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.category'),
                'icon'        => 'icon-magic',
                'class'       => 'MetadesignSolutions\Mdsoctoberseo\Models\SeoSettings',
                'order'       => 501,
                'keywords'    => 'seo search engine optimization meta',
                'permissions' => ['metadesignsolutions.mdsoctoberseo.manage_seo'],
            ],
            'sitemap' => [
                'label'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.sitemap.label'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.sitemap.description'),
                'category'    => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.category'),
                'icon'        => 'icon-shield',
                'class'       => 'MetadesignSolutions\Mdsoctoberseo\Models\SitemapSettings',
                'order'       => 502,
                'permissions' => ['metadesignsolutions.mdsoctoberseo.manage_seo'],
            ],
            'redirectmanager' => [
                'label'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.redirects.label'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.redirects.description'),
                'category'    => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.category'),
                'icon'        => 'icon-random',
                'url'         => Backend::url('metadesignsolutions/mdsoctoberseo/redirectmanager'),
                'order'       => 503,
                'permissions' => ['metadesignsolutions.mdsoctoberseo.manage_seo'],
            ],
            'sitemapmanager' => [
                'label'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.sitemap_manager.label'),
                'description' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.sitemap_manager.description'),
                'category'    => Lang::get('metadesignsolutions.mdsoctoberseo::lang.settings.category'),
                'icon'        => 'icon-sitemap',
                'url'         => Backend::url('metadesignsolutions/mdsoctoberseo/sitemap'),
                'order'       => 504,
                'permissions' => ['metadesignsolutions.mdsoctoberseo.manage_seo'],
            ],
        ];
    }

    public function boot()
    {
        // Register the redirect middleware
        $this->app['Illuminate\Contracts\Http\Kernel']
             ->pushMiddleware(\MetadesignSolutions\Mdsoctoberseo\Middleware\RedirectMiddleware::class);

        // Register sitemap.xml and robots.txt routes
        Event::listen('cms.page.beforeDisplay', function($controller, $url, $page) {
            if ($url === 'sitemap.xml' || $url === 'robots.txt') {
                $component = new \MetadesignSolutions\Mdsoctoberseo\Components\SitemapGenerator($controller);
                return $component->onRun();
            }
        });

        // Extend backend forms with SEO fields
        Event::listen('backend.form.extendFields', function ($widget) {
            // Only for backend forms with a model
            if (!$widget->getController() || !$widget->model) {
                return;
            }

            $model = $widget->model;

            // Check if the model has SEO columns using multiple methods
            $seoColumns = ['seo_title', 'seo_description', 'seo_keywords'];
            $hasSeo = false;
            
            // Method 1: Check if model has getTable method and use Schema
            if (method_exists($model, 'getTable')) {
                $tableName = $model->getTable();
                foreach ($seoColumns as $col) {
                    if (Schema::hasColumn($tableName, $col)) {
                        $hasSeo = true;
                        break;
                    }
                }
            }
            
            // Method 2: Check if model has hasColumn method
            if (!$hasSeo && method_exists($model, 'hasColumn')) {
                foreach ($seoColumns as $col) {
                    if ($model->hasColumn($col)) {
                        $hasSeo = true;
                        break;
                    }
                }
            }
            
            // Method 3: Check if model has fillable array with SEO columns
            if (!$hasSeo && method_exists($model, 'getFillable')) {
                $fillable = $model->getFillable();
                foreach ($seoColumns as $col) {
                    if (in_array($col, $fillable)) {
                        $hasSeo = true;
                        break;
                    }
                }
            }
            
            // Method 4: Check if model has attributes property
            if (!$hasSeo && property_exists($model, 'attributes')) {
                foreach ($seoColumns as $col) {
                    if (array_key_exists($col, $model->attributes)) {
                        $hasSeo = true;
                        break;
                    }
                }
            }
            
            if (!$hasSeo) {
                return;
            }

            // Inject SEO fields/partials with validation
            $widget->addTabFields([
                // --- Preview & Analysis Section ---
                'seo_preview_section' => [
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'type' => 'section',
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.preview_section'),
                    'cssClass' => 'section-preview',
                    'span' => 'full'
                ],
                'snippet_preview' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.snippet_preview'),
                    'type' => 'partial',
                    'path' => '$/metadesignsolutions/mdsoctoberseo/models/_snippet_preview.htm',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.snippet_preview_comment'),
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'dependsOn' => ['seo_title', 'seo_description'],
                    'vars' => [
                        'formModel' => $widget->model
                    ]
                ],
                'seo_score' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_score'),
                    'type' => 'partial',
                    'path' => '$/metadesignsolutions/mdsoctoberseo/models/_seo_score.htm',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_score_comment'),
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'right',
                    'dependsOn' => ['seo_title', 'seo_description', 'seo_keywords', 'og_title', 'og_description', 'og_image', 'og_type', 'twitter_title', 'twitter_description', 'twitter_card'],
                    'vars' => [
                        'formModel' => $widget->model
                    ]
                ],
                // --- Content Section ---
                'seo_content_section' => [
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'type' => 'section',
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.content_section'),
                    'span' => 'full'
                ],
                'seo_title' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_title'),
                    'type' => 'text',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'size' => 'large',
                    'validation' => [
                        'required' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.seo_title_required'),
                        'regex' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.seo_title_length')
                    ],
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_title_comment'),
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'seo_title',
                        'condition' => 'value[filled]'
                    ]
                ],
                'seo_description' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_description'),
                    'type' => 'textarea',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'right',
                    'size' => 'large',
                    'validation' => [
                        'required' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.seo_description_required'),
                        'regex' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.seo_description_length')
                    ],
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_description_comment'),
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'seo_description',
                        'condition' => 'value[filled]'
                    ]
                ],
                'seo_keywords' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_keywords'),
                    'type' => 'text',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'validation' => [
                        'message' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.seo_keywords_format')
                    ],
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_keywords_comment')
                ],
                // --- Social Media Section ---
                'seo_social_section' => [
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'type' => 'section',
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.social_section'),
                    'span' => 'full'
                ],
                'og_title' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_title'),
                    'type' => 'text',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_title_comment')
                ],
                'og_image' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_image'),
                    'type' => 'fileupload',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'right',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_image_comment')
                ],
                'og_description' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_description'),
                    'type' => 'textarea',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_description_comment')
                ],
                'og_type' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type'),
                    'type' => 'dropdown',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'right',
                    'options' => [
                        'website' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_website'),
                        'article' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_article'),
                        'profile' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_profile'),
                        'book' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_book'),
                        'video' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_video')
                    ],
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.og_type_comment')
                ],
                'twitter_title' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_title'),
                    'type' => 'text',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_title_comment')
                ],
                'twitter_description' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_description'),
                    'type' => 'textarea',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'right',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_description_comment')
                ],
                'twitter_card' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card'),
                    'type' => 'dropdown',
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'left',
                    'options' => [
                        'summary' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card_summary'),
                        'summary_large_image' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card_summary_large'),
                        'app' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card_app'),
                        'player' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card_player')
                    ],
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.twitter_card_comment')
                ],
                // --- Schema Section ---
                'seo_schema_section' => [
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'type' => 'section',
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.schema_section'),
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.schema_section_comment'),
                    'span' => 'full'
                ],
                'schema_json_ld' => [
                    'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.schema_json_ld'),
                    'type' => 'partial',
                    'path' => '$/metadesignsolutions/mdsoctoberseo/models/_schema_jsonld_preview.htm',
                    'comment' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.schema_json_ld_comment'),
                    'tab' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.fields.seo_tab'),
                    'span' => 'full',
                    'dependsOn' => ['seo_title', 'seo_description', 'seo_keywords', 'og_title', 'og_description', 'og_image', 'og_type', 'twitter_title', 'twitter_description', 'twitter_card'],
                    'vars' => [
                        'formModel' => $widget->model
                    ]
                ]
            ]);
        });

        // Add backend assets
        Event::listen('backend.page.beforeDisplay', function($controller, $action, $params) {
            if (BackendAuth::getUser()) {
                $controller->addCss('/plugins/metadesignsolutions/mdsoctoberseo/assets/css/seo-settings.css', [
                    'build' => 'v1.0.4'
                ]);

                $controller->addJs('/plugins/metadesignsolutions/mdsoctoberseo/assets/js/seo-validation.js', [
                    'build' => 'v1.0.4',
                    'require' => [
                        'jquery',
                        'bootstrap',
                        'framework',
                        'framework.extras'
                    ]
                ]);
            }
        });
    }

    public function registerNavigation()
    {
        return [
            'mdsoctoberseo' => [
                'label'       => Lang::get('metadesignsolutions.mdsoctoberseo::lang.navigation.main_menu'),
                'url'         => Backend::url('metadesignsolutions/mdsoctoberseo/seo'),
                'icon'        => 'icon-search',
                'permissions' => ['metadesignsolutions.mdsoctoberseo.manage_seo'],
                'order'       => 500,
                'sideMenu' => [
                    'seo' => [
                        'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.navigation.seo_manager'),
                        'icon'  => 'icon-search',
                        'url'   => Backend::url('metadesignsolutions/mdsoctoberseo/seo'),
                    ],
                    'redirectmanager' => [
                        'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.navigation.redirect_manager'),
                        'icon'  => 'icon-random',
                        'url'   => Backend::url('metadesignsolutions/mdsoctoberseo/redirectmanager'),
                    ],
                    'sitemap' => [
                        'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.navigation.sitemap_manager'),
                        'icon'  => 'icon-sitemap',
                        'url'   => Backend::url('metadesignsolutions/mdsoctoberseo/sitemap'),
                    ],
                    'documentation' => [
                        'label' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.navigation.documentation'),
                        'icon'  => 'icon-book',
                        'url'   => Backend::url('metadesignsolutions/mdsoctoberseo/documentation'),
                    ]
                ]
            ]
        ];
    }
}