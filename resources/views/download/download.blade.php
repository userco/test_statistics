@extends('layouts.app')

@section('content')
<div class="container">
    <h2> Изтегляне на файл</h2>
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
		<div class="form-group row"> 
			<div class="offset-sm-1 col-sm-3">
				{!!Form::submit('Изтегляне на файл', array('class' => 'btn btn-primary'))!!}
			</div>
		</div>
		{{ Form::close() }}
		
</div>
@endsection