@extends('layouts.app')
@section('title','List '.ucfirst(config('settings.tags_label_plural')))
@section('content')
    <section class="content-header">
        <h1 class="pull-left">{{ucfirst(config('settings.tags_label_plural'))}}</h1>
        <h1 class="pull-right">
            @can('create tags')
                <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px"
                   href="{!! route('tags.create') !!}">
                    <i class="fa fa-plus"></i>
                    Add New
                </a>
            @endcan
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                @include('tags.table')
            </div>
        </div>
        <div class="text-center">

        </div>
    </div>
@endsection

