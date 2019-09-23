@extends('layouts.app')

@section('content')
<div class="container">
    <h1> Търсене на файл по дата</h2>
	
		@if ($errors->any())
			  <div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					  <li>{{ $error }}</li>
					@endforeach
				</ul>
			  </div><br />
		@endif
		{{ Form::open(array('method'=>'post', 'url' => '/search')) }}
		<div class="form-group row">
			<label for="start_date">Начална дата</label>
			{{ Form::date('start_date', date('d.M.Y')) }} 
		</div>
		<div class="form-group row">
			<label for="end_date">Крайна дата</label>
			{{ Form::date('end_date', date('d.M.Y')) }} 
		</div>
		<div class="row">
			<div class="offset-sm-1 col-sm-3">
				{!!Form::submit('Tърсене', array('class' => 'btn btn-success'))!!}
			</div>	
		</div>
		{{ Form::close() }}
		@isset($links)
			{!!$links!!}
		@endisset
		
</div>
@endsection
