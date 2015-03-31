<?php namespace App\FileManager;

use McCool\LaravelAutoPresenter\BasePresenter;

class FileManagerPresenter extends BasePresenter {

    public function __construct(FileManager $resource)
    {
        $this->wrappedObject = $resource;
    }

}