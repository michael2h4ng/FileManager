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

    public function url()
    {
    	return action('HomeController@index', [$this->path]);
    }

    public function basename()
    {
        $basename = $this->wrappedObject->pathinfo['basename'];

        if (mb_strlen($basename) > 10)
        {
            return mb_substr($basename, 0, 20) . "...";
        }

        return $basename;
    }
}
