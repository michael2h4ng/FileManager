<?php namespace App\FileManager\File;

use \Model;
use \Storage;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Support\Collection;

class File extends Model implements HasPresenter {

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

    public function getFileSize($fileName)
    {
        return Storage::size($fileName);
    }

    public function getLastModified($fileName)
    {
        return Storage::lastModified($fileName);
    }

    public function getFileMeta($fileName)
    {
        return new File(['fileName' => $fileName,
                      'fileSize' => $this->getFileSize($fileName),
                      'lastModified' => $this->getLastModified($fileName)
                    ]);
    }

    public function getFilesWithMeta($path)
    {
        $fileNames = $this->getFiles($path);
        $files = array();

        foreach ($fileNames as $fileName)
        {
            $files[] = $this->getFileMeta($fileName);
        }

        return new Collection($files);
    }

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return FilePresenter::class;
    }

}
