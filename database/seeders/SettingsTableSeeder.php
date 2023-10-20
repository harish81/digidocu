<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Setting::create(['name'=>'system_title','value'=>'Digi Docu']);
        \App\Setting::create(['name'=>'system_logo','value'=>'logo.png']);

        \App\Setting::create(['name'=>'tags_label_singular','value'=>'tag']);
        \App\Setting::create(['name'=>'tags_label_plural','value'=>'tags']);

        \App\Setting::create(['name'=>'document_label_singular','value'=>'document']);
        \App\Setting::create(['name'=>'document_label_plural','value'=>'documents']);

        \App\Setting::create(['name'=>'file_label_singular','value'=>'file']);
        \App\Setting::create(['name'=>'file_label_plural','value'=>'files']);

        \App\Setting::create(['name'=>'default_file_validations','value'=>'mimes:jpeg,bmp,png,jpg']);
        \App\Setting::create(['name'=>'default_file_maxsize','value'=>'8']);

        \App\Setting::create(['name'=>'image_files_resize','value'=>'300,500,700']);

        \App\Setting::create(['name'=>'show_missing_files_errors','value'=>'true']);
    }
}
