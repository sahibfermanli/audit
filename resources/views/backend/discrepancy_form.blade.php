@extends('backend.app')
@section('title')
    Non-compliance report
@endsection
@section('content')
    <div class="container" style="padding-top: 10px;">
        @if(session('display') == 'block')
            <div class="alert alert-{{session('class')}}" role="alert">
                {{session('message')}}
            </div>
        @endif
        <form action="{{route("post_add_discrepancy")}}" method="post">
            {{csrf_field()}}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="no">№</label>
                    <input type="text" class="form-control" id="no" placeholder="№" disabled>
                </div>
                <div class="form-group col-md-4">
                    <label for="date">Date</label>
                    <input type="date" class="form-control" id="date" name="item_date" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="process">Process</label>
                    <select id="process" class="form-control" name="proc_id" required>
                        <option value="">Choose...</option>
                        @foreach($processes as $process)
                            <option value="{{$process->id}}">{{$process->proc_desc}}</option>
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
                            <option value="{{$department->id}}">{{$department->department_desc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="source">Source</label>
                    <select id="source" class="form-control" name="source_id" required>
                        <option value="">Choose...</option>
                        @foreach($sources as $source)
                            <option value="{{$source->id}}">{{$source->source_desc}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label for="flight">Flight</label>
                    <select id="flight" class="form-control" name="flt_number">
                        <option value="">Choose...</option>
                        @foreach($flights as $flight)
                            <option value="{{$flight->flight}}">{{$flight->flight}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="short_description">Summary of non-compliance</label>
                    <input type="text" class="form-control" id="short_description" placeholder="Short description" name="item_desc_short" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Detailed description of non-compliance</label>
                    <textarea style="resize: vertical;" class="form-control" id="description" placeholder="Description" cols="30" rows="5" name="item_desc"></textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="detect_person">Responsible person for determination of non-compliance</label>
                    <input type="text" class="form-control" id="detect_person" placeholder="Responsible person for determination of non-compliance" name="detect_person">
                </div>
                <div class="form-group col-md-6">
                    <label for="resolve_person">Responsible person for elimination of non-compliance</label>
                    <input type="text" class="form-control" id="resolve_person" placeholder="Responsible person for elimination of non-compliance" name="resolve_person">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="need_kd">Corrective action</label>
                    <select id="need_kd" class="form-control" name="need_kd">
                        <option value="0">Undefined</option>
                        <option value="1">Is required</option>
                        <option value="2">Not required</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="status">Status</label>
                    <select id="status" class="form-control" name="status">
                        <option value="0">Open</option>
                        <option value="1">Closed</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <div style="float: right;">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="reset" class="btn btn-danger">Cancel</button>
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