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
        @foreach ($directories as $directory)
            <div class="col-xs-4 col-sm-3 col-md-2">
                <div role="button" class="object directory">
                    <div class="icon-container">
                        <div class="icon-base directory"></div>
                    </div>
                    <div class="name-container">
                        <div role="button" class="name text-primary" title="{{{ $directory->dirName }}}">{{{ $directory->dirName }}}</div>
                        <div class="meta-info text-muted">{{{ $directory->lastModified }}}</div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($files as $file)
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="object directory">
                        <div class="icon-container">
                            <div class="icon-base file"></div>
                            <div class="icon-main"></div>
                        </div>
                        <div class="name-container">
                            <div role="button" class="name text-primary" title="{{{ $file->fileName }}}">{{{ $file->fileName }}}</div>
                            <div class="meta-info text-muted">{{{ $file->lastModified }}}</div>
                        </div>
                    </div>
                </div>
        @endforeach
	</div>
</div>
@endsection
