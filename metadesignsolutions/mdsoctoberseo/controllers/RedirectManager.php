<?php namespace Metadesignsolutions\Mdsoctoberseo\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Metadesignsolutions\Mdsoctoberseo\Models\Redirect;
use Flash;
use Input;
use Response;
use Lang;

class RedirectManager extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ImportExportController'
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $importExportConfig = 'config_import_export.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Metadesignsolutions.Mdsoctoberseo', 'mdsoctoberseo', 'redirectmanager');
        $this->pageTitle = Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.title');
    }

    public function import()
    {
        $this->pageTitle = Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.import_title');
    }

    public function importcsv()
    {
        $file = Input::file('importFile');
        if (!$file) {
            Flash::error(Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.no_file'));
            return \Redirect::back();
        }

        try {
            $csvData = file_get_contents($file->getRealPath());
            $lines = explode(PHP_EOL, $csvData);
            $header = str_getcsv(array_shift($lines));
            
            $updated = 0;
            $created = 0;
    
            foreach ($lines as $line) {
                if (trim($line) === '') continue;
                $data = str_getcsv($line);
                if (count($data) == count($header)) {
                    $redirectData = array_combine($header, $data);
                    
                    // Convert is_active to integer
                    if (isset($redirectData['is_active'])) {
                        $val = strtolower(trim($redirectData['is_active']));
                        $redirectData['is_active'] = ($val === 'true' || $val === '1') ? 1 : 0;
                    }
    
                    // Check if redirect already exists
                    $existing = Redirect::where('from_url', $redirectData['from_url'])->first();
                    
                    if ($existing) {
                        $existing->update($redirectData);
                        $updated++;
                    } else {
                        Redirect::create($redirectData);
                        $created++;
                    }
                }
            }
            
            Flash::success(Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.import_success', [
                'created' => $created,
                'updated' => $updated
            ]));
        } catch (\Exception $e) {
            Flash::error(Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.import_error'));
        }
        
        return \Redirect::back();
    }

    public function exportcsv()
    {
        try {
            $redirects = Redirect::all();
            $filename = 'redirects_' . date('Y-m-d') . '.csv';
        
            $handle = fopen('php://temp', 'r+');
            // Add BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['from_url', 'to_url', 'status_code', 'is_active']);
            foreach ($redirects as $redirect) {
                fputcsv($handle, [
                    $redirect->from_url,
                    $redirect->to_url,
                    $redirect->status_code,
                    $redirect->is_active
                ]);
            }
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
        
            return Response::make($content)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            Flash::error(Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect_manager.export_error'));
            return \Redirect::back();
        }
    }
}