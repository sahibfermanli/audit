@extends('backend.app')
@section('title')
    {{$title}}
@endsection
@section('content')
    <div class="mycontainer">
        <div class="">
            <div class="main-part-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="search-inputs-area" class="search-areas">
                            <input type="text" class="form-control search-input" id="search_values" column_name="no" placeholder="№" value="{{$search_arr['no']}}">
                            <select class="form-control search-input" id="search_values" column_name="flight" style="min-width: 170px;">
                                <option value="">Flight</option>
                                @foreach($flights as $flight)
                                    @if($flight->flight == $search_arr['flight'])
                                        <option selected value="{{$flight->flight}}">{{$flight->flight}}</option>
                                    @else
                                        <option value="{{$flight->flight}}">{{$flight->flight}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="form-control search-input" id="search_values" column_name="process" style="min-width: 170px;">
                                <option value="">Process</option>
                                @foreach($processes as $process)
                                    @if($process->id == $search_arr['process'])
                                        <option selected value="{{$process->id}}">{{$process->proc_desc}}</option>
                                    @else
                                        <option value="{{$process->id}}">{{$process->proc_desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="form-control search-input" id="search_values" column_name="department" style="min-width: 170px;">
                                <option value="">Department</option>
                                @foreach($departments as $department)
                                    @if($department->id == $search_arr['department'])
                                        <option selected value="{{$department->id}}">{{$department->department_desc}}</option>
                                    @else
                                        <option value="{{$department->id}}">{{$department->department_desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <input type="text" class="form-control search-input" id="search_values" column_name="description" placeholder="description" value="{{$search_arr['description']}}">
                            <select class="form-control search-input" id="search_values" column_name="kd" style="min-width: 170px;">
                                @switch($search_arr['kd'])
                                    @case('0')
                                    <option value="all">Need KD</option>
                                    <option selected value="0">Undefined</option>
                                    <option value="1">Is required</option>
                                    <option value="2">Not required</option>
                                    @break
                                    @case('1')
                                    <option value="all">Need KD</option>
                                    <option value="0">Undefined</option>
                                    <option selected value="1">Is required</option>
                                    <option value="2">Not required</option>
                                    @break
                                    @case('2')
                                    <option value="all">Need KD</option>
                                    <option value="0">Undefined</option>
                                    <option value="1">Is required</option>
                                    <option selected value="2">Not required</option>
                                    @break
                                    @default
                                    <option selected value="all">Need KD</option>
                                    <option value="0">Undefined</option>
                                    <option value="1">Is required</option>
                                    <option value="2">Not required</option>
                                @endswitch
                            </select>
                            <select class="form-control search-input" id="search_values" column_name="source" style="min-width: 170px;">
                                <option value="">Sources</option>
                                @foreach($sources as $source)
                                    @if($source->id == $search_arr['source'])
                                        <option selected value="{{$source->id}}">{{$source->source_desc}}</option>
                                    @else
                                        <option value="{{$source->id}}">{{$source->source_desc}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-primary" onclick="search_data();">Search</button>
                        </div>
                        <div id="search-type-area" class="search-areas">
                            <label for="date_search">Search by date</label>
                            <input type="checkbox" id="date_search" placeholder="max" onclick="date_area();">
                        </div>
                        <div id="search-date-area" class="search-areas">
                            <label for="start_date">Start</label>
                            <input type="date" id="search_values" column_name="start_date" class="form-control search-input start_date_search" value="{{$search_arr['start_date']}}">
                            <label for="end_date">End</label>
                            <input type="date" id="search_values" column_name="end_date" class="form-control search-input end_date_search" value="{{$search_arr['end_date']}}">
                        </div>
                    </div>
                </div>
                <div class="table-cl">
                    <div>
                        {!! $discrepancies->links(); !!}
                    </div>
                    <table class="references-table">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="columns" onclick="sort_by('disc_records.id')">№</th>
                            <th class="columns" onclick="sort_by('disc_records.item_date')">Date</th>
                            <th class="columns" onclick="sort_by('disc_records.flt_number')">Flight</th>
                            <th class="columns" onclick="sort_by('p.proc_code')">Process</th>
                            <th class="columns" onclick="sort_by('dep.dep_code')">Department</th>
                            <th class="columns" onclick="sort_by('disc_records.item_desc_short')">Description</th>
                            <th class="columns" onclick="sort_by('need_kd')">Need KD</th>
                            <th class="columns" onclick="sort_by('s.source_code')">Source</th>
                            <th></th>
                        </tr>
                        </thead>
                        @foreach($discrepancies as $discrepancy)
                            <tr class="rows" id="row_{{$discrepancy->id}}" onclick="select_row({{$discrepancy->id}})">
                                <td></td>
                                <td>{{$discrepancy->id}}</td>
                                <td>{{$discrepancy->item_date}}</td>
                                <td>{{$discrepancy->flt_number}}</td>
                                <td>{{$discrepancy->proc_code}} - {{$discrepancy->proc_desc}}</td>
                                <td>{{$discrepancy->dep_code}} - {{$discrepancy->department_desc}}</td>
                                <td>{{$discrepancy->item_desc_short}}</td>
                                @switch($discrepancy->need_kd)
                                    @case('0')
                                    <td>Undefined</td>
                                    @break
                                    @case('1')
                                    <td style="color: green;">Is required</td>
                                    @break
                                    @case('2')
                                    <td style="color: red;">Not required</td>
                                    @break
                                    @default
                                    <td>Undefined</td>
                                @endswitch
                                <td>{{$discrepancy->source_code}} - {{$discrepancy->source_desc}}</td>
                                <td><span onclick="del('{{route("discrepancies_delete")}}', {{$discrepancy->id}})" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></span></td>
                            </tr>
                        @endforeach
                    </table>
                    <div>
                        {!! $discrepancies->links(); !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')
    <script>
        {{--$(document).ready(function(){--}}
        {{--    var url = window.location.href;--}}
        {{--    var url_arr = url.split('discrepancyid');--}}
        {{--    var where_url = 'discrepancyid' + url_arr[1];--}}

        {{--    if (url_arr.length > 1) {--}}
        {{--        $('.pagination').each(function(){--}}
        {{--            $(this).find('a').each(function(){--}}
        {{--                var current = $(this);--}}
        {{--                var old_url = current.attr('href');--}}
        {{--                var new_url = old_url + '&' + where_url;--}}
        {{--                current.prop('href', new_url);--}}
        {{--            });--}}
        {{--        });--}}
        {{--    }--}}

        {{--    var event_search_val = "{{$search_arr['event']}}";--}}
        {{--    $("#event_search").val(event_search_val);--}}
        {{--});--}}

        {{--function search_data() {--}}
        {{--    var flight = $('#flight_search').val();--}}
        {{--    var event = $('#event_search').val();--}}
        {{--    var discrepancyid = $('#discrepancyid_search').val();--}}
        {{--    var name = $('#name_search').val();--}}
        {{--    var phone = $('#phone_search').val();--}}
        {{--    var description = $('#description_search').val();--}}
        {{--    var city = $('#city_search').val();--}}
        {{--    var address = $('#address_search').val();--}}
        {{--    var start_date = $('#start_date_search').val();--}}
        {{--    var end_date = $('#end_date_search').val();--}}

        {{--    var link = '?discrepancyid=' + discrepancyid + '&name=' + name + '&phone=' + phone + '&description=' + description + '&city=' + city + '&address=' + address + '&start_date=' + start_date + '&end_date=' + end_date + '&flight=' + flight + '&event=' + event;--}}

        {{--    location.href = link;--}}
        {{--}--}}
    </script>
@endsection