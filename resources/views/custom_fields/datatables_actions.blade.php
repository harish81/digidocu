{!! Form::open(['route' => ['customFields.destroy', $id], 'method' => 'delete']) !!}
<div class='btn-group'>
    <a href="{{ route('customFields.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-eye-open"></i>
    </a>
    <a href="{{ route('customFields.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="glyphicon glyphicon-edit"></i>
    </a>
    {!! Form::button('<i class="glyphicon glyphicon-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return conformDel(this,event)"
    ]) !!}
</div>
{!! Form::close() !!}
