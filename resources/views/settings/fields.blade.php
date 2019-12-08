
<!-- Value Field -->
<div class="form-group col-sm-12 {{ $errors->has('value') ? 'has-error' :'' }}">
    {!! Form::label('value', optional($setting)->name) !!}
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
    {!! $errors->first('value','<span class="help-block">:message</span>') !!}
</div>


<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
    <a href="{!! route('settings.index') !!}" class="btn btn-default">Cancel</a>
</div>
