@extends('backend.app')
@section('title')
    The list of corrective actions
@endsection
@section('content')
    <div class="mycontainer">
        <div class="">
            <div class="main-part-2">
                @if(session('display') == 'block')
                    <div class="alert alert-{{session('class')}}" role="alert">
                        {{session('message')}}
                    </div>
                @endif
                <table class="references-table">
                    <tr>
                        <th class="discrepancy-th">№</th>
                        <td>{{$discrepancy->id}}</td>
                        <th class="discrepancy-th">Process</th>
                        <td>{{$discrepancy->proc_code}} - {{$discrepancy->proc_desc}}</td>
                    </tr>
                    <tr>
                        <th class="discrepancy-th">Date</th>
                        <td>{{substr($discrepancy->item_date, 0, 10)}}</td>
                        <th class="discrepancy-th">Department</th>
                        <td>{{$discrepancy->dep_code}} - {{$discrepancy->department_desc}}</td>
                    </tr>
                    <tr>
                        <th class="discrepancy-th">Source</th>
                        <td>{{$discrepancy->source_code}} - {{$discrepancy->source_desc}}</td>
                        <th class="discrepancy-th">Flight</th>
                        <td>{{$discrepancy->flt_number}}</td>
                    </tr>
                    <tr>
                        <th class="discrepancy-th">Need KD</th>
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
                        <th class="discrepancy-th">Status</th>
                        @switch($discrepancy->status)
                            @case('1')
                            <td>Closed</td>
                            @break
                            @default
                            <td>Open</td>
                        @endswitch
                    </tr>
                    <tr>
                        <th class="discrepancy-head" colspan="4">Summary of non-compliance</th>
                    </tr>
                    <tr>
                        <td colspan="4">{{$discrepancy->item_desc_short}}</td>
                    </tr>
                </table>
                <div class="table-cl">

                    <a style="margin-top: 10px;" class="btn btn-warning" href="{{route("get_add_corrective", $discrepancy->id)}}">Add new correction</a>
                    <table class="references-table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>№</th>
                            <th>Date</th>
                            <th>ISO</th>
                            <th>Correction</th>
                            <th>KD Status</th>
                            <th>KD Person</th>
                            <th>Plan date</th>
                            <th>Fact</th>
                            <th>Need a plan?</th>
                            <th>Auditor</th>
                            <th>Audited</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        @php($number = 0)
                        @foreach($correctives as $corrective)
                            @if(strlen($corrective->kd_close_plan) > 10)
                                @php($close_plan_date = substr($corrective->kd_close_plan, 0, 10))
                            @else
                                @php($close_plan_date = $corrective->kd_close_plan)
                            @endif
                            @php($number++)
                            <tr class="rows" id="row_{{$corrective->id}}" onclick="select_row({{$corrective->id}})">
                                <td>
                                    <a href="{{route("get_update_corrective", [$discrepancy->id, $corrective->id])}}" class="btn btn-warning btn-xs"><i class="glyphicon glyphicon-edit"></i></a>
                                    @if(!empty($corrective->doc))
                                        <a target="_blank" href="{{$corrective->doc}}" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-file"></i></a>
                                    @else
                                        <a disabled="" class="btn btn-success btn-xs"><i class="glyphicon glyphicon-file"></i></a>
                                    @endif
                                </td>
                                <td>{{$corrective->master_id}}/{{$number}}</td>
                                <td>{{substr($corrective->reg_date, 0, 10)}}</td>
                                <td>{{$corrective->iso_number}}</td>
                                <td>{{$corrective->corr_desc_short}}</td>
                                <td>
                                    @switch($corrective->kd_status)
                                        @case('0') Not done @break
                                        @case('1') Held by @break
                                        @case('2') Canceled @break
                                    @endswitch
                                </td>
                                <td>{{$corrective->kd_person}}</td>
                                <td>{{$close_plan_date}}</td>
                                <td>{{$corrective->kd_close_fact}}</td>
                                <td align="center">
                                    @switch($corrective->need_plan)
                                        @case('0')  @break
                                        @case('1') X @break
                                    @endswitch
                                </td>
                                <td>{{$corrective->auditor}}</td>
                                <td>{{$corrective->audit_to}}</td>
                                <td>
                                    @switch($corrective->status)
                                        @case('0') Open @break
                                        @case('1') Closed @break
                                    @endswitch
                                </td>
                                <td><span onclick="del('{{route("delete_corrective", $discrepancy->id)}}', {{$corrective->id}})" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i></span></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

@endsection

@section('js')

@endsection