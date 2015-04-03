<?php namespace App\Http\Controllers;

use App\FileManager\FileManager;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;
use Illuminate\Http\Response;
use App\Http\Requests\CreateDirectoryRequest;
use App\Http\Requests\UploadFileRequest;

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

    public function create(CreateDirectoryRequest $request) // Inject validator
    {
        $data = $request->only('path', 'dirName'); // Retrive inputs

        if ($data['path'] == '/')
        {
            $dirPath = "/" . $data['dirName'];
        }
        else
        {
            $dirPath = $data['path'] . "/" . $data['dirName'];
        }

        // Check if the directory already exist
        while ($this->file->isFileExsit($dirPath))
        {
            $dirPath .= " (2)";
        }

        if (! $this->directory->createDir($dirPath))
        {
            // Throw an unknown error
        }

        return response()->json($this->directory->getObjectMeta($dirPath));
    }

    public function upload(UploadFileRequest $request) // Inject validator
    {
    	$data = $request->only('path', 'file'); // Retrive inputs
    	dd($data);
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
			$fileName = $this->file->getPathInfo($path)['basename'];
			$fileContent = $this->file->getFile($path);

			return response ($fileContent, 200)
				->header("Content-Type", "application/octet-stream")
				->header("Content-Disposition", 'attachment; filename=' . $fileName);
		}

		$objects = $this->fileManager->getAllWithMeta($path);

		return view('home', compact('path', 'objects'));
	}
}
