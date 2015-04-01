<?php namespace App\FileManager\File;

use McCool\LaravelAutoPresenter\BasePresenter;
use Carbon\Carbon;

class FilePresenter extends BasePresenter {

    public function __construct(File $resource)
    {
        $this->wrappedObject = $resource;
    }

    public function lastModified()
    {
        $lastModified = $this->wrappedObject->lastModified;

        return Carbon::createFromTimeStamp($lastModified)->diffForHumans();
    }

}