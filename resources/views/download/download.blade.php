@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Изтегляне на файл</h2>
		@if ($errors->any())
			  <div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					  <li>{{ $error }}</li>
					@endforeach
				</ul>
			  </div><br />
		@endif
		{{ Form::open(array('method'=>'post', 'url' => '/download')) }}
		
		{{Form::submit('Изтегляне на файл', null, array('class' => 'btn btn-primary'))}}
		
		{{ Form::close() }}
		
</div>
@endsection