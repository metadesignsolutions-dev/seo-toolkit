<?php namespace MetadesignSolutions\Mdsoctoberseo\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Lang;
use Backend\Behaviors\FormController;
use Backend\Behaviors\RelationController;
use Backend\Behaviors\ToolbarController;
use Backend\Behaviors\FilterController;
use Backend\Behaviors\ReorderController;
use Backend\Behaviors\FormController as BackendFormController;
use Backend\Behaviors\RelationController as BackendRelationController;
use Backend\Behaviors\ToolbarController as BackendToolbarController;
use Backend\Behaviors\FilterController as BackendFilterController;
use Backend\Behaviors\ReorderController as BackendReorderController;

class Documentation extends Controller
{
    public $implement = [
        BackendFormController::class,
        BackendRelationController::class,
        BackendToolbarController::class,
        BackendFilterController::class,
        BackendReorderController::class,
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('MetadesignSolutions.Mdsoctoberseo', 'mdsoctoberseo');
        $this->pageTitle = 'Documentation';
    }

    public function index()
    {
        $this->vars['title'] = 'Best SEO Toolkit Documentation';
        $this->vars['description'] = 'Complete user guide and documentation for Best SEO Toolkit plugin.';
    }
} 