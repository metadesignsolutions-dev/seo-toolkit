<?php namespace MetadesignSolutions\Mdsoctoberseo\Classes;

use Exception;
use File;

class HtaccessService
{
    /**
     * Get the content of the .htaccess file
     *
     * @return string
     * @throws Exception
     */
    public static function getContent()
    {
        $htaccessPath = base_path('.htaccess');
        
        if (!file_exists($htaccessPath)) {
            throw new Exception('.htaccess file not found');
        }

        if (!is_readable($htaccessPath)) {
            throw new Exception('.htaccess file is not readable');
        }

        return File::get($htaccessPath);
    }

    /**
     * Save content to the .htaccess file
     *
     * @param string $content
     * @return bool
     * @throws Exception
     */
    public static function saveContent($content)
    {
        $htaccessPath = base_path('.htaccess');
        
        if (!file_exists($htaccessPath)) {
            throw new Exception('.htaccess file not found');
        }

        if (!is_writable($htaccessPath)) {
            throw new Exception('.htaccess file is not writable');
        }

        // Create a backup of the current .htaccess file
        $backupPath = $htaccessPath . '.backup-' . date('Y-m-d-H-i-s');
        File::copy($htaccessPath, $backupPath);

        try {
            File::put($htaccessPath, $content);
            return true;
        } catch (Exception $e) {
            // Restore from backup if save fails
            if (file_exists($backupPath)) {
                File::copy($backupPath, $htaccessPath);
                File::delete($backupPath);
            }
            throw new Exception('Failed to save .htaccess file: ' . $e->getMessage());
        }
    }
} 