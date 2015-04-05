<?php namespace App\Http\Controllers;

use App\FileManager\FileManager;
use App\FileManager\File\File;
use App\FileManager\Directory\Directory;
use App\Http\Requests\MoveObjectRequest;
use App\Services\MetaInfoService;
use Illuminate\Http\Response;
use App\Http\Requests\CreateDirectoryRequest;
use App\Http\Requests\UploadFileRequest;
use App\Services\MataInfoService;
use \File as FileService;

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
        $data = $request->only('path', 'dirName'); // Retrieve inputs

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
            trim($dirPath);
        }

        if (! $this->directory->createDir($dirPath))
        {
            // Throw an unknown error
        }

        return response()->json($this->directory->getObjectMeta($dirPath));
    }

    public function upload(UploadFileRequest $request) // Inject validator
    {
    	$data = $request->only('dirPath'); // Retrieve meta data
        $files = $request->file('files'); // Retrieve the files

        // Check if the directory exist
        if ($this->file->isFileExsit($data['dirPath']))
        {
            // Throw exception
        }

        $errors = []; // Initialize message bag
        $success = [];
        foreach ($files as $key => $file)
        {
            // Construct physical path to the file
            $fileName = MetaInfoService::mb_pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $fileExtension = "." . $file->getClientOriginalExtension();

            if ($data['dirPath'] == '/')
            {
                $filePath = "/" . $fileName;
            }
            else
            {
                $filePath = $data['dirPath'] . "/" . $fileName;
            }

            // Determine if the file already exist
            while ($this->file->isFileExsit($filePath . $fileExtension))
            {
                $filePath .= " (2)";
            }

            if ($fileExtension !== ".")
            {
                $filePath .=  $fileExtension; // Full file path
            }

            $fileContent = FileService::get($file);

            // Upload the file
            if (!$this->file->uploadFile(trim($filePath), $fileContent))
            {
                $errors[] = array( "fileName" => $fileName );
            }

            $success[] = $this->file->getObjectMeta($filePath);
        }

        return response()->json(array("errors" => $errors, "success" => $success));
    }

    public function move(MoveObjectRequest $request)
    {
        // Retrieve inputs
        $data = $request->only('path', 'oldName', 'newName', 'fileType');

        $this->file->moveObject($data['path'] . '/' . $data['oldName'], $data['path'] . '/' . $data['newName']);

        if($data['fileType'] == 'directory')
        {
            return response()->json($this->directory->getObjectMeta($data['path'] . '/' . $data['newName']));
        }

        return response()->json($this->file->getObjectMeta($data['path'] . '/' . $data['newName']));
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

        $maxFileSize = MetaInfoService::max_file_size(False);
        $maxFileSizeBytes =  MetaInfoService::max_file_size(True);

		return view('home', compact('path', 'objects', 'maxFileSize', 'maxFileSizeBytes'));
	}
}
