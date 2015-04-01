<?php namespace App\FileManager\Directory;

use McCool\LaravelAutoPresenter\BasePresenter;

class DirectoryPresenter extends BasePresenter {

    public function __construct(Directory $resource)
    {
        $this->wrappedObject = $resource;
    }

}