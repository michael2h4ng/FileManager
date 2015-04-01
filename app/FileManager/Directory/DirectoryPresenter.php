<?php namespace App\FileManager\Directory;

use McCool\LaravelAutoPresenter\BasePresenter;
use Carbon\Carbon;

class DirectoryPresenter extends BasePresenter {

    public function __construct(Directory $resource)
    {
        $this->wrappedObject = $resource;
    }

    public function lastModified()
    {
        $lastModified = $this->wrappedObject->lastModified;

        return Carbon::createFromTimeStamp($lastModified)->diffForHumans();
    }
}