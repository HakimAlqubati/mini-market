@php
$edit = !is_null($dataTypeContent->getKey());
$add = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.' . ($edit ? 'edit' : 'add')) . ' ' .
    $dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.' . ($edit ? 'edit' : 'add')) . ' ' . $dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">


                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" class="form-edit-add"
                        action={{ $edit ? url('/update-product', [$dataTypeContent->getKey()]) : url('/add-product') }}
                        method="POST" enctype="multipart/form-data">
                        <!-- PUT Method if we are editing -->
                        @if ($edit)
                            {{ method_field('PUT') }}
                        @endif

                        <!-- CSRF TOKEN -->
                        {{ csrf_field() }}
                       
                        <div class="panel-body">

                            <table class="table table-striped" id="my-table">
                                <thead>

                                    <tr>

                                        <td colspan="2">

                                            <input type="text" class="form-control"
                                                placeholder="{{ __('product_name') }}" name="name"
                                                value="<?php echo $edit ? $product_details->name : ''; ?>" required />
                                        </td>

                                        <!--<td>-->

                                        <!--    <input type="text" class="form-control"-->
                                        <!--        placeholder="{{ __('english_name_hint') }}" name="english_name"-->
                                        <!--        value="<?php echo $edit ? $product_details->english_name : ''; ?>" required />-->
                                        <!--</td>-->

                                        <td colspan="2">
                                            <input type="text" class="form-control"
                                                placeholder="{{ __('product_code') }}" name="code"
                                                value="<?php echo $edit ? $product_details->code : ''; ?>" required />
                                              
                                                @if (count($errors) > 0)
                                                <div class="alert alert-danger" role="alert">  {{ $errors->first('code') }}  </div>
                                                @endif
                                        </td>


                                    <tr>
                                        <td colspan="100%">
                                            <input type="text" class="form-control"
                                                placeholder="{{ __('product_description') }}" name="desc"
                                                value="<?php echo $edit ? $product_details->description : ''; ?>" required />
                                        </td>
                                    </tr>


                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="main_group" id="main_group" class="form-control">
                                                <?php 
                                                 foreach ($mainGroups as $key => $value) {
                                                       ?>
                                                @if ($edit && $product_details->group_id == $value->id)
                                                    <option value="<?php echo $value->id; ?>" selected> <?php echo $value->name; ?>
                                                    </option>
                                                @endif
                                                <option value="<?php echo $value->id; ?>"> <?php echo $value->name; ?></option>

                                                <?php }?>


                                            </select>
                                        </td>

                                        {{-- <td>
                                            <p>
                                                {{ __('sub_group_label') }}
                                            </p>
                                        </td> --}}
                                        <td>

                                            <select name="sub_group" id="sub_group" class="form-control" required>

                                                <option value="">-إختر المجموعة الرئيسية-</option>
                                                <?php 
                                              
                                                 foreach ($subGroups as $key => $value) {
                                                       ?>
                                                @if ($edit && $product_details->group_id == $value->id)
                                                    <option value="<?php echo $value->id; ?>" selected> <?php echo $value->name; ?>
                                                    </option>
                                                @endif
                                                <option value="<?php echo $value->id; ?>"> <?php echo $value->name; ?></option>

                                                <?php }?>


                                            </select>
                                        </td>

                                        <td>
                                            <div class="form-check">
                                                @if ($edit && $product_details->active == 1)
                                                    <?php $value = 'checked'; ?>
                                                @else
                                                    <?php $value = ''; ?>
                                                @endif
                                                <input class="form-check-input" type="checkbox" value="checked"
                                                    id="flexCheckDefault" name="active" {{ $value }}>
                                                <label class="form-check-label" for="flexCheckDefault">
                                                    {{ __('active') }} ?
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input height="2" width="2" class="form-control" type="file" id="formFile"
                                                name="images[]" multiple>
                                        </td>
                                    </tr>
                                    <tr>

                                        <th>{{ __('unit') }}</th>
                                        <th>{{ __('price') }}</th>
                                        <th><button type="button" id="btn-add" class="btn btn-success">+</button></th>
                                        <th><button type="button" id="btn-remove" class="btn btn-danger">-</button></th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if ($edit && count($unit_prices) > 0)
                                        @foreach ($unit_prices as $item)
                                            <tr class="row_unit" id="row_unit">
                                                <input type="hidden" id="unit_price_id<?php echo $key; ?>"
                                                    name="unit_price_id[]" value='<?php echo $item->id; ?>'>
                                                <td>
                                                    <select name="unit[]" id="" required>
                                                        <?php 
                                                        foreach ($units as $key => $value) {
                                                 ?>
                                                        @if ($value->id == $item->unit_id)
                                                            <option value="<?php echo $value->id; ?>" selected>
                                                                <?php echo $value->name; ?></option>
                                                        @else
                                                            <option value="<?php echo $value->id; ?>">
                                                                <?php echo $value->name; ?></option>
                                                        @endif
                                                        <?php } ?>
                                                    </select>

                                                </td>
                                                <td>
                                                    <input type="number" name="price[]" id="" value="<?php echo $item->price; ?>"
                                                        required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="row_unit" id="row_unit">
                                            <td>
                                                <select name="unit[]" id="" required>

                                                    <?php  foreach ($units as $key => $value) {
                                         ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->name; ?></option>

                                                    <?php } ?>
                                                </select>

                                            </td>
                                            <td>
                                                <input type="number" name="price[]" id="" required>
                                            </td>
                                        </tr>

                                    @endif




                                </tbody>
                            </table>


                            <div style="width: 100%;text-align: center">
                                <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                            </div>
                        </div><!-- panel-body -->



                    </form>




                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}
                    </h4>
                </div>

                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger"
                        id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Delete File Modal -->
@stop

@section('javascript')
    <script>
        var params = {};
        var $file;

        function deleteHandler(tag, isMulti) {
            return function() {
                $file = $(this).siblings(tag);

                params = {
                    slug: '{{ $dataType->slug }}',
                    filename: $file.data('file-name'),
                    id: $file.data('id'),
                    field: $file.parent().data('field-name'),
                    multi: isMulti,
                    _token: '{{ csrf_token() }}'
                }

                $('.confirm_delete_name').text(params.filename);
                $('#confirm_delete_modal').modal('show');
            };
        }

        $('document').ready(function() {

            $("#main_group").change(function() {
                
            
                $.ajax({
                url: "{{ url('get-sub-group-of-main') }}?parent_id=" + $(this).val(),
                method: 'GET',
                success: function(data) {
                    $('#sub_group').html(data.html);
                }
            });
            });


            $('#btn-add').click(function() {

                var trid = $(this).closest('tr').attr('id'); // table row ID 

                $('<tr class="row_unit" id="row_unit" >' + $('#row_unit').html() + '</tr>').insertAfter(
                    'table tr:last');
            });

            $('#btn-remove').click(function() {
                // var rowIndex = $('#my-table tr:last').index(this);
                // alert(rowIndex)
                var last_id = $('#my-table tr:last').attr('id');
                $('#' + last_id).remove();
            });


            $('.toggleswitch').bootstrapToggle();

            //Init datepicker for date fields if data-datepicker attribute defined
            //or if browser does not handle date inputs
            $('.form-group input[type=date]').each(function(idx, elt) {
                if (elt.hasAttribute('data-datepicker')) {
                    elt.type = 'text';
                    $(elt).datetimepicker($(elt).data('datepicker'));
                } else if (elt.type != 'date') {
                    elt.type = 'text';
                    $(elt).datetimepicker({
                        format: 'L',
                        extraFormats: ['YYYY-MM-DD']
                    }).datetimepicker($(elt).data('datepicker'));
                }
            });

            @if ($isModelTranslatable)
                $('.side-body').multilingual({
                    "editing": true
                });
            @endif

            $('.side-body input[data-slug-origin]').each(function(i, el) {
                $(el).slugify();
            });

            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));

            $('#confirm_delete').on('click', function() {
                $.post('{{ route('voyager.' . $dataType->slug . '.media.remove') }}', params, function(
                    response) {
                    if (response &&
                        response.data &&
                        response.data.status &&
                        response.data.status == 200) {

                        toastr.success(response.data.message);
                        $file.parent().fadeOut(300, function() {
                            $(this).remove();
                        })
                    } else {
                        toastr.error("Error removing file.");
                    }
                });

                $('#confirm_delete_modal').modal('hide');
            });
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
