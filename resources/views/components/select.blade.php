<div class="form-group col-sm-6 {{ $errors->has(preg_replace("/\[.?\]/",'',$name)) ? 'has-error' :'' }}">
    {!! Form::label($name, empty($label)?ucfirst($name).':':$label.":") !!}
    {!! Form::select($name, $list, $value, array_merge(['class' => 'form-control select2'],$attributes)) !!}
    {!! $errors->first(preg_replace("/\[.?\]/",'',$name),'<span class="help-block">:message</span>') !!}
</div>
