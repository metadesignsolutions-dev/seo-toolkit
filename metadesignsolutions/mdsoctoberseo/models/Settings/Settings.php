<?php namespace Metadesignsolutions\Mdsoctoberseo\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    public $settingsCode = 'bestseotoolkit_settings';
    public $settingsFields = 'fields.yaml';
}
