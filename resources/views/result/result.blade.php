@extends('layouts.app')

@section('content')
<div class="container">
    <h2> Генериране на файл с анализ на теста</h2>
		
		@isset($notice)
			<div class="alert alert-danger">
				{!!$notice!!}
			</div>	
		@endisset
	    @isset($notice2)
			<div class="alert alert-success">
				{!!$notice2!!}
			</div>	
		@endisset
		@if ($errors->any())
			  <div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					  <li>{{ $error }}</li>
					@endforeach
				</ul>
			  </div><br />
		@endif
		{{ Form::open(array('method'=>'post', 'url' => '/result')) }}
		<div class="form-group row"> 
			<div class="offset-sm-1 col-sm-3">
				{!!Form::submit('Генериране на файл', array('class' => 'btn btn-primary'))!!}
			</div>
		</div>
		{{ Form::close() }}
		
</div>
@endsection