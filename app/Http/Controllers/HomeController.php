<?php namespace App\Http\Controllers;

use App\FileManager\FileManager;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;
use Illuminate\Http\Response;

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
	 * Main entrance.
	 *
	 * @return Response
	 */
	public function index($path = '/')
	{
		if (! $this->file->isFileExsit($path))
		{
			abort(404);
		}

		if ($this->file->getMimetype($path) !== "directory")
		{
			$fileContent = $this->file->getFile($path);
			$fileMime = $this->file->getMimetype($path);

			return (new Response($fileContent, 200))->header('Content-Type', $fileMime);
		}

		$objects = $this->fileManager->getAllWithMeta($path);

		return view('home', compact('path', 'objects'));
	}
}
