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

                    <form class="form-inline form-filter" method="GET"
                        action="<?php echo url('/'); ?>/admin/search-report-customer">

                        <div class="form-group">
                            <label for="status"> {{ __('customer') }}:</label>
                            <select class="form-control" name="customer" id="customer">
                                <option value="">-Choose-</option>
                                @foreach ($customersData as $value)
                                    <option value="{{ $value->id }}"> {{ $value->name }} </option>
                                @endforeach

                            </select>
                        </div>






                        <input class="form-control btn btn-primary" type="submit" value="{{ __('search') }}">
                    </form>
                    <div class="container">




                        <table class="table table-striped">


                            <tr>


                                <th>{{ __('order_id') }} </th>
                                <th>{{ __('status_name') }}</th>
                                <th>{{ __('date') }}</th>
                                <th>{{ __('total_price') }}</th>

                                <th></th>

                            </tr>
                            </thead>
                            <tbody>

                                @if (count($orders) == 0)
                                    <tr>
                                        <td colspan="100%" style="text-align: center;">
                                            <h4 style="color: red;">
                                                يرجى اختيار عميل ، أو ان العميل الذي اخترته لا يوجد له طلبات!
                                            </h4>
                                        </td>
                                    </tr>
                                @endif
                                @foreach ($orders as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td> {{ $value->order_state }} </td>
                                        <td> {{ $value->created_at }} </td>
                                        <td> {{ $value->total_price }} </td>
                                        <th>
                                            <a href="{{ url('/') . '/admin/orders/' . $value->id }}">
                                                {{ __('order_details_label') }}
                                            </a>
                                        </th>
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
