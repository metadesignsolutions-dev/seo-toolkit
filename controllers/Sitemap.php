<?php namespace MetadesignSolutions\Mdsoctoberseo\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use MetadesignSolutions\Mdsoctoberseo\Models\Definition;
use MetadesignSolutions\Mdsoctoberseo\Classes\DefinitionItem;
use Cache;
use Flash;
use File;
use Response;
use Lang;

class Sitemap extends Controller
{
    public $implement = [
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $formActionsConfig = 'config_actions.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MetadesignSolutions.Mdsoctoberseo', 'mdsoctoberseo', 'sitemap');
        $this->pageTitle = Lang::get('metadesignsolutions.mdsoctoberseo::lang.sitemap_manager.title');
    }

    public function onGenerateSitemap()
    {
        try {
            $definitions = Definition::all();
            $finalXml = null;

            foreach ($definitions as $row) {
                $item = new DefinitionItem();
                $item->type = $row->type;
                $item->url = $row->url;
                $item->reference = $row->reference;
                $item->cmsPage = $row->cmsPage;
                $item->changefreq = $row->changefreq;
                $item->priority = $row->priority;

                $definition = new Definition();
                $definition->items = [$item];
                $xmlString = $definition->generateSitemap();

                if ($xmlString) {
                    $doc = new \DOMDocument();
                    $doc->loadXML($xmlString);

                    if (!$finalXml) {
                        $finalXml = $doc;
                    } else {
                        foreach ($doc->getElementsByTagName('url') as $urlElement) {
                            $imported = $finalXml->importNode($urlElement, true);
                            $finalXml->documentElement->appendChild($imported);
                        }
                    }
                }
            }

            if ($finalXml) {
                File::put(base_path('sitemap.xml'), $finalXml->saveXML());
                Cache::put('sitemap.xml', $finalXml->saveXML(), 1440);
                Flash::success(Lang::get('metadesignsolutions.mdsoctoberseo::lang.sitemap_manager.generated_success'));
            } else {
                Flash::error(Lang::get('metadesignsolutions.mdsoctoberseo::lang.sitemap_manager.no_data'));
            }
        } catch (\Exception $e) {
            Flash::error(Lang::get('metadesignsolutions.mdsoctoberseo::lang.sitemap_manager.generation_error'));
        }

        return $this->asExtension('FormController')->update();
    }
} 