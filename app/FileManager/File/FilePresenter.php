<?php namespace App\FileManager\File;

use McCool\LaravelAutoPresenter\BasePresenter;

class FilePresenter extends BasePresenter {

    public function __construct(File $resource)
    {
        $this->wrappedObject = $resource;
    }

}