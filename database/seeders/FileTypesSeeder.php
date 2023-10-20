<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FileTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\FileType::create([
            'name' => 'General',
            'no_of_files' => 2,
            'labels' => 'page1,page2',
            'file_validations' => 'mimes:jpeg,bmp,png,jpg',
            'file_maxsize' => 8
        ]);
    }
}
