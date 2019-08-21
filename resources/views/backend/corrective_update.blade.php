@extends('backend.app')
@section('title')
    Add correction
@endsection
@section('content')
    <div class="container" style="padding-top: 10px;">
        @if(session('display') == 'block')
            <div class="alert alert-{{session('class')}}" role="alert">
                {{session('message')}}
            </div>
        @endif
        <form action="{{route("post_update_corrective", [$discrepancy->id, $corrective->id])}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="reg_date">Date</label>
                    <input type="date" class="form-control" id="reg_date" name="reg_date" required value="{{$corrective->reg_date}}">
                </div>
                <div class="form-group col-md-3">
                    <label for="iso_number">ISO</label>
                    <input type="text" class="form-control" id="iso_number" placeholder="ISO number" name="iso_number" maxlength="50" value="{{$corrective->iso_number}}">
                </div>
                <div class="form-group col-md-3">
                    <label for="auditor">Auditor</label>
                    <input type="text" class="form-control" id="auditor" placeholder="Auditor" name="auditor" maxlength="255" value="{{$corrective->auditor}}">
                </div>
                <div class="form-group col-md-3">
                    <label for="audit_to">Audited</label>
                    <input type="text" class="form-control" id="audit_to" placeholder="Audited" name="audit_to" maxlength="255" value="{{$corrective->audit_to}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="description">Detailed description of non-compliance</label>
                    <textarea readonly style="resize: vertical;" class="form-control" id="description" placeholder="Description" cols="30" rows="3">{{$discrepancy->item_desc}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="inc_reason">Reason for non-compliance</label>
                    <textarea style="resize: vertical;" class="form-control" id="inc_reason" placeholder="Reason for non-compliance" cols="30" rows="3" name="inc_reason">{{$corrective->inc_reason}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="corr_desc_short">Corrective action (Short description)</label>
                    <textarea required style="resize: vertical;" class="form-control" id="corr_desc_short" placeholder="Short description" cols="30" rows="3" name="corr_desc_short">{{$corrective->corr_desc_short}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="corr_desc">Corrective action (Detailed description)</label>
                    <textarea required style="resize: vertical;" class="form-control" id="corr_desc" placeholder="Detailed description" cols="30" rows="3" name="corr_desc">{{$corrective->corr_desc}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="kd_person">KD Person</label>
                    <input required maxlength="255" type="text" class="form-control" id="kd_person" name="kd_person" value="{{$corrective->kd_person}}">
                </div>
                <div class="form-group col-md-2">
                    <label for="need_plan">Need plan?</label>
                    <select id="need_plan" class="form-control" name="need_plan">
                        @switch($corrective->need_plan)
                            @case('1')
                            <option value="0">No</option>
                            <option selected value="1">Yes</option>
                            @break
                            @default
                            <option selected value="0">No</option>
                            <option value="1">Yes</option>
                        @endswitch
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="kd_status">KD Status</label>
                    <select id="kd_status" class="form-control" name="kd_status">
                        @switch($corrective->kd_status)
                            @case('1')
                            <option value="0">Not done</option>
                            <option selected value="1">Held by</option>
                            <option value="2">Canceled</option>
                            @break
                            @case('2')
                            <option value="0">Not done</option>
                            <option value="1">Held by</option>
                            <option selected value="2">Canceled</option>
                            @break
                            @default
                            <option selected value="0">Not done</option>
                            <option value="1">Held by</option>
                            <option value="2">Canceled</option>
                        @endswitch
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="kd_close_plan">Planned date</label>
                    <input type="date" class="form-control" id="kd_close_plan" name="kd_close_plan" value="{{substr($corrective->kd_close_plan, 0, 10)}}">
                </div>
                <div class="form-group col-md-2">
                    <label for="kd_close_fact">Actual date</label>
                    <input type="date" class="form-control" id="kd_close_fact" name="kd_close_fact" value="{{substr($corrective->kd_close_fact, 0, 10)}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="effect_desc">Non-compliance closure verification and performance evaluation</label>
                    <textarea style="resize: vertical;" class="form-control" id="effect_desc" placeholder="Description of efficiency" cols="30" rows="3" name="effect_desc">{{$corrective->effect_desc}}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="eff_person">Person of efficiency</label>
                    <input type="text" class="form-control" id="eff_person" placeholder="Person of efficiency" name="eff_person" value="{{$corrective->eff_person}}">
                </div>
                <div class="form-group col-md-3">
                    <label for="eff_status">Status of efficiency</label>
                    <select id="eff_status" class="form-control" name="eff_status">
                        @switch($corrective->eff_status)
                            @case('1')
                            <option value="0">Not determined</option>
                            <option selected value="1">Not effective</option>
                            <option value="2">Effectively</option>
                            @break
                            @case('2')
                            <option value="0">Not determined</option>
                            <option value="1">Not effective</option>
                            <option selected value="2">Effectively</option>
                            @break
                            @default
                            <option selected value="0">Not determined</option>
                            <option value="1">Not effective</option>
                            <option value="2">Effectively</option>
                        @endswitch
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="status">General status</label>
                    <select id="status" class="form-control" name="status">
                        @switch($corrective->status)
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
                    <label for="eff_close_date">Date of efficiency</label>
                    <input type="date" class="form-control" id="eff_close_date" name="eff_close_date" value="{{substr($corrective->eff_close_date, 0, 10)}}">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="doc">Document</label>
                    <input type="file" class="form-control" id="doc" name="document" accept=".xlsx,.xls,.doc, .docx,.txt,.pdf" />
                </div>
                <div class="form-group col-md-3">
                    <label>Actions</label>
                    <div style="display: block;">
                        @if(!empty($corrective->doc))
                            <a target="_blank" href="{{$corrective->doc}}" class="btn btn-success" id="show_btn">Show doc.</a>
                            <button onclick="delete_document('{{route("delete_corrective_document", $discrepancy->id)}}', {{$corrective->id}});" type="button" class="btn btn-danger" id="del_btn">Delete doc.</button>
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
    <script>
        $(document).ready(function(){
            var current_date = get_current_date();

            $("#reg_date").val(current_date);
        });
    </script>
@endsection