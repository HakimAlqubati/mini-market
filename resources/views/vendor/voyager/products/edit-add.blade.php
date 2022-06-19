@php
$edit = !is_null($dataTypeContent->getKey());
$add = is_null($dataTypeContent->getKey());
@endphp

@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href={{ url('/') . '/multiselect/css/style_custom.css' }}>
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

                                        <td>

                                            <input type="text" class="form-control"
                                                placeholder="{{ __('english_name_hint') }}" name="english_name"
                                                value="<?php echo $edit ? $product_details->english_name : ''; ?>" required />
                                        </td>

                                        <td>
                                            <input type="text" class="form-control"
                                                placeholder="{{ __('product_code') }}" name="code"
                                                value="<?php echo $edit ? $product_details->code : ''; ?>" required />

                                            @if (count($errors) > 0)
                                                <div class="alert alert-danger" role="alert">
                                                    {{ $errors->first('code') }}
                                                </div>
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
                                                <option value="">-إختر-</option>
                                                <?php foreach ($mainGroups as $key => $value) {  ?>
                                                @if ($edit && \App\Models\SubGroup::find($product_details->group_id)->parent_id == $value->id)
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
                                                
                                              if($edit) {
                                                  foreach (\App\Models\SubGroup::where('parent_id', \App\Models\SubGroup::find($product_details->group_id)->parent_id)->get() as $key => $value) {
                                                       ?>
                                                @if ($edit && $product_details->group_id == $value->id)
                                                    <option value="<?php echo $value->id; ?>" selected> <?php echo $value->name; ?>
                                                    </option>
                                                @endif
                                                <option value="<?php echo $value->id; ?>"> <?php echo $value->name; ?></option>

                                                <?php } }?>


                                            </select>
                                        </td>


                                        <td colspan="2">
                                            <input height="2" width="2" class="form-control" type="file" id="formFile"
                                                name="images[]" multiple>
                                        </td>
                                    </tr>
                                    <tr>

                                        <th>{{ __('unit') }}</th>
                                        <th>{{ __('price') }}</th>
                                        @if ($edit == 1)
                                            <th> إضافة الألوان</th>
                                            <th>
                                                الألوان المختارة
                                            </th>
                                        @endif
                                        <th> </th>
                                        <th> </th>
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

                                                @if ($edit == 1)
                                                    <td style="text-align: center">

                                                        <a class="{{ $item->id }}" id="add-color-model"
                                                            style="font-weight: bold; text-decoration: none;"> إضافة
                                                            الألوان</a>
                                                    </td>

                                                    <td>
                                                        @foreach (explode(',', $item->colors) as $color)
                                                            <p>{{ \App\Models\Color::find($color)?->name }}</p>
                                                        @endforeach
                                                    </td>
                                                @endif

                                                <td>

                                                    <a id="create-one" class="{{ $item->id }}"
                                                        style="font-size: 30px; font-weight: bold; text-decoration: none;">
                                                        +
                                                    </a>
                                                </td>

                                                <td>

                                                    <a id="remove" class="remove"
                                                        style="font-size: 30px; font-weight: bold; text-decoration: none;">
                                                        -
                                                    </a>


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
                                            <td colspan="2">
                                                <input type="number" name="price[]" id="" required>
                                            </td>



                                            <td>




                                                <a id="create-one"
                                                    style="font-size: 30px; font-weight: bold; text-decoration: none;">
                                                    +
                                                </a>
                                            </td>

                                            <td>

                                                <a id="remove" class="remove"
                                                    style="font-size: 30px; font-weight: bold; text-decoration: none;">
                                                    -
                                                </a>


                                            </td>

                                        </tr>

                                    @endif




                                </tbody>
                            </table>


                            <div style="width: 100%;text-align: center">

                                <div class="form-check">
                                    @if ($edit && $product_details->active == 1)
                                        <?php $value = 'checked'; ?>
                                    @else
                                        <?php $value = ''; ?>
                                    @endif
                                    <input class="form-check-input" type="checkbox" value="checked" id="flexCheckDefault"
                                        name="active" {{ $value }}>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        {{ __('active') }} ?
                                    </label>
                                </div>

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


    <!-- Start Color Modal -->

    <div id="dialog-modal"
        style=" text-align: center;  background: #fafafa; border: 4px solid #755245; border-radius: 20px; padding: 20px;width: 700px !important;">
        <form action="">

            <div class="dropdown-container">
                <div class="dropdown-button noselect w-100">
                    <div class="dropdown-label">ألوان</div>
                    <div class="dropdown-quantity">(<span class="quantity">0</span>)</div>
                    <i class="fa fa-chevron-down"></i>
                </div>
                <div class="dropdown-list" style="display: none;">

                    <input type="search" placeholder="ابحث عن الألوان..." class="dropdown-search" />
                    <ul id="ul"></ul>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="addNotesAjax">إرسال</button>
        </form>
    </div>

    <!-- End Color Modal -->
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


            // $('#btn-add').click(function() {

            //     var trid = $(this).closest('tr').attr('id'); // table row ID 

            //     $('<tr class="row_unit" id="row_unit" >' + $('#row_unit').html() + '</tr>').insertAfter(
            //         'table tr:last');
            // });

            // $('#btn-remove').click(function() {
            //     // var rowIndex = $('#my-table tr:last').index(this);
            //     // alert(rowIndex)
            //     var last_id = $('#my-table tr:last').attr('id');
            //     $('#' + last_id).remove();
            // });


            $(document).on('click', '#create-one', function() {

                var trIndex = $(this).closest("tr").index();
                // alert($(this).attr('class'))

                var trHtml = $(this).closest("tr").html()

                var unit = '<td> <select name="unit[]" id="" required>'

                <?php foreach ($units as $key => $value) {?>

                    +
                    '<option value="{{ $value->id }}">   {{ $value->name }} </option>'

                <?php } ?>
                    +
                    '</select>' +
                    '</td>'
                var price = '<td> <input name="price[]" type="text" required> </td>'

                var plus =
                    '<td> <a id="create-one" style="font-size: 30px; font-weight: bold; text-decoration: none;"> + </a> </td>'

                var minus =
                    '<td> <a id="remove" class="remove" style="font-size: 30px; font-weight: bold; text-decoration: none;"> - </a> </td>'


                $("<tr class='row_unit'  >" + '<input type="hidden" name="unit_price_id[]" >' + unit +
                    price + '<td> </td> <td> </td>' + plus + minus +
                    "</tr>").insertAfter(".row_unit:last");

            });

            $(document).on('click', '.remove', function() {

                var trIndex = $(this).closest("tr").index();
                if (trIndex == 0) {
                    alert("Sorry!! Can't remove first row!");
                } else {
                    $(this).closest("tr").remove();
                }

            });







            // Main multi select 



        });
    </script>


    <script src={{ url('/') . '/multiselect/js/jquery.min.js' }}></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>

    <script src={{ url('/') . '/multiselect/js/bootstrap.min.js' }}></script>


    <script>
        $('document').ready(function() {

            $('#dialog-modal').dialog({
                modal: true,
                autoOpen: false
            });

            $(document).on('click', '#add-color-model', function() {

                var unitPriceId = $(this).attr('class');



                $('#dialog-modal')
                    .data('param_unit_price_id', unitPriceId)
                    .dialog('open');

            });



            jQuery('#addNotesAjax').click(function(e) {
                // e.preventDefault();


                jQuery.ajax({
                    url: '/spices-sys/add-colors-unit-price/' + $("#dialog-modal").data(
                        'param_unit_price_id'),
                    method: 'post',
                    data: {

                        colors: $("input:checkbox:checked[name='colors[]']").map(function() {
                            return $(this).val();
                        }).get()

                    },
                    success: function(result) {
                        $('#dialog-modal')
                            .dialog('close')
                        alert('تم إضافة الألوان بنجاح')
                    }
                });

            });




            (function($) {
                $(document)
                    .on("click", ".dropdown-button", function() {
                        $(this).siblings(".dropdown-list").toggle();
                    })
                    .on("input", ".dropdown-search", function() {
                        var target = $(this);
                        var dropdownList = target.closest(".dropdown-list");
                        var search = target.val().toLowerCase();

                        if (!search) {
                            dropdownList.find("li").show();
                            return false;
                        }

                        dropdownList.find("li").each(function() {
                            var text = $(this).text().toLowerCase();
                            var match = text.indexOf(search) > -1;
                            $(this).toggle(match);
                        });
                    })
                    .on("change", '[type="checkbox"]', function() {
                        var container = $(this).closest(".dropdown-container");
                        var numChecked = container.find('[type="checkbox"]:checked').length;
                        container.find(".quantity").text(numChecked || "Any");
                    });

                // JSON of States for demo purposes

                var colors = <?php
                $colors = \App\Models\Color::get();
                foreach ($colors as $color_val) {
                    $final[] = [
                        'name' => $color_val->name,
                        'abbreviation' => $color_val->id,
                        'capName' => $color_val->name,
                    ];
                }
                echo json_encode($final);
                ?>



                var usStates = [{
                        name: "ALABAMA",
                        abbreviation: "AL"
                    },
                    {
                        name: "ALASKA",
                        abbreviation: "AK"
                    },

                ];

                var stateTemplate = _.template(


                    "<li>" +

                    '<label class="checkbox-wrap"><input id="colorsid" name="colors[]" value="<%= abbreviation %>" type="checkbox"> <span for="<%= abbreviation %>"><%= capName %></span> <span class="checkmark"></span></label>' +
                    // '<label for="<%= abbreviation %>"><%= capName %></label>' +
                    "</li>"
                );

                console.log('hakeeem===> ', colors)
                // Populate list with states
                _.each(colors, function(s) {
                    s.capName = _.startCase(s.name.toLowerCase());
                    $("#ul").append(stateTemplate(s));
                });


            })(jQuery);


        });
    </script>

    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"
        integrity="sha256-xH4q8N0pEzrZMaRmd7gQVcTZiFei+HfRTBPJ1OGXC0k=" crossorigin="anonymous"></script>

@stop
