@if($result['products'][0]->products_type == '1')
<div class="form-group">
    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.products_attributes_from') }}</label>
    <div class="col-sm-10 col-md-8">
        @if(count($result['attributes'])==0 )
            <input type='hidden' id='has-attribute' value='0'>
                <div class="alert alert-danger" role="alert">
                {{ trans('labels.You can not add stock without attribute for variable product') }}
            </div>
        @else
            @foreach ($result['attributes'] as $attribute)
                <input type='hidden' id='has-attribute' value='1'>
                <select class="form-control field-validate" name="attributeid[{{$result['products'][0]->products_id}}][]">
                    <option value="">{{ trans('labels.Choose') }}</option>
                    @foreach ($attribute['values'] as $value)
                        <option value="{{ $value['products_attributes_id'] }}">{{ $value['value'] }}</option>
                    @endforeach
                </select>
                @endforeach
            <input type="hidden" name="offer_type_from" value="variable_product">
        @endif
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
            {{ trans('labels.Select Option values Text') }}.</span>
        <span class="help-block hidden">{{ trans('labels.Select Option values Text') }}</span>
    </div>
</div>

@elseif($result['products'][0]->products_type == '0')
<div class="form-group">
    <label for="name" class="col-sm-2 col-md-4 control-label">{{ trans('labels.products_attributes_from') }}</label>
    <input type="hidden" name="offer_type_from" value="full_product">
    <input type='hidden' id='has-attribute' value='1'>
    <input type='hidden' id='has-attribute' value='0'>

    <div class="col-sm-10 col-md-8">
        <div class="alert alert-info" role="alert">
            {{ trans('labels.Now you can add stock for simple product') }}
        </div>
        <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
            {{ trans('labels.Select Option values Text') }}.</span>
        <span class="help-block hidden">{{ trans('labels.Select Option values Text') }}</span>
    </div>
</div>
@endif


