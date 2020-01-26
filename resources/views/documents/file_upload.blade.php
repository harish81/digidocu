@extends('layouts.app')
@section('title',"Upload ".ucfirst(config('settings.file_label_plural')))
@section('css')
    <style>

    </style>
@stop
@section('scripts')
    <script id="file-row-template" type="text/x-handlebars-template">
        <div class="box box-primary file-row file-row-@{{index}}">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button onclick="removeRow(this)" type="button" class="btn btn-danger btn-sm" title="Remove">
                        <i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6" style="margin-top: 10px;">
                        <div class="form-group text-center">
                            <label class="img-picker-label">
                                <img class="img-picker"
                                     src="http://dummyimage.com/400x200/f5f5f5/000000&text=Click+to+upload+{{config('settings.file_label_plural')}}"
                                     alt="Pick a file" height="120"/>
                            </label>
                            {!! Form::file('files[@{{index}}][file]', ['style'=>'margin:0 auto;','class'=>'image-picker-input']) !!}
                        </div>
                    </div>
                    {!! Form::bsSelect('files[@{{index}}][file_type_id]', $fileTypes, null, ['class'=>'form-control'], ucfirst(config('settings.file_label_singular'))." Type") !!}
                    {!! Form::bsText('files[@{{index}}][name]', null, [], ucfirst(config('settings.file_label_singular'))." Name") !!}
                    @foreach ($customFields as $customField)
                        {!! Form::bsText("files[@{{index}}][custom_fields][$customField->name]",null,['class'=>'form-control typeahead','data-source'=>json_encode($customField->suggestions),'autocomplete'=>is_array($customField->suggestions)?'off':'on'], Str::title(str_replace('_',' ',$customField->name))) !!}
                    @endforeach
                </div>
            </div>
        </div>
    </script>

    <script>
        var rowIndex = 0;
        $(document).ready(function () {
            registerListener();
            $("#frmUploads").submit(function (e) {
                e.preventDefault();
                submitForm(this);
            });
            addRow();
        });

        function clearError() {
            var input = $(this);
            input.next().remove();
            input.parent().removeClass("has-error");
        }

        function showErrors(errors) {
            $("#mainErrors").remove();
            $('form#frmUploads').before('<div id="mainErrors" class="alert alert-danger alert-dismissible">\n' +
                '<button class="close" data-dismiss="alert" aria-label="close">&times;</button>\n' +
                '<strong>Your Data Contain Errors, Please Review & Fix These Errors.</strong>\n' +
                '</div>');
            window.scrollTo(0, 0);
            for (var key in errors) {
                if (!errors.hasOwnProperty(key)) continue;

                var inputName = buildName(key);
                var element = $("*[name='" + inputName + "']");

                element.on('input', clearError);
                element.trigger('input');

                element.parent().addClass("has-error");
                element.after('<span class="help-block validation-err">' + errors[key] + '</span>');
            }
        }

        function registerListener() {
            $(".image-picker-input").unbind('change');
            $(".img-picker-label").unbind('click');

            $(".image-picker-input").change(function (event) {
                readURL(this, $(this).prev().find("img"));
            });
            $(".img-picker-label").click(function () {
                $(this).next().trigger('click');
            });
            //re-typeahead
            registerTypeahead();
        }

        function addRow() {
            var template = Handlebars.compile($("#file-row-template").html());
            var html = template({index: rowIndex});
            $(html).appendTo("#files_container");
            registerListener();
            rowIndex++;
        }

        function removeRow(elem) {
            $(elem).parents(".file-row").remove();
        }

        function buildName(key) {
            var name = '';
            var meta = key.split('.');
            meta.forEach(function (value, index) {
                if (index !== 0) {
                    name += '[' + value + ']';
                } else {
                    name += value;
                }
            });

            return name;
        }

        function submitForm(frm) {
            var oldHtmlBtn = $(frm).find("button[type='submit']").html();
            $(frm).find("button[type='submit']").html('<i class="fa fa-spinner fa-spin"></i> Uploading...').attr("disabled", true);
            var formData = new FormData(frm);
            $.ajax({
                url: '{{route('documents.files.store',$document->id)}}',
                type: 'POST',
                data: formData,
                success: function (data) {
                    window.location.href = "{{route('documents.show',$document->id)}}";
                },
                error: function (err) {
                    if (err.status === 422) {
                        showErrors(err.responseJSON.errors);
                    }
                },
                complete: function (data) {
                    $(frm).find("button[type='submit']").html(oldHtmlBtn).attr("disabled", false);
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
    </script>
@stop
@section('content')
    <section class="content-header">
        <h1>
            Upload {{ucfirst(config('settings.file_label_plural'))}} - <small>{{$document->name}}</small>
            <button class="btn btn-primary pull-right" onclick="javascript:window.history.back();"><i
                    class="fa fa-arrow-left"></i> Back
            </button>
        </h1>
    </section>
    <div class="content">
        <?php /** @var \Illuminate\Support\ViewErrorBag $errors */ ?>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <strong>Check These Errors:</strong>
                {!! implode('', $errors->all('<li>:message</li>'))  !!}
            </div>
        @endif
        @if(!count($fileTypes))
            <div class="alert alert-warning alert-dismissible">
                <button class="close" data-dismiss="alert" aria-label="close">&times;</button>
                <strong>There is no {{config('settings.file_label_singular')." type"}},</strong> Please add atleast one.
                <a href="{{route('fileTypes.create')}}">Add {{config('settings.file_label_singular')." type"}}</a>
            </div>
        @endif
        {!! Form::open(['route' => ['documents.files.store',$document->id],'files'=>true, 'id'=>'frmUploads']) !!}
        <div id="files_container">

        </div>
        <div style="text-align: center">
            <button type="button" class="btn btn-info btn-sm" onclick="addRow()"><i class="fa fa-plus"></i>
                Add {{ucfirst(config('settings.file_label_singular'))}}
            </button>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Upload</button>
        </div>
        {!! Form::close() !!}
    </div>
@endsection
