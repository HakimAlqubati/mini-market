@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href={{ url('/') . '/multiselect/css/style_custom.css' }}>
@stop


@section('page_title', __('voyager::generic.'))

@section('page_header')
    <h1 class="page-title">


    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">


                <div class="panel panel-bordered">

                    <h2 style="text-align: center;"> إضافة الألوان للمنتج ({{ $productName }}) </h2>
                    <p style="text-align: center;"> إضغط على زر (إضافة الألوان) أمام كل وحدة</p>
                    <table class="table table-striped">

                        <thead>
                            <tr>
                                <th>
                                    الوحدة
                                </th>

                                <th>
                                    إضافة
                                </th>
                                <th>
                                    الألوان المختارة
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unitPrices as $item)
                                <tr class="row_unit" id="row_unit">
                                    <td>
                                        <p>{{ \App\Models\Unit::find($item->unit_id)->name }}</p>
                                    </td>



                                    <td>
                                        <a class="{{ $item->id }}" id="add-color-model"
                                            style="font-weight: bold; text-decoration: none;">إضافة الألوان</a>
                                    </td>

                                    <td>
                                        @foreach (explode(',', $item->colors) as $color)
                                            <p>{{ \App\Models\Color::find($color)?->name }}</p>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                    <div class="row" style="text-align: center;">
                        <div class="col-md-6">
                            <a href="{{ url('/') . '/admin/products/create' }}" class="btn btn-primary">إضافة منتج
                                جديد</a>
                        </div>

                        <div class="col-md-6">
                            <a href="{{ url('/') . '/admin/products' }}" class="btn btn-primary">العودة لقائمة
                                المنتجات</a>
                        </div>
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
                e.preventDefault();


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
                        location.reload();
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
