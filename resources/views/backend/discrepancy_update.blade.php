@extends('backend.app')
@section('title')
    Update non-compliance report | {{$discrepancy->id}}
@endsection
@section('content')
    <div class="container" style="padding-top: 10px;">
        @if(session('display') == 'block')
            <div class="alert alert-{{session('class')}}" role="alert">
                {{session('message')}}
            </div>
        @endif
        <form action="{{route("post_update_discrepancy", $discrepancy->id)}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="no">№</label>
                    <input type="text" class="form-control" id="no" placeholder="№" disabled value="{{$discrepancy->id}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="item_date" required value="{{substr($discrepancy->item_date, 0, 10)}}">
                </div>
                <div class="form-group col-md-4">
                    <label for="process">Process</label>
                    <select id="process" class="form-control" name="proc_id" required>
                        <option value="">Choose...</option>
                        @foreach($processes as $process)
                            @if($discrepancy->proc_id == $process->id)
                                <option selected value="{{$process->id}}">{{$process->proc_desc}}</option>
                            @else
                                <option value="{{$process->id}}">{{$process->proc_desc}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="department">Department</label>
                    <select id="department" class="form-control" name="dep_id" required>
                        <option value="">Choose...</option>
                        @foreach($departments as $department)
                            @if($discrepancy->dep_id == $department->id)
                                <option selected value="{{$department->id}}">{{$department->department_desc}}</option>
                            @else
                                <option value="{{$department->id}}">{{$department->department_desc}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="source">Source</label>
                    <select id="source" class="form-control" name="source_id" required>
                        <option value="">Choose...</option>
                        @foreach($sources as $source)
                            @if($discrepancy->source_id == $source->id)
                                <option selected value="{{$source->id}}">{{$source->source_desc}}</option>
                            @else
                                <option value="{{$source->id}}">{{$source->source_desc}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="flight">Flight</label>
                    <select id="flight" class="form-control" name="flt_number">
                        <option value="">Choose...</option>
                        @foreach($flights as $flight)
                            @if($discrepancy->flt_number == $flight->flight)
                                <option selected value="{{$flight->flight}}">{{$flight->flight}}</option>
                            @else
                                <option value="{{$flight->flight}}">{{$flight->flight}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="short_description">Summary of non-compliance</label>
                    <input type="text" class="form-control" id="short_description" placeholder="Short description" name="item_desc_short" required value="{{$discrepancy->item_desc_short}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Detailed description of non-compliance</label>
                    <textarea style="resize: vertical;" class="form-control" id="description" placeholder="Description" cols="30" rows="5" name="item_desc">{{$discrepancy->item_desc}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="detect_person">Responsible person for determination of non-compliance</label>
                    <input type="text" class="form-control" id="detect_person" placeholder="Responsible person for determination of non-compliance" name="detect_person" value="{{$discrepancy->detect_person}}">
                </div>
                <div class="form-group col-md-6">
                    <label for="resolve_person">Responsible person for elimination of non-compliance</label>
                    <input type="text" class="form-control" id="resolve_person" placeholder="Responsible person for elimination of non-compliance" name="resolve_person" value="{{$discrepancy->resolve_person}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="need_kd">Corrective action</label>
                    <select id="need_kd" class="form-control" name="need_kd">
                        @switch($discrepancy->need_kd)
                            @case('0')
                            <option selected value="0">Undefined</option>
                            <option value="1">Is required</option>
                            <option value="2">Not required</option>
                            @break
                            @case('1')
                            <option value="0">Undefined</option>
                            <option selected value="1">Is required</option>
                            <option value="2">Not required</option>
                            @break
                            @case('2')
                            <option value="0">Undefined</option>
                            <option value="1">Is required</option>
                            <option selected value="2">Not required</option>
                            @break
                            @default
                            <option selected value="0">Undefined</option>
                            <option value="1">Is required</option>
                            <option value="2">Not required</option>
                        @endswitch
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">Status</label>
                    <select id="status" class="form-control" name="status">
                        @switch($discrepancy->status)
                            @case('1')
                            <option value="0">Open</option>
                            <option selected value="1">Closed</option>
                            @break
                            @default
                            <option selected value="0">Open</option>
                            <option value="1">Closed</option>
                        @endswitch
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="doc">Document</label>
                    <input type="file" class="form-control" id="doc" name="document" accept=".xlsx,.xls,.doc, .docx,.txt,.pdf" />
                </div>
                <div class="form-group col-md-3">
                    <label>Actions</label>
                    <div style="display: block;">
                        @if(!empty($discrepancy->doc))
                            <a target="_blank" href="{{$discrepancy->doc}}" class="btn btn-success" id="show_btn">Show doc.</a>
                            <button onclick="delete_document('{{route("delete_discrepancy_document")}}', {{$discrepancy->id}});" type="button" class="btn btn-danger" id="del_btn">Delete doc.</button>
                        @else
                            <button disabled type="button" class="btn btn-success">Show doc.</button>
                            <button disabled type="button" class="btn btn-danger">Delete doc.</button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <div style="float: left;">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-warning">Cancel</button>
                        <a href="{{route("corrective_action_list", $discrepancy->id)}}" class="btn btn-default">To the list of corrective actions</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('css')

@endsection

@section('js')

@endsection