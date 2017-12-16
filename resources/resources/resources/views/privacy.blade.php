@extends('layouts.legal')

@section('page-name', 'privacy')

@section('content')



	<h1 class="section-content__title">Privacy Policy</h1>


	<div class="section-content__date">
		<span>Last modified: </span>
		<time>{{ $lastModified }}</time>
	</div>

	{!! $content !!}



@endsection
