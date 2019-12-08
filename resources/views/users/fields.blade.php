@can('user manage permission')
    <script id="permission-row" type="text/x-handlebars-template">
        <tr>
            <td>
                {!! Form::select('tag_permissions[@{{index}}][tag_id]', $tags , null , ['class' => 'form-control input-sm']) !!}
            </td>
            @foreach (config('constants.TAG_LEVEL_PERMISSIONS')  as $perm)
                <td><label>
                        <input name="tag_permissions[@{{index}}][{{$perm}}]" type="checkbox" class="iCheck-helper"
                               value="1">
                    </label></td>
            @endforeach
            <td>
                <button onclick="removeRow(this)" class="btn btn-danger btn-xs" title="Remove row"><i
                        class="fa fa-trash"></i></button>
            </td>
        </tr>
    </script>
    <script>
        @php
            $groupTagPerm = groupTagsPermissions(optional($user ?? null)->getAllPermissions());
        @endphp
        let rowIndex = 0;

        function addRow() {
            var template = Handlebars.compile($("#permission-row").html());
            var html = template({index: rowIndex});
            $(html).appendTo("#permission-body");
            registerIcheck();
            rowIndex++;
        }
        function removeRow(elem) {
            $(elem).parents("tr").remove();
        }
        window.onload = function () {
            @foreach($groupTagPerm as $key=>$value)
                addRow();
                $("#permission-body>tr:last-child").find("select[name^='tag_permissions']").val('{{$value['tag_id']}}');
                @foreach($value['permissions'] as $perm)
                $("#permission-body>tr:last-child").find("input[name$='[{{$perm}}]']").attr('checked','checked');
                @endforeach
            @endforeach
            registerIcheck();
        }
    </script>
@endcan
<div class="box box-primary">
    <div class="box-header no-border">
        <h3 class="box-title">User Detail</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <!-- Name Field -->
            <div class="form-group col-sm-6 {{ $errors->has('name') ? 'has-error' :'' }}">
                {!! Form::label('name', 'Name:') !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                {!! $errors->first('name','<span class="help-block">:message</span>') !!}
            </div>


            <!-- Email Field -->
            <div class="form-group col-sm-6 {{ $errors->has('email') ? 'has-error' :'' }}">
                {!! Form::label('email', 'Email:') !!}
                {!! Form::email('email', null, ['class' => 'form-control']) !!}
                {!! $errors->first('email','<span class="help-block">:message</span>') !!}
            </div>


            <!-- Username Field -->
            <div class="form-group col-sm-6 {{ $errors->has('username') ? 'has-error' :'' }}">
                {!! Form::label('username', 'Username:') !!}
                {!! Form::text('username', null, ['class' => 'form-control']) !!}
                {!! $errors->first('username','<span class="help-block">:message</span>') !!}
            </div>


            <!-- Address Field -->
            <div class="form-group col-sm-6 {{ $errors->has('address') ? 'has-error' :'' }}">
                {!! Form::label('address', 'Address:') !!}
                {!! Form::text('address', null, ['class' => 'form-control']) !!}
                {!! $errors->first('address','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Password Field -->
            <div class="form-group col-sm-6 {{ $errors->has('password') ? 'has-error' :'' }}">
                {!! Form::label('password', 'Password:') !!}
                {!! Form::text('password', null, ['class' => 'form-control']) !!}
                {!! $errors->first('password','<span class="help-block">:message</span>') !!}
            </div>

            {{--Status Filed--}}
            <div class="form-group col-sm-6 {{ $errors->has('status') ? 'has-error' :'' }}">
                {!! Form::label('status', 'Status:') !!}
                {!! Form::select('status', [config('constants.STATUS.ACTIVE') => config('constants.STATUS.ACTIVE'), config('constants.STATUS.BLOCK') => config('constants.STATUS.BLOCK')],null, ['class'=>'form-control']); !!}
                {!! $errors->first('status','<span class="help-block">:message</span>') !!}
            </div>

            <!-- Description Field -->
            <div class="form-group col-sm-12 col-lg-12 {{ $errors->has('description') ? 'has-error' :'' }}">
                {!! Form::label('description', 'Description(Additional Information):') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control b-wysihtml5-editor']) !!}
                {!! $errors->first('description','<span class="help-block">:message</span>') !!}
            </div>
        </div>
    </div>
</div>
@can('user manage permission')
    <div class="box box-primary">
        <div class="box-header no-border">
            <h3 class="box-title">Global Permissions</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label">User</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.USERS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} Users
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-4">
                            <label class="control-label">{{ucfirst(config('settings.tags_label_plural'))}}</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.TAGS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} {{ucfirst(config('settings.tags_label_plural'))}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="col-sm-4">
                            <label
                                class="control-label">{{ucfirst(config('settings.document_label_plural'))}}</label><br>
                            @foreach(config('constants.GLOBAL_PERMISSIONS.DOCUMENTS') as $permission_name=>$permission_label)
                                <div class="form-group">
                                    <label>
                                        <input name="global_permissions[]" type="checkbox" class="iCheck-helper"
                                               value="{{$permission_name}}" {{optional($user ?? null)->can($permission_name)?'checked':''}}>
                                        &nbsp;{{ucfirst($permission_label)}} {{ucfirst(config('settings.document_label_plural'))}}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="box box-primary">
        <div class="box-header no-border">
            <h3 class="box-title">{{ucfirst(config('settings.tags_label_plural'))}} Wise Permissions</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Select {{ucfirst(config('settings.tags_label_singular'))}}</th>
                            @foreach (config('constants.TAG_LEVEL_PERMISSIONS')  as $perm)
                                <th>{{ucfirst($perm)}}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody id="permission-body">

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">
                                <button type="button" onclick="addRow()" class="btn btn-info btn-xs">Add
                                    new {{config('settings.tags_label_singular')}}</button>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endcan
<!-- Submit Field -->
<div class="form-group">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('users.index') !!}" class="btn btn-default">Cancel</a>
</div>
