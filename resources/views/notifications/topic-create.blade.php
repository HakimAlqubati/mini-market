@extends('voyager::master')

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href={{ url('/') . '/multiselect/css/style_custom.css' }}>

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

                    <form class="form-inline form-filter">
                        <!-- CSRF TOKEN -->
                        {{-- {{ csrf_field() }} --}}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group" style="width: 100%;">
                                    <label for="exampleInputEmail1">العنوان</label>

                                    <input type="text" id="title" class="form-control" style="width: 100%" name="title"
                                        placeholder="عنوان الإشعار" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" style="width: 100%;">
                                    <label for="exampleInputEmail1">نص الإشعار</label>
                                    <input type="text" id="body" class="form-control" style="width: 100%" name="body"
                                        placeholder="نص الإشعار" />
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <button type="submit" class="btn btn-primary" id="addNotesAjax">إرسال</button>
                        </div>
                        {{-- <input class="form-control btn btn-primary" type="submit" value="إرسال"> --}}
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


    <script src={{ url('/') . '/multiselect/js/jquery.min.js' }}></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>

    <script src={{ url('/') . '/multiselect/js/bootstrap.min.js' }}></script>


    <script>
        window.onload = function() {






        }


        var params = {};
        var $file;



        $('document').ready(function() {




            jQuery('#addNotesAjax').click(function(e) {
                e.preventDefault();


                jQuery('#addNotesAjax').click(function(e) {
                    e.preventDefault();
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(this.responseText);
                        }
                    };
                    xhttp.open("POST", "https://fcm.googleapis.com/fcm/send", true);
                    xhttp.setRequestHeader("Content-type", "application/json");
                    xhttp.setRequestHeader("Authorization",
                        "key=AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI"
                    );
                    xhttp.send(JSON.stringify({
                        "to": "/topics/news",
                        "notification": {
                            "body": $('#body').val(),
                            "title": $('#title').val()
                        },
                        "data": {
                            "message": "this is massage"
                        }

                    }));

                });



                jQuery.ajax({
                    url: 'https://fcm.googleapis.com/fcm/send',
                    headers: {
                        'Content-Type': 'application/json',
                        'Access-Control-Allow-Origin': 'http://localhost',
                        'Access-Control-Allow-Methods': '*',
                        'Authorization': 'Bearer AAAA5_sMJeY:APA91bFk6Af-tHbSJWc-qEKBYQsVNxA8JE8g9onU3IbzzAXTQWNmxXH7MrKizlcgo5DdySg6M5nVOUx15k0MBrMle4LS-ZEZ3nlJic8yIXKz4gTndKCWiek5Y2VlbG_czqUU7P5uSHVI'
                    },
                    method: 'post',
                    data: {
                        "to": "/topics/news",
                        "notification": {
                            "body": $('#title').val(),
                            "title": $('#body').val()
                        },
                        "data": {
                            "message": "this is massage"
                        }


                    },
                    success: function(result) {
                        $('#dialog-modal')
                            .dialog('close')
                        alert('تم إضافة الألوان بنجاح')
                        location.reload();
                    }
                });

            });



        });
    </script>

    <script src="https://code.jquery.com/ui/1.13.0/jquery-ui.js"
        integrity="sha256-xH4q8N0pEzrZMaRmd7gQVcTZiFei+HfRTBPJ1OGXC0k=" crossorigin="anonymous"></script>


@stop

{{-- <style>
    @media print {
        .form-filter * {
            display: none !important;
        }
    }

</style> --}}
