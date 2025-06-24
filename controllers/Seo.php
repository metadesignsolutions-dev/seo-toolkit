<?php namespace Metadesignsolutions\Mdsoctoberseo\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Lang;

class Seo extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    /**
     * @var string formConfig file
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string listConfig file
     */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Metadesignsolutions.Mdsoctoberseo', 'mdsoctoberseo');
        $this->pageTitle = Lang::get('metadesignsolutions.mdsoctoberseo::lang.seo_manager.title');
    }

    // Method to handle index action
    public function index()
    {
        // Logic for listing SEO settings
        $this->asExtension('ListController')->index();
    }

    // Method to handle create action
    public function create()
    {
        // Logic for creating new SEO settings
        $this->asExtension('FormController')->create();
    }

    // Method to handle preview action
    public function preview($recordId)
    {
        // Logic for previewing SEO settings
        $this->asExtension('FormController')->preview($recordId);
    }

    // Method to handle update action
    public function update($recordId)
    {
        // Logic for updating existing SEO settings
        $this->asExtension('FormController')->update($recordId);
    }
}