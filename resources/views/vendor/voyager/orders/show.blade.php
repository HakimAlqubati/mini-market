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
        {{ __('voyager::generic.' . ($edit ? 'edit' : 'add')) .' ' .$dataType->getTranslatedAttribute('display_name_singular') }}
    </h1>
    @include('voyager::multilingual.language-selector')
    <a href="{{ url('/generate-pdf/' . $dataTypeContent->getKey() . '') }}" class="btn btn-success">
        <i class="glyphicon glyphicon-file"></i> <span class="export-to-pdf">Export to excel</span>
    </a>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">

        
        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- form start -->

                    <div class="container">
                        <form action="{{ url('update-order', [$dataTypeContent->getKey()]) }}" method="POST">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3">
                                    {{ __('order_id') }}:
                                    <input type="number" class="form-control" value='<?php echo $finalResultOrder['id']; ?>' readonly>
                                </div>






                                <div class="col-md-3">
                                    {{ __('status_name') }}:

                                    <select class="form-control" name="order_state" aria-label="Default select example"
                                        style="                                                                                                                                                                                                                                                                                            text-align: center;"
                                        id="id_order_state" disabled>
                                        <?php foreach ($orderStates as $key =>   $value) {  ?>
                                        <?php if($finalResultOrder['order_state'] == $key ) { ?>
                                        <option value="<?php echo $key; ?>" selected> <?php echo $value; ?> </option>
                                        <?php }else{ ?>
                                        <option value="<?php echo $key; ?>"> <?php echo $value; ?> </option>
                                        <?php }  }?>
                                    </select>





                                </div>



                                <div class="col-md-3">
                                    {{ __('date') }}
                                    <input type="date" class="form-control" value='<?php echo date_format($finalResultOrder['created_at'], 'Y-m-d'); ?>' readonly>
                                </div>






                                <div class="col-md-3">
                                    {{ __('customer') }} :
                                    {{-- <input type="hidden" value='<?php echo $arrayOrder['created_by']; ?>'> --}}
                                    <input type="text" class="form-control" value='<?php echo $finalResultOrder['user_name']; ?>' readonly>
                                </div>

                                <div class="col-md-12">
                                    {{ __('customer_address') }} :
                                    <p style="border: 1px solid #eee;
                                                                                            padding: 5px;
                                                                                            border-radius: 5px;">
                                        {{ $finalResultOrder['customer_address'] }}
                                    </p>
                                </div>

                                <div class="col-md-12">
                                    {{ __('notes') }} :
                                    <p style="border: 1px solid #eee;
                                                                                            padding: 5px;
                                                                                            border-radius: 5px;">
                                        {{ $finalResultOrder['notes'] }}
                                    </p>
                                </div>



                            </div>


                            <hr>
                            <!-- Start order details -->

                            <?php foreach ($finalResultOrderItems as $key => $value) {  ?>
                            <div class="row">


                                <div class="col-md-3">
                                    {{ __('product') }} :

                                    <input type="text" class="form-control" value='<?php echo $value['product_name']; ?>' readonly>
                                </div>


                                <div class="col-md-3">
                                    {{ __('unit') }} :

                                    <input type="text" class="form-control" value='<?php echo $value['unit_name']; ?>' readonly>
                                </div>


                                <div class="col-md-3">
                                    {{ __('qty') }} :

                                    <input type="text" class="form-control" value='<?php echo $value['qty']; ?>' readonly>
                                </div>


                                <div class="col-md-3">
                                    {{ __('price') }} :

                                    <input type="text" class="form-control" value='<?php echo $value['price']; ?>' readonly>
                                </div>







                            </div>
                            <?php } ?>



                        </form>
                    </div>




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
                $('.side-body').multilingual({"editing": true});
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
