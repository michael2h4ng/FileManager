<?php namespace App\FileManager\Directory;

use \Storage;
use App\FileManager\AbstractObject;
use McCool\LaravelAutoPresenter\HasPresenter;
use Illuminate\Database\Eloquent\Collection;

class Directory extends AbstractObject implements HasPresenter {

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    protected $casts = [];

    public function getAll($path)
    {
        return Storage::Directories($path);
    }

    public function getLastModified($name)
    {
        return Storage::lastModified($name);
    }

    public function getObjectMeta($name)
    {
        return new Directory(['name' => $name,
                              'type' => 'directory',
                              'lastModified' => $this->getLastModified($name)
                    ]);
    }

    public function getAllWithMeta($path, $sortBy = "name")
    {
        $names = $this->getAll($path);
        $directories = new Collection();

        foreach ($names as $name)
        {
            $directories->add($this->getObjectMeta($name));
        }

        return $directories->sortBy($sortBy);
    }

    /**
     * @return string The path to the presenter class
     */
    public function getPresenterClass()
    {
        return DirectoryPresenter::class;
    }

}
