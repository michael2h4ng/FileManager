<?php namespace App\FileManager\File;

use McCool\LaravelAutoPresenter\BasePresenter;
use Carbon\Carbon;

class FilePresenter extends BasePresenter {

    public function __construct(File $resource)
    {
        $this->wrappedObject = $resource;
    }

    public function basename()
    {
        $basename = $this->wrappedObject->pathinfo['basename'];

        if (strlen($basename) > 20)
        {
            return substr($basename, 0, 20) . "...";
        }

        return $basename;
    }

    public function lastModified()
    {
        $lastModified = $this->wrappedObject->lastModified;

        return Carbon::createFromTimeStamp($lastModified)->diffForHumans();
    }

    public function url()
    {
        return action('HomeController@index', [$this->path]);
    }

}
