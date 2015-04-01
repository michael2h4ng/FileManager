<?php namespace App\Http\Controllers;

use App\FileManager\FileManager;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(FileManager $fileManager, File $file, Directory $directory)
	{
		$this->middleware('auth');
        $this->fileManager = $fileManager;
        $this->file = $file;
        $this->directory = $directory;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($path = '/')
	{
        $directories = $this->directory->getDirectoriesWithMeta($path);
        $files = $this->file->getFilesWithMeta($path);

		return view('home', compact('path', 'directories', 'files'));
	}

}
