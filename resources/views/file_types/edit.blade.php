@extends('layouts.app')
@section('title','Edit '.ucfirst(config('settings.file_label_singular')).' Type')
@section('content')
    <section class="content-header">
        <h1>
            {{ucfirst(config('settings.file_label_singular'))}} Type
        </h1>
   </section>
   <div class="content">
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($fileType, ['route' => ['fileTypes.update', $fileType->id], 'method' => 'patch']) !!}

                        @include('file_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
