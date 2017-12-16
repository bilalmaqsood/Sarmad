@extends('360.layouts.360')
@section('page-name', '360Viewer')

@section('head-assets')
<script type="text/javascript" src="{{ asset('js/360Viewer.js') }}"></script>
<script type="text/javascript">
    javascript:(function(){var script=document.createElement('script');script.onload=function(){var stats=new Stats();document.body.appendChild(stats.dom);requestAnimationFrame(function loop(){stats.update();requestAnimationFrame(loop)});};script.src='//rawgit.com/mrdoob/stats.js/master/build/stats.min.js';document.head.appendChild(script);})()
</script>

@endsection

@section('content')

{{-- Flash Messages --}}
<div class="flash-message-box visible" data-flash-messages></div>

{{-- Header --}}
@include('components/header')

<main class="app-main">

    <div class="hidden" data-image-base="{{ asset('upload/' . $tour->user_id . '/tours/'. $tour->id .'/rooms') }}/"></div>

    <div class="images hidden">
        @foreach ($rooms as $room)
            <div class="hidden"
                 data-image
                 data-image-src="{{ asset('upload/' . $tour->user_id . '/tours/'. $tour->id .'/rooms/' . $room->name . '.jpg') }}"
            ></div>
        @endforeach
    </div>

    <section class="canvas-container" data-360-space></section>

    <div class="app-loader">
        <div class="app-loader__spinner">Loading...</div>
    </div>

    <aside class="uploads-sidebar">

        <div class="uploads-sidebar__gradient"></div>

        <div class="uploads-sidebar__overflow">
            <div class="uploads-sidebar__scroll">
                <div class="uploads-sidebar__inner">
                    <ul data-uploads-navigation>
                    {{-- Thumbs Appended here via JS::UiController.js --}}
                    </ul>
                </div>
            </div>
        </div>

    </aside>

    <footer class="uploads-toolbar">

        <div class="uploads-toolbar__controls">
            <ul>
                <li data-tool-toggle data-tool="newUpload">
                    <i class="material-icons">add_circle_outline</i>
                </li>
                <li data-tool-toggle data-tool="roomConnector">
                    <i class="material-icons">room</i>
                </li>
                <li data-tool-toggle data-tool="testFunc">
                    <i class="material-icons">textsms</i>
                </li>

                <!-- Controller Buttons - if class has active load event listeners -->
                <li data-tool-toggle data-tool="lookAround">
                    <i class="material-icons">textsms</i>
                    <p>L</p>
                </li>

                <li data-tool-toggle data-tool="moveObject">
                    <i class="material-icons">textsms</i>
                    <p>M</p>
                </li>

            </ul>
        </div>

    </footer>

    <div class="hidden" data-tour-data='{{ $tour->tour_data }}'></div>

</main>

{{-- Footer --}}
@include('components/footer')

@endsection
