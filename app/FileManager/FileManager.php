<?php namespace App\FileManager;

use \Model;
use \Storage;
use McCool\LaravelAutoPresenter\HasPresenter;

class FileManager extends Model implements HasPresenter {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    public function getFiles($path)
    {
        return Storage::Files($path);
    }

    public function getDirectories($path)

    {
        return Storage::Directories($path);
    }
    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return FileManagerPresenter::class;
    }

}
