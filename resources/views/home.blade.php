@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
        @foreach ($directories as $directory)
            <div class="col-md-12">{{{ $directory }}}</div>
        @endforeach

        @foreach ($files as $file)
            <div class="col-md-12">{{{ $file }}}</div>
        @endforeach
	</div>
</div>
@endsection
