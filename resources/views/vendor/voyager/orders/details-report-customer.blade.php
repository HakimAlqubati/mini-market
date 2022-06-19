@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('page_title', __('voyager::generic.') . ' ' . '')

@section('page_header')

    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">


                <div class="panel panel-bordered">

                    <form class="form-inline form-filter" method="GET" action="<?php echo url('/'); ?>/admin/report-customer">

                        <div class="form-group">
                            <label for="status"> {{ __('customer') }}:</label>
                            <select class="form-control" name="customer" id="customer">
                                <option value="">-Choose-</option>
                                @foreach ($customersData as $value)
                                    <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                @endforeach

                            </select>
                        </div>


                        <div class="form-group">
                            <label for="status">{{ __('status_name') }}:</label>
                            <select class="form-control" name="order_state" id="order_state">
                                <option value="">-Choose-</option>
                                <option value="ordered">Ordered</option>
                                <option value="processing"> Processing </option>
                                <option value="completed"> Completed </option>
                                <option value="cancelled"> Cancelled </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status"> {{ __('from_date') }}:</label>
                            <input type="date" name="from_date" type="date" class="form-control">

                            <label for="status">{{ __('to_date') }}:</label>
                            <input type="date" name="to_date" type="date" class="form-control">

                        </div>


                        <input class="form-control btn btn-primary" type="submit" value="{{ __('search') }}">
                    </form>
                    <div class="container">




                        <table class="table table-striped">
                            <thead>
                                <tr>

                                    <td colspan="2" style="text-align: center">
                                        <p
                                            style="display: inline;vertical-align: middle;line-height: 5.5;font-weight: bold;">
                                            {{ __('order_titls') }}( {{ $state_name }} ) </p>
                                        <br>
                                        <p style="display: inline;vertical-align: middle;font-weight: bold;">
                                            {{ __('customer') }}( {{ $customerName }} )
                                        </p>
                                    </td>
                                    <td>
                                        <img src="<?php echo url('/'); ?>/storage/icons/logo.png" height="100px" />
                                    </td>
                                    <td colspan="2" style="text-align: center;">
                                        <p
                                            style="display: inline;vertical-align: middle;line-height: 5.5;font-weight: bold;">
                                            {{ __('from_date') }}: {{ $from_date }}
                                        </p>
                                        <br>
                                        <p style="display: inline;vertical-align: middle;font-weight: bold;">
                                            {{ __('to_date') }}: {{ $to_date }}
                                        </p>
                                    </td>
                                </tr>

                                <tr>


                                    <th>كود الصنف</th>
                                    <th>{{ __('product') }} </th>
                                    <th>{{ __('unit') }}</th>
                                    <th>{{ __('qty') }}</th>
                                    <th>{{ __('status_name') }}</th>
                                    <th>{{ __('total_price') }}</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($final as $value)
                                    <tr>
                                        <td>{{ $value->product_code }}</td>
                                        <td>{{ $value->product_name }}</td>
                                        <td> {{ $value->unit_name }} </td>
                                        <td> {{ $value->qty }} </td>
                                        <td> {{ $value->order_state }} </td>
                                        <td> {{ $value->price }} </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>



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
        window.onload = function() {






        }


        var params = {};
        var $file;



        $('document').ready(function() {
            $('.toggleswitch').bootstrapToggle();




            $('.form-group').on('click', '.remove-multi-image', deleteHandler('img', true));
            $('.form-group').on('click', '.remove-single-image', deleteHandler('img', false));
            $('.form-group').on('click', '.remove-multi-file', deleteHandler('a', true));
            $('.form-group').on('click', '.remove-single-file', deleteHandler('a', false));


            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop

{{-- <style>
    @media print {
        .form-filter * {
            display: none !important;
        }
    }

</style> --}}
