@extends('layout.default')

@section('title')
    <title>{{ __('mediahub.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="MediaHub" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('mediahub.title') }}
    </li>
@endsection

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('mediahub.title') }}</h2>
        <div class="panel__body">
            <ul class="mediahub-card__list">
                <li class="mediahub-card__list-item">
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'categories' => $tvCategoryIds]) }}"
                        class="mediahub-card"
                    >
                        <h2 class="mediahub-card__heading">{{ __('mediahub.shows') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $tv }} {{ __('mediahub.shows') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a
                        href="{{ route('torrents.index', ['view' => 'group', 'categories' => $movieCategoryIds]) }}"
                        class="mediahub-card"
                    >
                        <h2 class="mediahub-card__heading">{{ __('mediahub.movies') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $movies }} {{ __('mediahub.movies') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.collections.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">
                            {{ __('mediahub.collections') }} Hub
                        </h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $collections }} {{ __('mediahub.collections') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.persons.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('mediahub.persons') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $persons }} {{ __('mediahub.persons') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.genres.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('mediahub.genres') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $genres }} {{ __('mediahub.genres') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.networks.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('mediahub.networks') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $networks }} {{ __('mediahub.networks') }}
                        </h3>
                    </a>
                </li>
                <li class="mediahub-card__list-item">
                    <a href="{{ route('mediahub.companies.index') }}" class="mediahub-card">
                        <h2 class="mediahub-card__heading">{{ __('mediahub.companies') }} Hub</h2>
                        <h3 class="mediahub-card__subheading">
                            {{ $companies }} {{ __('mediahub.companies') }}
                        </h3>
                    </a>
                </li>
            </ul>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">Disclaimer</h2>
        <div class="panel__body" style="text-align: center">
            {{ __('mediahub.disclaimer') }}
            <img class="" src="{{ url('/img/tmdb_long.svg') }}" style="width: 200px" />
        </div>
    </section>
@endsection
