@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
               <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

					<div class="alert alert-success">
						Уважаеми учители, <br/>
						Това приложение е създадено за вас. С негова помощ може да оценявате учениците и да проследявате техните резултати. То прави анализ на тестовете и тестовите задачи и писмено тълкуване на анализа. Тестовите резултати и анализи се онагледяват с диаграми.
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
