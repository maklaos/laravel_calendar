@extends('layouts.app')

@section('content')
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
                            <form class="card-body" id="event-form" action="{{ route('calendar.store') }}" method="post" enctype="multipart/form-data">
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
                                    <input id="event-title" name="name" type="text" class="form-control" placeholder="Event Title" required>
                                </div>
                                <div class="input-group">
                                    <input id="event-start-date" name="event-start-date" type="text" class="form-control" placeholder="start (yyyy-mm-dd)" required>
                                </div>
                                <div class="input-group">
                                    <input id="event-start-time" name="event-start-time" type="text" class="form-control" placeholder="start (hh:mm) empty if all day">
                                </div>
                                <div class="input-group">
                                    <input id="event-end-date" name="event-end-date" type="text" class="form-control" placeholder="end (yyyyy-mm-dd)" required>
                                </div>
                                <div class="input-group">
                                    <input id="event-end-time" id="event-end-time" type="text" class="form-control" placeholder="end (hh:mm) empty if all day">
                                </div>
                                <label for="media">Multimedia (png, jpg, mp3)</label>
                                <input name="file" type="file" class="form-control" accept=".png,.jpg,.mp3">
                                <label for="notify">Notification<input name="notify" type="checkbox" class="form-control"></label>
                                <input type="hidden" name="t_start" id="t_start">
                                <input type="hidden" name="t_end" id="t_end">
                                <input type="hidden" name="color" id="event-color">
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
                                <div id="start">start</div>
                                <div id="end">end</div>
                                <div id="media"></div>

                                <div class="input-group-append">
                                    <form action="{{ route('calendar.index') }}" id="event-delete" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button id="remove-event" type="submit" class="btn btn-primary btn-flat" data-remove-id="0">Delete</button>
                                    </form>
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
        //Date for the calendar events (dummy data)
        var date = new Date()
        var d    = date.getDate(),
            m    = date.getMonth(),
            y    = date.getFullYear();

        var event_list = [
            @foreach($events as $event)
            {
                id             : '{{ $event->id }}',
                title          : '{{ $event->name }}',
                start          : moment('{{ $event->t_start }}'),
                end            : moment('{{ $event->t_end }}'),
                backgroundColor: '{{ $event->color }}',
                borderColor    : '#fff'
            },
            @endforeach
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
                event_view.find('#url').html('').html(calEvent.url);
                event_view.find('#start').html('').html(moment(calEvent.start).format('D.M.YY H:mm'));
                event_view.find('#end').html('').html(moment(calEvent.end).format('D.M.YY H:mm'));
                event_view.find('#remove-event').attr('data-remove-id', calEvent.id);
                event_view.find('#event-delete').attr('action', '{{ route('calendar.index') }}/'+calEvent.id);

                // change the border color
                $('.fc-event').css('border-color', 'white');
                $(this).css('border-color', 'red');

                $.ajax({
                    url: "{{ route('calendar.index') }}/"+calEvent.id,
                    type: "GET",
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data) {
                        if (data.media.includes('.mpga')) {
                            $('#media').html('<audio controls><source src="/storage/'+data.media+'" type="audio/mpeg"></audio>');
                        } else if (data.media.includes('.png') || data.media.includes('.jpg') || data.media.includes('.jpeg'))  {
                            $('#media').html('<img src="/storage/'+data.media+'" style="max-width:100%">');
                        } else {
                            $('#media').html('');
                        }
                    },
                    error: function(e) {
                        alert('error');
                    }
                });
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
        $('#event-form').on('submit', function (e) {
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
            $('#event-color').val(currColor);

            $.ajax({
                url: "{{ route('calendar.index') }}",
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data) {
                    var eventObject = {
                        id : data,
                        title : $('#event-title').val(),
                        start : start_date,
                        end : end_date,
                        backgroundColor : currColor,
                        borderColor : '#fff',
                        color : '#000',
                    };

                    $('#calendar').fullCalendar('renderEvent', eventObject, true);

                    alert('added');
                },
                error: function ($xhr) {

                    var data = $xhr.responseJSON;
                    alert(JSON.stringify(data));
                }
            });
        });

        $('#event-delete').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data) {
                    alert('deleted');
                    $('#calendar').fullCalendar('removeEvents', [$('#remove-event').attr('data-remove-id')]);
                },
                error: function(e) {
                    alert('error');
                }
            });
        });

    })
</script>
@endsection
