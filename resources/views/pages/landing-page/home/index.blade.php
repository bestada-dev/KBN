@extends('layouts-landing-page.app')

@section('title', 'Home ')

@section('content')

    @include('pages.landing-page.home.content.hero')
    @include('pages.landing-page.home.content.comfortable_place_section')
    @include('pages.landing-page.home.content.property_with_vr_section')
    @include('pages.landing-page.home.content.benefit')
    @include('pages.landing-page.home.content.popular_property')
    @include('pages.landing-page.home.content.offering')
    @include('pages.landing-page.home.content.our_tenants')
    @include('pages.landing-page.home.content.contact_us')

@endsection

@section('js')
    @include('pages.landing-page.home.content.js')
@endsection