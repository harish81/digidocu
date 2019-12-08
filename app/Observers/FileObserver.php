<?php

namespace App\Observers;

use App\File;

class FileObserver
{
    /**
     * Handle the file "created" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function created(File $file)
    {
        $doc = $file->document;
        $doc->updated_at = now();
        $doc->save();
    }

    /**
     * Handle the file "updated" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function updated(File $file)
    {
        //
    }

    /**
     * Handle the file "deleted" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function deleted(File $file)
    {
        $doc = $file->document;
        $doc->updated_at = now();
        $doc->save();
    }

    /**
     * Handle the file "restored" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function restored(File $file)
    {
        //
    }

    /**
     * Handle the file "force deleted" event.
     *
     * @param  \App\File  $file
     * @return void
     */
    public function forceDeleted(File $file)
    {
        //
    }
}
