<!DOCTYPE html>
<!-- saved from url=(0059)https://adminlte.io/themes/dev/AdminLTE/pages/calendar.html -->
<html class="mdl-js" style="height: auto;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Calendar</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/ionicons.min.css') }}">
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fullcalendar.print.css') }}" media="print">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
<body class="sidebar-mini" style="height: auto;">
<div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="" style="min-height: 863px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Calendar</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href={{ route('home') }}>Home</a></li>
                            <li class="breadcrumb-item active">Calendar</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- /. box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Create Event</h3>
                            </div>
                            <form class="card-body" id="event-form" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                                    <ul class="fc-color-picker" id="color-chooser">
                                        <li><a class="text-primary" href="#"><i class="fa fa-square"></i></a></li>
                                        <li><a class="text-warning" href="#"><i class="fa fa-square"></i></a></li>
                                        <li><a class="text-success" href="#"><i class="fa fa-square"></i></a></li>
                                        <li><a class="text-danger" href="#"><i class="fa fa-square"></i></a></li>
                                        <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                                    </ul>
                                </div>
                                <div class="input-group">
                                    <input id="event-title" name="title" type="text" class="form-control" placeholder="Event Title">
                                </div>
                                <div class="input-group">
                                    <input id="event-description" name="description" type="text" class="form-control" placeholder="Event description">
                                </div>
                                <div class="input-group">
                                    <input id="event-start-date" type="text" class="form-control" placeholder="start (yyyy-mm-dd)">
                                </div>
                                <div class="input-group">
                                    <input id="event-start-time" type="text" class="form-control" placeholder="start (hh:mm) empty if all day">
                                </div>
                                <div class="input-group">
                                    <input id="event-end-date" type="text" class="form-control" placeholder="end (yyyyy-mm-dd)">
                                </div>
                                <div class="input-group">
                                    <input id="event-end-time" name="" type="text" class="form-control" placeholder="end (hh:mm) empty if all day">
                                </div>
                                <label for="file">Multimedia (png, jpg, mp3)</label>
                                <input name="file" type="file" class="form-control" accept=".png,.jpg,.mp3">
                                <label for="notify">Notification<input name="notify" type="checkbox" class="form-control"></label>
                                <input type="hidden" name="t_start" id="t_start">
                                <input type="hidden" name="t_end" id="t_end">
                                <div class="input-group-append">
                                    <button id="add-new-event" type="submit" class="btn btn-primary btn-flat">Add</button>
                                </div>
                            </form>
                        </div>
                        <div class="card" id="event-view">
                            <div class="card-header">
                                <h3 class="card-title">View Event</h3>
                            </div>
                            <div class="card-body">
                                <div id="title">Title</div>
                                <div id="description">description</div>
                                <div id="start">start</div>
                                <div id="end">end</div>

                                <div class="input-group-append">
                                    <button id="remove-event" type="button" class="btn btn-primary btn-flat" data-remove-id="0">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card card-primary">
                            <div class="card-body p-0">
                                <!-- THE CALENDAR -->
                                <div id="calendar" class="fc fc-ltr fc-unthemed"></div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /. box -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<!-- fullCalendar 2.2.5 -->
<script src="{{ asset('js/moment.min.js') }}"></script>
<script src="{{ asset('js/fullcalendar.min.js') }}"></script>
<!-- Page specific script -->
<script>
    $(function () {
        var ev_id = 1;
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear();

        var event_list = [
            {
                id              : 2,
                title          : 'Long Event',
                start          : new Date(y, m, d - 5),
                end            : new Date(y, m, d - 2),
                backgroundColor: '#f39c12', //yellow
                borderColor    : '#f39c12' //yellow
            },
            {
                id              : 3,
                title          : 'Meeting',
                start          : new Date(y, m, d, 10, 30),
                allDay         : false,
                backgroundColor: '#0073b7', //Blue
                borderColor    : '#0073b7' //Blue
            },
            {
                id              : 4,
                title          : 'Lunch',
                start          : new Date(y, m, d, 12, 0),
                end            : new Date(y, m, d, 14, 0),
                allDay         : false,
                backgroundColor: '#00c0ef', //Info (aqua)
                borderColor    : '#00c0ef' //Info (aqua)
            },
            {
                id              : 5,
                title          : 'Birthday Party',
                start          : new Date(y, m, d + 1, 19, 0),
                end            : new Date(y, m, d + 1, 22, 30),
                allDay         : false,
                backgroundColor: '#00a65a', //Success (green)
                borderColor    : '#00a65a' //Success (green)
            },
            {
                id              : 6,
                title          : 'Click for Google',
                start          : new Date(y, m, 28),
                end            : new Date(y, m, 29),
                url            : 'http://google.com/',
                backgroundColor: '#3c8dbc', //Primary (light-blue)
                borderColor    : '#3c8dbc' //Primary (light-blue)
            }
        ];

        /* initialize the calendar
         -----------------------------------------------------------------*/

        $('#calendar').fullCalendar({
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week : 'week',
                day  : 'day'
            },
            displayEventEnd: true,
            timeFormat: 'H:mm',
            //Random default events
            events:  event_list,
            eventClick: function(calEvent, jsEvent, view) {
                jsEvent.preventDefault();

                var event_view = $('#event-view');

                event_view.find('#title').html('').html(calEvent.title);
                event_view.find('#description').html('').html(calEvent.description);
                event_view.find('#url').html('').html(calEvent.url);
                event_view.find('#start').html('').html(moment(calEvent.start).format('D.M.YY H:mm'));
                event_view.find('#end').html('').html(moment(calEvent.end).format('D.M.YY H:mm'));
                event_view.find('#allDay').html('').html(calEvent.allDay);
                event_view.find('#remove-event').attr('data-remove-id', calEvent.id);

                // change the border color
                $('.fc-event').css('border-color', 'white');
                $(this).css('border-color', 'red');

            }
        })

        /* ADDING EVENTS */
        var currColor = '#3c8dbc' //Red by default
        //Color chooser button
        var colorChooser = $('#color-chooser-btn');
        $('#color-chooser > li > a').click(function (e) {
            e.preventDefault();
            //Save color
            currColor = $(this).css('color');
            //Add color effect to button
            $('#add-new-event').css({
                'background-color': currColor,
                'border-color'    : currColor
            });
        })
        $('#add-new-event').click(function (e) {
            e.preventDefault();

            var start_date;
            var end_date;

            if ($('#event-start-time').val().length) {
                var dd = $('#event-start-date').val().split('-');
                var dt = $('#event-start-time').val().split(':');
                start_date = new Date(dd[0], dd[1]-1, dd[2], dt[0], dt[1]);
            } else {
                var dd = $('#event-start-date').val().split('-');
                start_date = new Date(dd[0], dd[1]-1, dd[2]);
            }

            if ($('#event-end-time').val().length) {
                var dd = $('#event-end-date').val().split('-');
                var dt = $('#event-end-time').val().split(':');
                end_date = new Date(dd[0], dd[1]-1, dd[2], dt[0], dt[1]);
            } else {
                var dd = $('#event-end-date').val().split('-');
                end_date = new Date(dd[0], dd[1]-1, dd[2]);
            }

            $('#t_start').val(start_date);
            $('#t_end').val(end_date);

            var all_day = false;

            if (!$('#event-start-time').val().length && !$('#event-end-time').val().length) {
                all_day = true;
            }

            var eventObject = {
                id : ev_id,
                title : $('#event-title').val(),
                description : $('#event-description').val(),
                start : start_date,
                end : end_date,
                allDay: all_day,
                backgroundColor : currColor,
                borderColor : '#fff',
                color : '#000',
            };

            $('#calendar').fullCalendar('renderEvent', eventObject, true);
            ev_id++;
        });

        $('#remove-event').click(function(){
            $('#calendar').fullCalendar('removeEvents', [$(this).attr('data-remove-id')]);
        });
    })
</script>


</body></html>
