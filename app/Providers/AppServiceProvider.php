<?php

namespace App\Providers;

use App\File;
use App\Observers\FileObserver;
use App\Setting;
use Form;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //dynamic constants
        try {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                config(['settings.' . $setting->name => $setting->value]);
            }
            config(['settings_array.model_types_plural' => ['tags' => ucfirst(config('settings.tags_label_plural')), 'documents' => ucfirst(config('settings.document_label_plural')), 'files' => ucfirst(config('settings.file_label_plural'))]]);
        }catch (\Exception $e){}

        //laravel collective custom components
        Form::component('bsText', 'components.input', ['name', 'value' => null, 'attributes' => [], 'label' => null]);
        Form::component('bsTextarea', 'components.textarea', ['name', 'value' => null, 'attributes' => [], 'label' => null]);
        Form::component('bsSelect', 'components.select', ['name', 'list' => null, 'value'=>null, 'attributes' => [], 'label' => null]);

        //observer
        File::observe(FileObserver::class);
    }
}
