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
		<div class="row">
        <div class="col">
            <div class="input-group mb-3">
                <label class="custom-file border">
                    <input type="file" id="inputGroupFile02" class="custom-file-input" required>
                    <span class="custom-file-control pr-3" style="white-space: 
nowrap;">Файл... </span>
                    <div class="input-group-append">
                        <span class="btn btn-primary" id="inputGroupFile02">Импорт</span>
                    </div>
                </label>
            </div>
        </div>
	</div>
		<div class="row">
			<div class="offset-sm-1 col-sm-3">
				{!!Form::submit('Запазване', array('class' => 'btn btn-danger'))!!}
			</div>	
		</div>
		{{ Form::close() }}
		
</div>
@endsection
