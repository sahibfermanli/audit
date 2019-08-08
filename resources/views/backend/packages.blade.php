@extends('parcel.app')
@section('title')
    Checkout
@endsection
@section('content')
    <div class="mycontainer">
        <div class="">
            <div class="main-part-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="search-inputs-area" class="search-areas">
                            <select class="form-control search-input" id="flight_search" style="min-width: 170px;">
                                <option value="">Flight</option>
                                @foreach($flights as $flight)
                                    @if($flight->id == $search_arr['flight'])
                                        <option selected value="{{$flight->id}}">{{$flight->flight_no}} {{$flight->take_off_date}}</option>
                                    @else
                                        <option value="{{$flight->id}}">{{$flight->flight_no}} {{$flight->take_off_date}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input type="text" class="form-control search-input" id="packageid_search" placeholder="Package ID" value="{{$search_arr['packageid']}}">
                            <input type="text" class="form-control search-input" id="name_search" placeholder="Name / Surname" value="{{$search_arr['name']}}">
                            <input type="text" class="form-control search-input" id="phone_search" placeholder="Phone" value="{{$search_arr['phone']}}">
                            <input type="text" class="form-control search-input" id="description_search" placeholder="Description" value="{{$search_arr['description']}}">
                            <input type="text" class="form-control search-input" id="city_search" placeholder="City" value="{{$search_arr['city']}}">
                            <input type="text" class="form-control search-input" id="address_search" placeholder="Address" value="{{$search_arr['address']}}">
                            <select class="form-control search-input" id="event_search">
                                <option value="">Event</option>
                                <option value="Arrived at the customs of Azerbaijan">Arrived at the customs of Azerbaijan</option>
                                <option value="Arrived Camex Baku">Arrived Camex Baku</option>
                                <option value="Arrived Camex Ganja">Arrived Camex Ganja</option>
                                <option value="Arrived Camex Sumgait">Arrived Camex Sumgait</option>
                                <option value="Custom Clearance">Custom Clearance</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Item pre-advised">Item pre-advised</option>
                                <option value="Local Delivery">Local Delivery</option>
                                <option value="Parcel Pick-up Notification">Parcel Pick-up Notification</option>
                                <option value="Picked up">Picked up</option>
                                <option value="Ready for delivery">Ready for delivery</option>
                                <option value="Withheld at Customs">Withheld at Customs</option>
                            </select>
                            <button type="button" class="btn btn-primary" onclick="search_data();">Search</button>
                        </div>
                        <div id="search-type-area" class="search-areas">
                            <label for="date_search">Search by date</label>
                            <input type="checkbox" id="date_search" placeholder="max" onclick="date_area();">
                        </div>
                        <div id="search-date-area" class="search-areas">
                            <label for="start_date">Start</label>
                            <input type="date" id="start_date_search" class="form-control search-input" value="{{$search_arr['start_date']}}">
                            <label for="end_date">End</label>
                            <input type="date" id="end_date_search" class="form-control search-input" value="{{$search_arr['end_date']}}">
                        </div>
                    </div>
                </div>
                <div class="table-cl">
                    <div>
                        {!! $packages->links(); !!}
                    </div>
                    <table class="references-table">
                        <thead>
                        <tr>
                            {{--<th><input type="checkbox" id="checkAll"></th>--}}
                            <th>#</th>
                            <th>Flight</th>
                            <th>Package ID</th>
                            <th>Status</th>
                            <th>Position</th>
                            <th>N/S</th>
                            <th>Price</th>
                            <th>Weight</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Destination city</th>
                            <th>Destination adress</th>
                            <th>Contact number</th>
                        </tr>
                        </thead>
                        @php($row = 0)
                        @foreach($packages as $package)
                            @php($row++)

                            @if($package->DestinationBranch == null)
                                <!-- Courier -->
                                @php($color = '#fe195e')
                            @else
                                <!-- Office -->
                                @php($color = '#08a6c3')
                            @endif

                            @php($description = $package->Description)

                            <tr class="rows" id="row_{{$row}}" onclick="select_row({{$row}})">
                                <td>{{$row}}</td>
                                <td>{{$package->flight_no}} {{$package->take_off_date}}</td>
                                <td><span class="text-primary"
                                          style="color: {{$color}} !important;">{{$package->OriginalPackageID}}</span>
                                </td>
                                <td onclick="show_events('{{$package->OriginalPackageID}}')">{{$package->last_event}}</td>
                                <td>{{$package->position}}</td>
                                <td>{{$package->DestinationName}}</td>
                                <td>{{$package->Value}}</td>
                                <td>{{$package->Weight}}</td>
                                <td>{{$package->Quantity}}</td>
                                @if(strlen($package->Description) > 10)
                                    @php($new_desc = substr($package->Description, 0, 10).'...')
                                    <td onclick="show_full_data('Description for {{$package->OriginalPackageID}}', '{{$package->Description}}')"
                                        title="{{$package->Description}}">{{$new_desc}}</td>
                                @else
                                    <td onclick="show_full_data('Description for {{$package->OriginalPackageID}}', '{{$package->Description}}')">{{$package->Description}}</td>
                                @endif
                                <td>{{$package->DestinationCity}}</td>
                                @if(strlen($package->DestinationAddress) > 10)
                                    @php($new_address = substr($package->DestinationAddress, 0, 10).'...')
                                    <td onclick="show_full_data('Destination address for {{$package->OriginalPackageID}}', '{{$package->DestinationAddress}}')"
                                        title="{{$package->DestinationAddress}}">{{$new_address}}</td>
                                @else
                                    <td onclick="show_full_data('Destination address for {{$package->OriginalPackageID}}', '{{$package->DestinationAddress}}')">{{$package->DestinationAddress}}</td>
                                @endif
                                <td>{{$package->ContactPhoneNumber}}</td>
                            </tr>
                        @endforeach
                    </table>
                    <div>
                        {!! $packages->links(); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="events-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Events for <b id="OriginalPackageID"></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="headings">
                            <th class="column-title">#</th>
                            <th class="column-title">Event</th>
                            <th class="column-title">Date</th>
                            <th class="column-title">Country</th>
                        </tr>
                        </thead>
                        <tbody id="events_table">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="full-data-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b id="data-type"></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span id="full-data"></span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="/css/sweetalert2.min.css">

    <style>
        td {
            white-space: nowrap;
        }
    </style>
@endsection

@section('js')
    <script src="/js/jquery.form.min.js"></script>
    <script src="/js/jquery.validate.min.js"></script>
    <script src="/js/sweetalert2.min.js"></script>

    <script>
        $("#checkAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>

    <script>
        //select row
        function select_row(row) {
            $('.rows').css('background-color', 'white');
            $('#row_'+row).css('background-color', '#acecff');
        }

        var show_date_area = false;

        $(document).ready(function(){
            var url = window.location.href;
            var url_arr = url.split('packageid');
            var where_url = 'packageid' + url_arr[1];

            if (url_arr.length > 1) {
                $('.pagination').each(function(){
                    $(this).find('a').each(function(){
                        var current = $(this);
                        var old_url = current.attr('href');
                        var new_url = old_url + '&' + where_url;
                        current.prop('href', new_url);
                    });
                });
            }

            var event_search_val = "{{$search_arr['event']}}";
            $("#event_search").val(event_search_val);
        });

        function date_area() {
            if (show_date_area) {
                show_date_area = false;
                $('#search-date-area').css('display', 'none');
            } else {
                show_date_area = true;
                $('#search-date-area').css('display', 'block');
            }
        }

        function search_data() {
            var flight = $('#flight_search').val();
            var event = $('#event_search').val();
            var packageid = $('#packageid_search').val();
            var name = $('#name_search').val();
            var phone = $('#phone_search').val();
            var description = $('#description_search').val();
            var city = $('#city_search').val();
            var address = $('#address_search').val();
            var start_date = $('#start_date_search').val();
            var end_date = $('#end_date_search').val();

            var link = '?packageid=' + packageid + '&name=' + name + '&phone=' + phone + '&description=' + description + '&city=' + city + '&address=' + address + '&start_date=' + start_date + '&end_date=' + end_date + '&flight=' + flight + '&event=' + event;

            location.href = link;
        }


        //show events
        function show_events(id) {
            swal({
                title: '<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Please wait...</span>',
                text: 'Loading, please wait...',
                showConfirmButton: false
            });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "Post",
                url: '',
                data: {
                    'OriginalPackageID': id,
                    'type': 'show_events',
                    '_token': CSRF_TOKEN
                },
                success: function (response) {
                    swal.close();
                    if (response.case === 'success') {
                        var events = response.events;
                        var i = 0;
                        var event_arr = '';
                        var no = 0;
                        var event = '';
                        var date = '';
                        var company = '';
                        var tr = '';
                        var table = '';
                        for (i = 0; i < events.length; i++) {
                            event_arr = events[i];

                            no = i + 1;
                            event = event_arr['event'];
                            date = event_arr['date'];
                            if (event_arr['company'] === 0) {
                                company = 'China';
                            } else {
                                company = 'Azerbaijan';
                            }

                            tr = '<tr><td>' + no + '</td><td>' + event + '</td><td>' + date + '</td><td>' + company + '</td></tr>';
                            table = table + tr;
                        }

                        $('#events_table').html(table);
                        $('#OriginalPackageID').html(id);

                        $('#events-modal').modal('show');
                    } else {
                        swal(
                            response.title,
                            response.content,
                            response.case
                        );
                    }
                }
            });
        }

        //show full data
        function show_full_data(type, data) {
            $('#data-type').html(type);
            $('#full-data').html(data);

            $('#full-data-modal').modal('show');
        }
    </script>
@endsection