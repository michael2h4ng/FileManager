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

    public function getAll($path)
    {
        return Storage::Files($path);
    }

    public function getFileSize($name)
    {
        return Storage::size($name);
    }

    public function getLastModified($name)
    {
        return Storage::lastModified($name);
    }

    public function getMimetype($name)
    {
        return Storage::Mimetype($name);
    }

    public function getObjectMeta($name)
    {
        return new File(['name' => $name,
                         'path' => $name,
                         'type' => 'file',
                         'ext'  => 'ext',
                         'mine' => $this->getMimetype($name),
                         'fileSize' => $this->getFileSize($name),
                         'lastModified' => $this->getLastModified($name)
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
