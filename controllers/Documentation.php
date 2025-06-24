<?php namespace Metadesignsolutions\Mdsoctoberseo\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Lang;

class Documentation extends Controller
{
    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Metadesignsolutions.Mdsoctoberseo', 'mdsoctoberseo');
        $this->pageTitle = 'Documentation';
    }

    public function index()
    {
        $this->vars['title'] = 'Best SEO Toolkit Documentation';
        $this->vars['description'] = 'Complete user guide and documentation for Best SEO Toolkit plugin.';
    }
} 