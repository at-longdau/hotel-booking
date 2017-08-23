@extends('backend.layouts.main')
@section('title', 'Hotels managment')
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      {{ __('Hotels managment') }}
      <small>
        {{ __('List of hotels') }}
      </small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>{{ __('Home') }}</a></li>
      <li class="active">{{ __('List of hotels') }}</li>
    </ol>
  </section>

 <!-- Main content -->
 <section class="content">
   <div class="row">
     <div class="col-xs-12">
       <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ __('Table hotel detail') }}</h3>
            @include('flash::message')
            @include('backend.layouts.partials.modal')
          </div>
          <div class="contain-btn pull-right">
            <a class="btn btn-primary" href="{{ route('hotel.create') }}">{{ __('Add hotel') }}</a>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <table id="list-table" class="table table-bordered table-striped">
              <thead>
                <tr align="center">
                  <th>{{ __('ID') }}</th>
                  <th>{{ __('Name') }}</th>
                  <th>{{ __('Address') }}</th>
                  <th>{{ __('Place') }}</th>
                  <th>{{ __('Star') }}</th>
                  <th>{{ __('Total Rooms') }}</th>
                  <th>{{ __('Created At') }}</th>
                  <th>{{ __('Options') }}</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($hotels as $hotel)
                  <tr>
                    <td>{{ $hotel->id }}</td>
                    <td>
                      <a href="{{ route('hotel.show', $hotel->id) }}">
                        {{ $hotel->name }}
                      </a>
                    </td>
                    <td>{{ $hotel->address }}</td>
                    <td><a href="{{ route('place.show', $hotel->place->id) }}">{{ $hotel->place->name }}</a></td>
                    <td>{{ $hotel->star }}</td>
                    <td>{{ $hotel->rooms->count()}}
                    <td>{{ $hotel->created_at }}</td>
                    <td class="text-center col-action">
                      <div class="btn-option text-center">
                        <a class="btn-edit fa fa-pencil-square-o btn-custom-option pull-left" href="{{ route('hotel.edit', $hotel->id) }}">
                          <i aria-hidden="true"></i>
                        </a>
                        <form class="inline" method="POST" action="{{ route('hotel.destroy', $hotel->id) }}">
                          {!! csrf_field() !!}
                          {{ method_field('DELETE') }}
                          <button type="submit" class=" btn-custom-option btn btn-delete-item fa fa-trash-o"
                          data-original-title="Delete" data-toggle="tooltip"  data-title="{{ __('Confirm deletion!') }}"
                          data-confirm="{{ __('Are you sure you want to delete?') }}">
                          </button>
                        </form> 
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            {!! $hotels->render() !!}
          </div>
         <!-- /.box-body -->
        </div>
       <!-- /.box -->
      </div>
     <!-- /.col -->
    </div>
   <!-- /.row -->
  </section>
</div>
@endsection