<?php namespace App\FileManager\Directory;

use \Model;
use \Storage;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Support\Collection;

class Directory extends Model implements HasPresenter {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    public function getDirectories($path)
    {
        return Storage::Directories($path);
    }

    public function getLastModified($fileName)
    {
        return Storage::lastModified($fileName);
    }

    public function getDirectoryMeta($dirName)
    {
        return new Directory(['dirName' => $dirName,
                      'lastModified' => $this->getLastModified($dirName)
                    ]);
    }

    public function getDirectoriesWithMeta($path)
    {
        $dirNames = $this->getDirectories($path);
        $directories = array();

        foreach ($dirNames as $dirName)
        {
            $directories[] = $this->getDirectoryMeta($dirName);
        }

        return new Collection($directories);
    }

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return DirectoryPresenter::class;
    }

}
