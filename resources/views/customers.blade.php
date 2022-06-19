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


                    <div class="container">




                        <table class="table table-striped">
                            <thead>


                                <tr>


                                    <th> {{ __('customer_id_label') }} </th>
                                    <th> {{ __('customer_name_label') }}</th>
                                    <th> {{ __('phone_number_label') }}</th>
                                    <th> {{ __('orders_count') }}</th>
                                    <th> {{ __('registered_date') }}</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($customersData as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td> {{ $value->name }} </td>
                                        <td> {{ $value->phone }} </td>
                                        <td> {{ $value->orders_count }} </td>
                                        <td> {{ $value->created_at }} </td>
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
