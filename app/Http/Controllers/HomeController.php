<?php namespace App\Http\Controllers;

use App\FileManager\FileManager;

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
	public function __construct(FileManager $fileManager)
	{
		$this->middleware('auth');
        $this->fileManager = $fileManager;
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index($path = '/')
	{
        $directories = $this->fileManager->getDirectories($path);
        $files = $this->fileManager->getFiles($path);

		return view('home', compact('path', 'directories', 'files'));
	}

}
