@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href=""><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button id="new_folder" type="button" class="btn btn-default" aria-label="New Folder">
                <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
            </button>
        </div>
    </div>

	<div id="file_system" class="row">
        @foreach ($objects as $object)
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="object" data-filetype="{{{ $object->type }}}" data-mime="{{{ $object->mime }}}" data-basename="{{{ $object->pathinfo['basename'] }}}">
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
