{!! csrf_field() !!}
<div class="form-group">
    {!! Form::label('name', trans('mockup.name_label')) !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
    <small class="text-danger">{{ $errors->first('name') }}</small>
</div>

<div class="form-group">
    {!! Form::label('mockup_category_id', trans('mockup.category_label')) !!}
    {!! Form::select('mockup_category_id', $categories, null, ['class' => 'form-control categories select2', 'required' => 'required']) !!}
    <small class="text-danger">{{ $errors->first('mockup_category_id') }}</small>
</div>

<div class="form-group">
  {!! Form::label('format', trans('mockup.format_label')) !!}
  @foreach ($formats as $key => $format)
  <div class="checkbox">
    <label for="{{$key}}" class="checkbox">
        {!! Form::radio('format', $key,  null, [
            'class' => 'form-control no-icheck',
            'id'    => $key,
        ]) !!} {!! trans($format) !!}
    </label>
  </div>
  @endforeach
</div>

<div class="form-group">
  {!! Form::label('color', trans('mockup.color_label')) !!}
  @for ($i = 0; $i < 6; $i++)
    <div class="checkbox">
        <label for="color_{{$i}}" class="checkbox">
            {!! Form::radio('color', 'color_'.$i,  ($i == 0), [
                'class' => 'form-control no-icheck',
                'id'    => 'color_'.$i,
            ]) !!} {!! trans('mockup.color_'.$i) !!}
        </label>
    </div>
  @endfor
</div>

<div class="form-group">
    {!! Form::label('images', trans('mockup.image_label')) !!}
    {!! Form::file('images', ['class' => 'required']) !!}
    <p class="help-block">{!! trans('mockup.help_image_text') !!}</p>
    <small class="text-danger">{{ $errors->first('images') }}</small>
</div>

<div class="form-group">
    {!! Form::label('psd', trans('mockup.psd_label')) !!}
    {!! Form::file('psd', ['class' => 'psd-files']) !!}
    <p class="help-block">{!! trans('mockup.help_psd_text') !!}</p>
    <small class="text-danger">{{ $errors->first('psd') }}</small>
</div>

{!! Form::submit(trans('mockup.submit'), ['class' => 'btn btn-info pull-right']) !!}
