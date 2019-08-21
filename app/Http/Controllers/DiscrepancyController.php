<?php

namespace App\Http\Controllers;

use App\Departments;
use App\Discrepancy;
use App\Flights;
use App\Process;
use App\Sources;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DiscrepancyController extends HomeController
{
    public function get_discrepancy_records() {
        return $this->discrepancy_records(0, "Discrepancies");
    }

    public function get_discrepancy_archive() {
        return $this->discrepancy_records(1, "Archive");
    }

    private function discrepancy_records($status=0, $title="Discrepancies") {
        try {
            $query = Discrepancy::leftJoin('proc_list as p', 'disc_records.proc_id', '=', 'p.id')
                ->leftJoin('department_list as dep', 'disc_records.dep_id', '=', 'dep.id')
                ->leftJoin('source_list as s', 'disc_records.source_id', '=', 's.id')
                ->whereNull('disc_records.deleted_by')
                ->where('disc_records.status', $status);

            $search_arr = array(
                'no' => '',
                'flight' => '',
                'process' => '',
                'department' => '',
                'description' => '',
                'kd' => '',
                'source' => '',
                'start_date' => '',
                'end_date' => ''
            );

            if (!empty(Input::get('no')) && Input::get('no') != ''  && Input::get('no') != null) {
                $where_no = Input::get('no');
                $query->where('disc_records.id', $where_no);
                $search_arr['no'] = $where_no;
            }

            if (!empty(Input::get('flight')) && Input::get('flight') != ''  && Input::get('flight') != null) {
                $where_flight = Input::get('flight');
                $query->where('disc_records.flt_number', 'LIKE', '%'.$where_flight.'%');
                $search_arr['flight'] = $where_flight;
            }

            if (!empty(Input::get('process')) && Input::get('process') != ''  && Input::get('process') != null) {
                $where_process = Input::get('process');
                $query->where('disc_records.proc_id', $where_process);
                $search_arr['process'] = $where_process;
            }

            if (!empty(Input::get('department')) && Input::get('department') != ''  && Input::get('department') != null) {
                $where_department = Input::get('department');
                $query->where('disc_records.dep_id', $where_department);
                $search_arr['department'] = $where_department;
            }

            if (!empty(Input::get('description')) && Input::get('description') != ''  && Input::get('description') != null) {
                $where_description = Input::get('description');
                $query->where('disc_records.item_desc_short', 'LIKE', '%'.$where_description.'%');
                $search_arr['description'] = $where_description;
            }

            if (Input::get('kd') != ''  && Input::get('kd') != null && Input::get('kd') != 'all') {
                $where_kd = Input::get('kd');
                $query->where('disc_records.need_kd', $where_kd);
                $search_arr['kd'] = $where_kd;
            }

            if (!empty(Input::get('source')) && Input::get('source') != ''  && Input::get('source') != null) {
                $where_source = Input::get('source');
                $query->where('disc_records.source_id', $where_source);
                $search_arr['source'] = $where_source;
            }

            if (!empty(Input::get('start_date')) && Input::get('start_date') != ''  && Input::get('start_date') != null) {
                $where_start_date = Input::get('start_date');
                $query->where('disc_records.item_date', '>=', $where_start_date);
                $search_arr['start_date'] = $where_start_date;
            }

            if (!empty(Input::get('end_date')) && Input::get('end_date') != ''  && Input::get('end_date') != null) {
                $where_end_date = Input::get('end_date');
                $search_arr['end_date'] = $where_end_date;
                $where_end_date = new DateTime($where_end_date);
                $where_end_date = $where_end_date->modify('+1 day');
                $query->where('disc_records.item_date', '<=', $where_end_date);
            }

            //short by start
            $short_by = 'disc_records.id';
            $shortType = 'DESC';
            if (!empty(Input::get('shortBy')) && Input::get('shortBy') != ''  && Input::get('shortBy') != null) {
                $short_by = Input::get('shortBy');
            }
            if (!empty(Input::get('shortType')) && Input::get('shortType') != ''  && Input::get('shortType') != null) {
                $short_type = Input::get('shortType');
                if ($short_type == 2) {
                    $shortType = 'DESC';
                } else {
                    $shortType = 'ASC';
                }
            }
            //short by finish

            $discrepancies = $query
                ->orderBy($short_by, $shortType)
                ->select(
                    'disc_records.id',
                    'disc_records.doc',
                    'disc_records.item_date',
                    'disc_records.flt_number',
                    'disc_records.item_desc_short',
                    'disc_records.need_kd',
                    'p.proc_desc',
                    'p.proc_code',
                    'dep.department_desc',
                    'dep.dep_code',
                    's.source_desc',
                    's.source_code'
                )
                ->paginate(50);

            $flights = Flights::whereNull('deleted_by')->orderBy('flight')->select('flight')->get();
            $processes = Process::whereNull('deleted_by')->orderBy('proc_desc')->select('id', 'proc_desc')->get();
            $departments = Departments::whereNull('deleted_by')->orderby('department_desc')->select('id', 'department_desc')->get();
            $sources = Sources::whereNull('deleted_by')->orderBy('source_desc')->select('id', 'source_desc')->get();

            return view("backend.discrepancies", compact(
                'discrepancies',
                'title',
                'search_arr',
                'flights',
                'processes',
                'departments',
                'sources'
            ));
        } catch (\Exception $exception) {
            return view('backend.error');
        }
    }

    public function delete_discrepancy_record(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['case' => 'warning', 'title' => 'Warning!', 'content' => 'Id not found!']);
        }
        try {
            $delete = Discrepancy::where(['id'=>$request->id])->update(['deleted_at'=>Carbon::now(), 'deleted_by'=>Auth::id()]);

//            if ($delete) {
//                Chamber::where(['area_id'=>$request->id])->whereNull('deleted_by')->update(['deleted_at'=>Carbon::now(), 'deleted_by'=>Auth::id()]);
//            }

            return response(['case' => 'success', 'title' => 'Success!', 'content' => 'Successful!', 'id'=>$request->id]);
        } catch (\Exception $e) {
            return response(['case' => 'error', 'title' => 'Error!', 'content' => 'An error occurred!']);
        }
    }

    public function get_add_discrepancy() {
        try {
            $flights = Flights::whereNull('deleted_by')->orderBy('flight')->select('flight')->get();
            $processes = Process::whereNull('deleted_by')->orderBy('proc_desc')->select('id', 'proc_desc')->get();
            $departments = Departments::whereNull('deleted_by')->orderby('department_desc')->select('id', 'department_desc')->get();
            $sources = Sources::whereNull('deleted_by')->orderBy('source_desc')->select('id', 'source_desc')->get();

            return view("backend.discrepancy_add", compact(
                'flights',
                'processes',
                'departments',
                'sources'
            ));
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function post_add_discrepancy(Request $request) {
        $validator = Validator::make($request->all(), [
            'item_date' => ['required', 'date'],
            'flt_number' => ['nullable', 'string', 'max:10'],
            'proc_id' => ['required', 'integer'],
            'dep_id' => ['required', 'integer'],
            'item_desc' => ['nullable', 'string'],
            'item_desc_short' => ['required', 'string'],
            'source_id' => ['required', 'integer'],
            'need_kd' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
            'detect_person' => ['nullable', 'string', 'max:255'],
            'resolve_person' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'mimes:doc,docx,xls,xlsx,pdf,txt'],
        ]);
        if ($validator->fails()) {
            Session::flash('message', 'Please fill in the required fields!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->refresh();
        }
        try {
            $request->merge(['created_by'=>Auth::id()]);

            if (isset($request->document)) {
                $image_name = 'discrepancy_' . str_random(4) . '_' . microtime();
                Storage::disk('uploads')->makeDirectory('files/documents');
                $cover = $request->file('document');
                $extension = $cover->getClientOriginalExtension();
                Storage::disk('uploads')->put('files/documents/'. $image_name.'.'.$extension,  File::get($cover));
                $image_address = '/uploads/files/documents/' . $image_name.'.'.$extension;
                $request['doc'] = $image_address;
            }

            unset($request['document']);
            $request = Input::except('document');

            Discrepancy::create($request);

            Session::flash('message', 'Discrepancy successfully added!');
            Session::flash('class', 'success');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function get_update_discrepancy($id) {
        if (!isset($id) || empty($id) || is_int($id)) {
            Session::flash('message', 'Oops! ID not found!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        }

        try {
            $discrepancy = Discrepancy::where('id', $id)->whereNull('deleted_by')->select(
                'id',
                'doc',
                'item_date',
                'proc_id',
                'dep_id',
                'source_id',
                'flt_number',
                'item_desc_short',
                'item_desc',
                'detect_person',
                'resolve_person',
                'need_kd',
                'status'
            )->first();

            if (!$discrepancy) {
                Session::flash('message', 'Oops! Discrepancy not found!');
                Session::flash('class', 'warning');
                Session::flash('display', 'block');
                return redirect()->route("discrepancies");
            }

            $flights = Flights::whereNull('deleted_by')->orderBy('flight')->select('flight')->get();
            $processes = Process::whereNull('deleted_by')->orderBy('proc_desc')->select('id', 'proc_desc')->get();
            $departments = Departments::whereNull('deleted_by')->orderby('department_desc')->select('id', 'department_desc')->get();
            $sources = Sources::whereNull('deleted_by')->orderBy('source_desc')->select('id', 'source_desc')->get();

            return view("backend.discrepancy_update", compact(
                'flights',
                'processes',
                'departments',
                'sources',
                'discrepancy'
            ));
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function post_update_discrepancy(Request $request, $id) {
        if (!isset($id) || empty($id) || is_int($id)) {
            Session::flash('message', 'Oops! ID not found!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        }

        $validator = Validator::make($request->all(), [
            'item_date' => ['required', 'date'],
            'flt_number' => ['nullable', 'string', 'max:10'],
            'proc_id' => ['required', 'integer'],
            'dep_id' => ['required', 'integer'],
            'item_desc' => ['nullable', 'string'],
            'item_desc_short' => ['required', 'string'],
            'source_id' => ['required', 'integer'],
            'need_kd' => ['nullable', 'integer'],
            'status' => ['nullable', 'integer'],
            'detect_person' => ['nullable', 'string', 'max:255'],
            'resolve_person' => ['nullable', 'string', 'max:255'],
            'document' => ['nullable', 'mimes:doc,docx,xls,xlsx,pdf,txt'],
        ]);
        if ($validator->fails()) {
            Session::flash('message', 'Please fill in the required fields!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->refresh();
        }
        try {
            unset($request['_token']);

            if (Discrepancy::where('id', $id)->whereNull('deleted_by')->count('id') == 0) {
                Session::flash('message', 'Oops! Discrepancy not found!');
                Session::flash('class', 'warning');
                Session::flash('display', 'block');
                return redirect()->route("discrepancies");
            }

            if (isset($request->document)) {
                $image_name = 'discrepancy_' . str_random(4) . '_' . microtime();
                Storage::disk('uploads')->makeDirectory('files/documents');
                $cover = $request->file('document');
                $extension = $cover->getClientOriginalExtension();
                Storage::disk('uploads')->put('files/documents/'. $image_name.'.'.$extension,  File::get($cover));
                $image_address = '/uploads/files/documents/' . $image_name.'.'.$extension;
                $request['doc'] = $image_address;
            }

            unset($request['document']);
            $request = Input::except('document');

            Discrepancy::where('id', $id)->whereNull('deleted_by')->update($request);

            Session::flash('message', 'Discrepancy successfully updated!');
            Session::flash('class', 'success');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function delete_discrepancy_document(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['case' => 'warning', 'title' => 'Warning!', 'content' => 'Id not found!']);
        }
        try {
            Discrepancy::where(['id'=>$request->id])->update(['doc'=>null]);

            return response(['case' => 'success', 'title' => 'Success!', 'content' => 'Successful!', 'id'=>$request->id]);
        } catch (\Exception $e) {
            return response(['case' => 'error', 'title' => 'Error!', 'content' => 'An error occurred!']);
        }
    }
}
