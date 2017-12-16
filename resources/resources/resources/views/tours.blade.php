@extends('layouts.main')
@section('page-name', 'tours page--search-results')

@section('footer-assets')
<script src="{{ asset('/js/components/welcome-modal.js') }}"></script>
@endsection

@section('content')

<main class="app-main">

    @if (App\Helpers\GlobalData::checkIfNewUser())
    <div class="welcome-modal">

        <div class="welcome-modal__underlay" data-close-welcome-modal></div>

        <div class="welcome-modal__inner">
            <i class="material-icons">account_circle</i>
            <h1 class="welcome-modal__title">Welcome to Viewplex!</h1>
            <hr>
            <h2 class="welcome-modal__subtitle">What would you like to do?</h2>
            <div class="welcome-modal__links">
                <a href="{{ url('/guide') }}"> Get started guide here</a>
                <a href="{{ url('/tours') }}"> Create a virtual tour</a>
            </div>
        </div>
    </div>
    @endif

    @if ($resultCount > 0)
    <section class="section-controls">
        <div class="container">
            <div class="section-controls__flex">

                @if (Route::current()->uri == 'tours/search')
                    @if ($resultCount > 0)
                    <h1 class="section-tours_results-message">
                        @php
                        if ($resultCount == 1) {
                            $quantifier = 'Result';
                        } else {
                            $quantifier = 'Results';
                        }
                        @endphp
                        {{ $resultCount }} {{ $quantifier }} found for "{{ $query }}"
                    </h1>
                    @else
                    <h1 class="section-tours_results-message">Create your tours "{{ $query }}"</h1>
                    @endif
                @endif

                @if (Route::current()->uri != 'tours/search')
                <div class="filter-menu" data-open-menu data-menu-name="filter-menu">

                    <div class="filter-menu__text">
                        <span class="filter-menu__label">
                            Sort by:
                        </span>
                        <div class="filter-menu__selection">
                            <span>{{ $activeFilter }}</span>
                            <i class="material-icons">arrow_drop_down</i>
                        </div>
                    </div>

                    <div class="filter-menu__dropdown">
                        <ul>
                            @foreach ($filters as $filter)

                                @if ($filter['name'] == $activeFilter)

                                    <li class="active">
                                        <a href="{{ url('/tours?filter=' . $filter['param']) }}">
                                            {{ $filter['name'] }}
                                        </a>
                                    </li>
                                @else

                                    <li>
                                        <a href="{{ url('/tours?filter=' . $filter['param']) }}">
                                            {{ $filter['name'] }}
                                        </a>
                                    </li>

                                @endif

                            @endforeach
                        </ul>
                    </div>

                </div>
                @endif


                <form class="search-bar" method="GET" action="{{ url('/tours/search') }}">

                    {{ csrf_field() }}

                    <input type="text" name="search"
                           class="search-bar__input"
                           placeholder="Search Tours by Postcode"
                           autocomplete="off"
                           @if (isset($query))
                           value="{{ $query }}"
                           @endif
                    >

                    <button class="search-bar__icon" type="submit">
                        <i class="material-icons">search</i>
                    </button>

                </form>

                {{-- <div class="section-controls__cta">
                    <a class="btn btn-primary green outline with-icon" href="{{ url('upload') }}">
                        New Tour <i class="material-icons">add</i>
                    </a>
                </div> --}}

            </div>
        </div>
    </section>

    <section class="section-tours">
        <div class="container">
            <div class="row">

                @php
                $clearfixCount = 3;
                $loopCount = 1;
                @endphp

                @foreach($tours as $tour)

                    <article class="section-tours__tour col-lg-4" id="tour--{{ $tour->id }}">
                        <div class="section-tours__tour-inner">

                            <a class="section-tours__tour-image"
                               href="{{ url('/tours/' . $tour->id) }}"
                               target="_blank"
                            > 
                                <div class="image"
                                     style="background-image: url({{ asset('/upload/' . Auth::user()->id . '/tours/' . $tour->id . '/thumbnail/thumbnail.jpg') }});"
                                ></div> 
                                <div class="overlay"></div>
                                <div class="view-button">
                                    <i class="material-icons">pageview</i>
                                </div>

                                @if ($tour->public == 1)
                                <div class="section-tours__tour-live-icon">
                                    <div></div>
                                </div>
                                @endif

                            </a>

                            <footer class="section-tours__tour-footer">

                                <div class="section-tours__tour-text">
                                    <span class="section-tours__tour-address">
                                        {{ $tour->name }}
                                    </span>
                                    <span class="section-tours__tour-postcode">
                                        {{ $tour->address_postcode }}
                                    </span>
                                </div>

                                <div class="section-tours__tour-menu-icon" data-open-menu data-menu-name="tour-options">
                                    <div class="bars">
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                        <div class="bar"></div>
                                    </div>


                                    <div class="section-tours__tour-menu">

                                        <ul>
                                            <li>
                                                <i class="material-icons">pageview</i>
                                                <a href="{{ url('/tours/' . $tour->id) }}" target="_blank">View Tour</a>
                                            </li>
                                            @if (!$tour->public)
                                            <li>
                                                <i class="material-icons">publish</i>
                                                <a href="{{ url('/tours/' . $tour->id . '/make-public') }}">Make Public</a>
                                            </li>
                                            @else
                                            <li>
                                                <i class="material-icons">link</i>
                                                <a href="{{ url('/') }}">Get Shareable Link</a>
                                            </li>
                                            <li>
                                                <i class="material-icons" style="transform: rotate(180deg)">publish</i>
                                                <a href="{{ url('/tours/' . $tour->id . '/make-private') }}">Make Private</a>
                                            </li>
                                            @endif
                                            <li>
                                                <i class="material-icons">delete_forever</i>
                                                <a href="{{ url('/tours/' . $tour->id . '/delete') }}">
                                                    Delete Tour
                                                </a>
                                            </li>
                                        </ul>
                                    </div>


                                </div>

                            </footer>

                        </div>
                    </article>

                    @if ($loopCount % $clearfixCount === 0)
                        <div class="clearfix"></div>
                    @endif

                    @php
                    $loopCount++;
                    @endphp

                @endforeach

            </div>
        </div>
    </section>

    <section class="section-pagination">
        <div class="container">

            <div class="section-pagination__flex">

                <div class="section-pagination__previous">
                    @if ($tours->previousPageUrl())
                        <a href="{{ $tours->previousPageUrl() }}">Previous</a>
                    @endif
                </div>

                <div class="section-pagination__count">

                    {{ $tours->links() }}

                </div>

                <div class="section-pagination__next">
                    @if ($tours->nextPageUrl())
                        <a href="{{ $tours->nextPageUrl() }}">Next</a>
                    @endif
                </div>

            </div>

        </div>
    </section>

    <div class="new-button">
        <div class="container">

            <a href="{{ url('tours/new') }}" class="new-button__button">
                <i class="material-icons">add_circle</i>
            </a>

        </div>
    </div>

    @else

        @if (Route::current()->uri != 'tours/search')
        <div class="section-no-results">
            <h1 class="section-no-results__message">Create your first virtual tour</h1>
            <div class="section-no-results__cta">
                    
                        <a class="btn btn-primary green outline with-icon" href="{{ url('/tours/new') }}">
                        <span class="text"> Make a New Tour  <i class="material-icons">add</i> </span></a>

                </a>
            </div>
        </div>
        @endif

    @endif

</main>

@endsection
