@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Импорт на файл</h2>
		@if ($errors->any())
			  <div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					  <li>{{ $error }}</li>
					@endforeach
				</ul>
			  </div><br />
		@endif
		{{ Form::open(array('method'=>'post', 'url' => '/import')) }}
		{{Form::label('file', 'Изберете файл')}} 
		{{ Form::file('file', null)}}
		{{Form::submit('Запазване', null, array('class' => 'btn btn-primary'))}}
		
		{{ Form::close() }}
		
</div>
@endsection
