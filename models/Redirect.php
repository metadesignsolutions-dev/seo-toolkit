<?php namespace MetadesignSolutions\Mdsoctoberseo\Models;

use Model;
use Validator;
use ValidationException;
use Lang;

class Redirect extends Model
{
    protected $table = 'mdsoctoberseo_redirects';

    protected $fillable = ['from_url', 'to_url', 'status_code', 'is_active'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            // Validate URLs
            $rules = [
                'from_url' => [
                    'required',
                    'regex:/^\/[a-zA-Z0-9\-\/]*$/',
                    'unique:mdsoctoberseo_redirects,from_url,' . $model->id
                ],
                'to_url' => [
                    'required',
                    'regex:/^\/[a-zA-Z0-9\-\/]*$/'
                ],
                'status_code' => [
                    'required',
                    'in:301,302'
                ]
            ];

            $messages = [
                'from_url.required' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.from_url_required'),
                'from_url.regex' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.from_url_format'),
                'from_url.unique' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.from_url_unique'),
                'to_url.required' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.to_url_required'),
                'to_url.regex' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.to_url_format'),
                'status_code.required' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.status_code_required'),
                'status_code.in' => Lang::get('metadesignsolutions.mdsoctoberseo::lang.validation.status_code_invalid')
            ];

            $validator = Validator::make($model->attributes, $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });
    }

    public function getStatusCodeOptions()
    {
        return [
            301 => Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect.status_301'),
            302 => Lang::get('metadesignsolutions.mdsoctoberseo::lang.redirect.status_302')
        ];
    }
}