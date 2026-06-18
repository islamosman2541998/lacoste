@extends('site.layouts.app')
@section('transparent_header', true)
@section('title', $storeSettings->store_name)

@section('content')
    @include('site.partials.home.hero-slider')

    @include('site.partials.home.featured-categories')

    @include('site.partials.home.featured-products')

    @include('site.partials.home.new-products')

    @include('site.partials.home.flash-sales')

    @include('site.partials.home.brands')
@endsection