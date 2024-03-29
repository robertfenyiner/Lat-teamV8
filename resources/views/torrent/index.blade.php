@extends('layout.default')

@section('title')
    <title>{{ __('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('torrent.torrents') }} {{ config('other.title') }}" />
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('torrent.torrents') }}
    </li>
@endsection

@section('nav-tabs')
    <li class="nav-tab--active">
        <a class="nav-tab--active__link" href="{{ route('torrents.index') }}">
            {{ __('torrent.search') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('top10.index') }}">
            {{ __('common.top-10') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('rss.index') }}">
            {{ __('rss.rss') }}
        </a>
    </li>
    <li class="nav-tabV2">
        <a class="nav-tab__link" href="{{ route('torrents.create', ['category_id' => 1]) }}">
            {{ __('common.upload') }}
        </a>
    </li>
@endsection

@section('content')
    @livewire('torrent-search')
@endsection
