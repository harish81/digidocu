@extends('layouts.app')
@section('title','Home')
@section('scripts')
    <script>
        function gotoUpload() {
            var docId = $("#document_id").val();
            var urlToUp = "{{route('documents.files.create', '')}}"+"/"+docId;
            console.log(urlToUp);
            window.location.href = urlToUp;
            return false;
        }
        $(function () {
            $('#activityrange').daterangepicker(
                {
                    ranges   : {
                        'Today'       : [moment(), moment()],
                        'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                        'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate  : moment()
                },
                function (start, end) {
                    $('#activityrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                    $('#activity_range').val(start.format('YYYY-MM-DD') + 'to' + end.format('YYYY-MM-DD'));
                }
            );
            @if(request()->has('activity_range'))
                var dates = '{{request('activity_range')}}'.split('to');
                var start = moment(dates[0]);
                var end = moment(dates[1]);
                $('#activityrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            @endif
        });
    </script>
@stop
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dashboard</h1>
    </section>
    <section class="content" style="margin-top: 20px;">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-md-8">
                <div class="box box-default">
                    <div class="box-header no-border text-center">
                        <h3 class="box-title">Quick Upload</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <form action="#" class="text-center" style="width: 30vw;margin: 0 auto;" onsubmit="return gotoUpload()">
                            <div class="form-group">
                                <label for="">Choose {{ucfirst(config('settings.document_label_singular'))}}</label>
                                <select name="document_id" id="document_id" class="form-control select2" required>
                                    @foreach ($documents as $document)
                                        @can('view',$document)
                                            <option value="{{$document->id}}">{{$document->name}}</option>
                                        @endcan
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-tags"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ucfirst(config('settings.tags_label_plural'))}}</span>
                        <span class="info-box-number">{{$tagCounts}}</span>
                        <span class="progress-description">
                    Total {{ucfirst(config('settings.tags_label_plural'))}} in system
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-folder"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">{{ucfirst(config('settings.document_label_plural'))}}</span>
                        <span class="info-box-number">{{$documentCounts}}</span>
                        <span class="progress-description">
                    Containing {{$filesCounts}} {{ucfirst(config('settings.file_label_plural'))}}
                  </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-default">
                    <div class="box-header no-border">
                        <h3 class="box-title">Activity</h3>

                        <div class="box-tools pull-right">
                            {!! Form::open(['method' => 'get','style'=>'display:inline;']) !!}
                                {!! Form::hidden('activity_range', '', ['id' => 'activity_range']) !!}
                                <button type="button" id="activityrange" class="btn btn-default btn-sm">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span>Choose dates</span> <i class="fa fa-caret-down"></i>
                                </button>
                                {!! Form::button('<i class="fa fa-filter"></i>&nbsp;Filter', ['class' => 'btn btn-default btn-sm','type'=>'submit']) !!}
                            {!! Form::close() !!}
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="timeline">
                            <li class="time-label">
                                <span class="bg-red">{{formatDate(optional($activities->first())->created_at,'d M Y')}}</span>
                            </li>
                            @foreach ($activities as $activity)
                                @can('view',$activity->document)
                                <li>
                                    <i class="fa fa-user bg-aqua" data-toggle="tooltip"
                                       title="{{$activity->createdBy->name}}"></i>

                                    <div class="timeline-item">
                                            <span class="time" data-toggle="tooltip"
                                                  title="{{formatDateTime($activity->created_at)}}"><i
                                                    class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($activity->created_at)->diffForHumans()}}</span>

                                        <h4 class="timeline-header no-border">{!! $activity->activity !!}</h4>
                                    </div>
                                </li>
                                @endcan
                            @endforeach
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                        <div class="text-center">
                            {!! $activities->appends(request()->all())->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
