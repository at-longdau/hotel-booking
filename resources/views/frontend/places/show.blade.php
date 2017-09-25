@extends('frontend.layouts.master')
@section('customcss')
  <link rel="stylesheet" href="{{ asset('frontend/css/stylecustom.css') }}">
@endsection
@section('title', __($place->name))
@section('content')
<section class="cls-breadcrumb-effect">
  <div id="parallax-pagetitle" >
    <!-- Page title -->
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <ol class="breadcrumb cls-breadcrumb">
            <li><a href="{{ route('home.index') }}">{{ __('Home') }}</a></li>
            <li>{{ $place->name }}</li>
          </ol>
        </div>
      </div>
        <div class="cls-image-contain text-center">
          <img class="cls-image" src="{{ isset($place->image) ? $place->image_url : asset(config('image.no_image')) }}" alt="">
          <div class="cls-transbox">
            <h1>{{ $place->name }}</h1>
            <h2><i class="fa fa-arrow-right" aria-hidden="true"></i>{{ __('There are :totalHolels hotels in :place', ['totalHolels' => $place->hotels_count, 'place' => $place->name]) }}</h2>
          </div>
        </div>
    </div>
  </div>
</section>
@if($hotels->count() != null)
  <section class="rooms mt50">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h2 class="lined-heading">
            <span>{{ __('List Hotels of :place', ['place' => $place->name]) }}</span>
          </h2>
        </div> 
        @foreach($hotels as $hotel)
          <div class="col-sm-4 mt50">
            <div class="room-thumb">
            @if($hotel->images->count() != 0)
              <img src="{{ asset($hotel->images->random()->path) }}" alt="hotel" class="img-responsive"/>
            @else
              <img src="{{ asset(config('image.default_thumbnail')) }}" alt="hotel" class="img-responsive"/>
            @endif
              <div class="mask">
                <div class="main cls-with-max">
                  <div class="pull-left">
                    <a href=""><h5>{{ $hotel->name }}</h5></a>
                  </div>
                  <div class="cls-mr-20">
                    <a href="">
                      <h5 class="cls-text-color-primary">{{ ($hotel->total != null) ? round($hotel->total, 1) : __('Has no rating')}}</h5>
                    </a>
                  </div>
                </div>
                <div class="content">
                 <p>
                    <span>{{ __('Introduce') }}</span>
                    {{ contentLimit(strip_tags($hotel->introduce)) }}
                  </p>
                  <div class="row">
                    <div class="col-xs-6">
                      <ul class="list-unstyled">
                      @php($count = 0)
                        @foreach($hotel->hotelServices as $hotelService)
                          @if($count == 3)
                                </ul>
                              </div>
                            <div class="col-xs-6">
                              <ul class="list-unstyled">
                          @endif
                          <li><i class="fa fa-check-circle"></i>{{ $hotelService->service->name }}</li>
                          @php($count++)
                        @endforeach
                      </ul>
                    </div>
                  </div>
                  <a href="" class="btn btn-primary btn-block">{{ __('Read More') }}</a>
                </div>
              </div>
            </div>
          </div> 
        @endforeach
      </div>
    </div>
    <div class="text-center">{{$hotels->render()}}</div>
  </section>
@else
  <p class="text-center">{{ __('Sorry! The system is updating') }}</p>
@endif
@endsection