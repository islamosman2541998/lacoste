@extends('site.layouts.app')

@section('title', $storeSettings->store_name)

@section('content')
    @include('site.partials.home.hero-slider')

    @include('site.partials.home.featured-categories')

    @include('site.partials.home.featured-products')
@endsection