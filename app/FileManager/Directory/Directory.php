<?php namespace App\FileManager\Directory;

use \Storage;
use App\FileManager\AbstractObject;
use App\Services\MetaInfoService;
use Illuminate\Database\Eloquent\Collection;

class Directory extends AbstractObject {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    public function getPathInfo($dirPath)
    {
        return MetaInfoService::mb_pathinfo($dirPath);
    }

    public function getAll($path)
    {
        return Storage::Directories($path);
    }

    public function getMimeType($dirPath)
    {
        return Storage::MimeType($dirPath);
    }

    public function getLastModified($dirPath)
    {
        return Storage::lastModified($dirPath);
    }

    public function getObjectMeta($dirPath)
    {
        return new Directory(['path' => $dirPath,
                              'pathinfo' => $this->getPathInfo($dirPath),
                              'mime'    => $this->getMimeType($dirPath),
                              'type' => 'directory',
                              'lastModified' => $this->getLastModified($dirPath)
                    ]);
    }

    public function getAllWithMeta($path, $sortBy = "path")
    {
        $names = $this->getAll($path);
        $directories = new Collection();

        foreach ($names as $name)
        {
            $directories->add($this->getObjectMeta($name));
        }

        return $directories->sortBy($sortBy);
    }

    public function createDir($dirPath)
    {
        return Storage::makeDirectory(trim($dirPath));
    }

    public function deleteObject($dirPath)
    {
        return Storage::deleteDirectory($dirPath);
    }

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return DirectoryPresenter::class;
    }

}
