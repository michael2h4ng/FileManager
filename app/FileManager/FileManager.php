<?php namespace App\FileManager;

use \Model;
use \Storage;
use McCool\LaravelAutoPresenter\HasPresenter;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;

class FileManager extends Model implements HasPresenter {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return FileManagerPresenter::class;
    }

}
