@extends('voyager::master')

@section('page_title', __('voyager::generic.view') . ' ' . $dataType->getTranslatedAttribute('display_name_singular'))

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }}
        {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

        {{-- @can('edit', $dataTypeContent)
            <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.edit') }}</span>
            </a>
        @endcan --}}
        {{-- @can('delete', $dataTypeContent)
            @if ($isSoftDeleted)
                <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                </a>
            @else
                <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                    <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                </a>
            @endif
        @endcan --}}
        @can('browse', $dataTypeContent)
            <a href="{{ route('voyager.' . $dataType->slug . '.index') }}" class="btn btn-warning">
                <i class="glyphicon glyphicon-list"></i> <span
                    class="hidden-xs hidden-sm">{{ __('voyager::generic.return_to_list') }}</span>
            </a>
        @endcan
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12 panel panel-bordered">

                {{-- <div class="panel panel-bordered" style="padding-bottom:5px;"> --}}
                <!-- form start -->
                <div class="col-md-12"
                    style="border-radius: 17px;border: 2px solid; margin-bottom: 10px; font-weight: bold;">

                    <div class="col-md-4">
                        <p>ورود طبيعية تحف وهدايا</p>
                        <p>777777777</p>
                    </div>

                    <div class="col-md-4" style="text-align: center;">
                        <p>طلبات العملاء</p>
                    </div>

                    <div class="col-md-4" style="text-align: left;">
                        <p>777777777</p>
                        <p>777777777</p>
                        <p>777777777</p>
                    </div>
                </div>



                <div class="col-md-12" style="padding-right: 0px; padding-left: 0px;">
                    <table class="table table-bordered">

                        <tr style="font-weight: bold;">

                            <td>
                                رقم الطلب: {{ $finalResultOrder['id'] }}
                            </td>

                            <td colspan="2" style="border: none;">
                                التاريخ : {{ date_format($finalResultOrder['created_at'], 'Y-m-d') }}
                            </td>

                            <td colspan="3" style="border: none;">
                                يقيد على : {{$finalResultOrder['user_name']}}
                            </td>
                            <td>
                                العملة: ر.ي
                            </td>
                        </tr>

                        <tr style="font-weight: bold;">
                            <td style="border: none;">
                                رقم المرجع: ##
                            </td>

                            <td colspan="4" style="border: none;">
                                البيان:  {{ $finalResultOrder['customer_address'] }}
                            </td>

                            <td colspan="2" style="border: none;">
                                اسم المندوب:
                            </td>
                        </tr>


                        <tr style="font-weight: bold;">
                            <td> كود الصنف </td>
                            <td> اسم الصنف </td>
                            <td> الوحدة </td>
                            <td> الكمية </td>
                            <td> ك.المجانية </td>
                            <td> السعر </td>
                            <td> الإجمالي </td>
                            <td></td>

                        </tr>

                        @foreach ($finalResultOrderItems as $key => $value)
                            <tr>
                                <td> {{ $value['product_code'] }} </td>
                                <td> {{ $value['product_name'] }} </td>
                                <td> {{ $value['unit_name'] }} </td>
                                <td>{{ $value['qty'] }} </td>
                                <td> 0</td>
                                <td>{{ $value['price'] }} </td>
                                <td> {{ $value['price'] * $value['qty'] }} </td>
                                <td></td>
                            </tr>
                        @endforeach

                    </table>
                </div>



                {{-- </div> --}}
            </div>
        </div>
    </div>


@stop

@section('javascript')
    @if ($isModelTranslatable)
        <script>
            $(document).ready(function() {
                $('.side-body').multilingual();
            });
        </script>
    @endif
    <script>
        var deleteFormAction;
        $('.delete').on('click', function(e) {
            var form = $('#delete_form')[0];

            if (!deleteFormAction) {
                // Save form action initial value
                deleteFormAction = form.action;
            }

            form.action = deleteFormAction.match(/\/[0-9]+$/) ?
                deleteFormAction.replace(/([0-9]+$)/, $(this).data('id')) :
                deleteFormAction + '/' + $(this).data('id');

            $('#delete_modal').modal('show');
        });
    </script>
@stop
