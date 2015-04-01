<?php namespace App\FileManager;

use \Model;
use McCool\LaravelAutoPresenter\HasPresenter;

abstract class AbstractObject extends Model implements HasPresenter {

    protected $hidden;
    protected $casts;

    abstract public function getAll($path);
    abstract public function getLastModified($name);
    abstract public function getObjectMeta($name);
    abstract public function getAllWithMeta($path, $sortBy);
    abstract public function getPresenterClass();

}
