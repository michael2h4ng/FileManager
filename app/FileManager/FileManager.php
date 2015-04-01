<?php namespace App\FileManager;

use \Model;
use \Storage;
use McCool\LaravelAutoPresenter\HasPresenter;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;

class FileManager extends Model implements HasPresenter {

    public function __construct(File $file, Directory $directory)
    {
        $this->file = $file;
        $this->directory = $directory;
    }

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

    public function getAllWithMeta($path)
    {
        $objects = $this->directory->getAllWithMeta($path);
        $files = $this->file->getAllWithMeta($path);

        foreach($files as $file) {
            $objects->add($file);
        }

        return $objects;
    }

}
