<?php namespace App\FileManager\File;

use \Storage;
use App\FileManager\AbstractObject;

use Illuminate\Database\Eloquent\Collection;

class File extends AbstractObject {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    public function getPathInfo($filePath)
    {
        return pathinfo($filePath);
    }

    public function isFileExsit($filePath)
    {
        return Storage::disk(env('DISK_NAME', 'local'))->exists($filePath);
    }

    public function getFile($filePath)
    {
        return Storage::get($filePath);
    }

    public function getAll($dirPath)
    {
        return Storage::Files($dirPath);
    }

    public function getFileSize($filePath)
    {
        return Storage::size($filePath);
    }

    public function getLastModified($filePath)
    {
        return Storage::lastModified($filePath);
    }

    public function getMimetype($filePath)
    {
        return Storage::Mimetype($filePath);
    }

    public function getObjectMeta($filePath)
    {
        return new File(['path' => $filePath,
                         'pathinfo' => $this->getPathInfo($filePath),
                         'type' => 'file',
                         'mime' => $this->getMimetype($filePath),
                         'fileSize' => $this->getFileSize($filePath),
                         'lastModified' => $this->getLastModified($filePath)
                    ]);
    }

    public function getAllWithMeta($path, $sortBy = "name")
    {
        $names = $this->getAll($path);
        $files = new Collection();

        foreach ($names as $name)
        {
            $files->add($this->getObjectMeta($name));
        }

        return $files->sortBy($sortBy);
    }

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return FilePresenter::class;
    }

}
