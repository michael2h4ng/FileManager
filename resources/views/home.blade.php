@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
        @foreach ($directories as $directory)
            <div class="col-xs-4 col-sm-3 col-md-2">
                <div class="object directory">
                    <div class="icon-container">
                        <div class="icon-base"></div>
                        <div class="icon-main"></div>
                    </div>
                    <div class="name-container">
                        <div role="button" class="name" title="{{{ $directory->dirName }}}">{{{ $directory->dirName }}}</div>
                        <div class="meta-info">{{{ $directory->lastModified }}}</div>
                    </div>
                </div>
            </div>
        @endforeach

        @foreach ($files as $file)
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="object directory">
                        <div class="icon-container">
                            <div class="icon-base"></div>
                            <div class="icon-main"></div>
                        </div>
                        <div class="name-container">
                            <div role="button" class="name" title="{{{ $file->fileName }}}">{{{ $file->fileName }}}</div>
                            <div class="meta-info">{{{ $file->lastModified }}}</div>
                        </div>
                    </div>
                </div>
        @endforeach
	</div>
</div>
@endsection
