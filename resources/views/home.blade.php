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

	<div id="file_system" class="row">
        @foreach ($objects as $object)
                <div class="col-xs-4 col-sm-3 col-md-2">
                    <div class="object">
                        <div class="icon-container">
                            <div class="icon-base {{{ $object->type }}}"></div>
                            <div class="icon-main"></div>
                        </div>
                        <div class="name-container">
                            <div role="button" class="name text-primary" title="{{{ $object->name }}}"><a href="{{{ $object->url }}}">{{{ $object->name }}}</a></div>
                            <div class="meta-info text-muted">{{{ $object->lastModified }}}</div>
                        </div>
                    </div>
                </div>
        @endforeach
	</div>
</div>
@endsection
