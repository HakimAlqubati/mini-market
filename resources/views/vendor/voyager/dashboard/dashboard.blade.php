 @extends('voyager::master')

 @section('css')
     <meta name="csrf-token" content="{{ csrf_token() }}">
 @stop

 @section('page_title', __('voyager::generic.') . ' ' . '')

 @section('page_header')
     <h1 class="page-title">
         {{ __('dashboard_title') }}
     </h1>
     @include('voyager::multilingual.language-selector')
 @stop

 @section('content')
     <div class="page-content edit-add container-fluid">
         <div class="row">
             <div class="col-md-12">

                 {{-- First chart --}}
                 <div class="panel panel-bordered">

                     <div class="container">


                         <div class="col-md-12" style="padding: 30px 0px 30px 0px">
                             <?php
                             
                             $chart1 = $finalDataFirstChart;
                             
                             ?>
                             <div id="chartContainer" style="height: 370px; width: 100%;"></div>

                         </div>

                     </div>

                 </div>


                 {{-- Second chart --}}
                 <div class="panel panel-bordered">

                     <div class="container">

                         <div class="col-md-12" style="padding:30px 0px 30px 0px;">
                             <?php
                             
                             $dataPoints = $finalDataSecondChart;
                             ?>
                             <div id="chartContainer2" style="height: 370px; width: 100%;"></div>

                         </div>
                     </div>
                 </div>



                 {{-- Third chart --}}
                 <div class="panel panel-bordered">

                     <div class="container">
                         <div class="col-md-12" style="text-align: center;padding: 30px 0px 30px 0px;">

                             {{-- Start total customers --}}
                             <div class="col-md-5"
                                 style="padding-top: 32px; color: black; border: 2px solid;
                                                                                                                                                                                                 padding-right: 0px;
                                                                                                                                                                                                 padding-left: 0px;
                                                                                                                                                                                                 border-radius: 45px;">
                                 <p style="font-weight: bold"> {{ __('total_customers') }}</p>
                                 <div>
                                     <img src="{{ url('/') }}/storage/icons/user.png" />

                                     <a href={{ url('/') . '/dashboard/customers' }}>
                                         <p style="font-weight: bold"> {{ $customersCount }} {{ __('customer_count') }}
                                         </p>
                                     </a>

                                 </div>
                             </div>

                             <div class="col-md-2">

                             </div>


                             {{-- Start total orders --}}
                             <div class="col-md-5"
                                 style="padding-top: 32px; color: black; border: 2px solid;
                                                                                                                                                                                                 padding-right: 0px;
                                                                                                                                                                                                 padding-left: 0px;
                                                                                                                                                                                                 border-radius: 45px;">
                                 <p style="font-weight: bold"> {{ __('total_orders') }}</p>
                                 <div>
                                     <img style="width: 100;height: 100;"
                                         src="{{ url('/') }}/storage/icons/fast-delivery.png" />

                                     <a href={{ url('/') . '/admin/orders' }}>
                                         <p style="font-weight: bold"> {{ $ordersCount }} {{ __('order_count') }} </p>
                                     </a>
                                 </div>
                             </div>




                         </div>
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
                     <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'
                     </h4>
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

             // Start first chart 
             var chart = new CanvasJS.Chart("chartContainer", {
                 animationEnabled: true,
                 exportEnabled: true,
                 title: {
                     text: "{{ __('first_chart_title') }}"
                 },
                 subtitles: [{
                     text: "{{ __('first_chart_sub_title') }}"
                 }],
                 data: [{
                     type: "pie",
                     showInLegend: "true",
                     legendText: "{label}",
                     indexLabelFontSize: 16,
                     indexLabel: "{label} - #percent%",
                     yValueFormatString: "Orders #,##0",
                     dataPoints: <?php echo json_encode($chart1, JSON_NUMERIC_CHECK); ?>
                 }]
             });
             chart.render();
             // End first chart


             //  Start second chart 

             var chart2 = new CanvasJS.Chart("chartContainer2", {
                 animationEnabled: true,
                 theme: "light2",
                 title: {
                     text: "{{ __('second_chart_title') }}"
                 },
                 axisY: {
                     title: "{{ __('number_of_orders_title') }}"
                 },
                 data: [{
                     type: "column",
                     yValueFormatString: "#,##0.## tonnes",
                     dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                 }]
             });
             chart2.render();

             // End second chart


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

 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
