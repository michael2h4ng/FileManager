@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/home"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="dropzone" class="fade well">Drop files here</div>

            <button id="new_folder" type="button" class="btn btn-default" aria-label="New Folder">
                <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> New Folder
            </button>

            <div id="new_file" class="btn btn-default">
                <form>
                    <label class="" for="fileupload">
                        <span class="glyphicon glyphicon-upload upload-icon" aria-hidden="true"></span>
                        <span class="sr-only">Upload</span>
                    </label>
                    <input id="fileupload" type="file" name="files[]" data-url="/manager/put/file" data-toggle="tooltip" data-placement="right" title="Max File Size: {{{ini_get('post_max_size')}}}" multiple>
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="dirPath" value="{{{ $path }}}">
                </form>
            </div>

            <div id="progress" class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                    <span class="sr-only">0% Complete</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
	<div id="file_system" class="row" data-dirpath="{{{ $path }}}">
        @foreach ($objects as $object)
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="object" data-filetype="{{{ $object->type }}}" data-fullpath="{{{ $object->path }}}" data-ext="" data-basename="{{{ $object->pathinfo['basename'] }}}">
                        <div class="icon-container">
                            <div class="icon-base {{{ $object->type }}}"></div>
                            <div class="icon-main"></div>
                        </div>
                        <div class="name-container">
                            <div role="button" class="name text-primary" title="{{{ $object->pathinfo['basename'] }}}"><a href="{{{ $object->url }}}">{{{ $object->pathinfo['basename'] }}}</a></div>
                            <div class="meta-info text-muted">{{{ $object->lastModified }}}</div>
                        </div>
                    </div>
                </div>
        @endforeach
	</div>
</div>
@endsection
