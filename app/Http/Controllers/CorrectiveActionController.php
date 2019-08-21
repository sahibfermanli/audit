<?php

namespace App\Http\Controllers;

use App\Discrepancy;
use App\DiscrepancyDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CorrectiveActionController extends HomeController
{
    public function get_corrective_actions($discrepancy_id) {
        if (!isset($discrepancy_id) || empty($discrepancy_id) || is_int($discrepancy_id)) {
            Session::flash('message', 'Oops! Discrepancy not found!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        }

        try {
            $discrepancy = Discrepancy::leftJoin('proc_list as p', 'disc_records.proc_id', '=', 'p.id')
                ->leftJoin('department_list as dep', 'disc_records.dep_id', '=', 'dep.id')
                ->leftJoin('source_list as s', 'disc_records.source_id', '=', 's.id')
                ->whereNull('disc_records.deleted_by')
                ->where('disc_records.id', $discrepancy_id)
                ->select(
                    'disc_records.id',
                    'disc_records.item_date',
                    'disc_records.flt_number',
                    'disc_records.item_desc_short',
                    'disc_records.need_kd',
                    'disc_records.status',
                    'p.proc_desc',
                    'p.proc_code',
                    'dep.department_desc',
                    'dep.dep_code',
                    's.source_desc',
                    's.source_code'
                )
                ->first();


            $correctives = DiscrepancyDetails::whereNull('deleted_by')
                ->where('master_id', $discrepancy_id)
                ->orderBy('id')
                ->select(
                    'id',
                    'master_id',
                    'reg_date',
                    'iso_number',
                    'corr_desc_short',
                    'kd_status',
                    'kd_person',
                    'kd_close_plan',
                    'kd_close_fact',
                    'need_plan',
                    'auditor',
                    'audit_to',
                    'status',
                    'doc'
                )
                ->get();

            return view("backend.correctives", compact(
                'discrepancy',
                'correctives'
            ));
        } catch (\Exception $exception) {
            return view('backend.error');
        }
    }

    public function get_add_corrective($discrepancy_id) {
        try {
            $discrepancy = Discrepancy::where('id', $discrepancy_id)->select('id', 'item_desc')->first();

            if ($discrepancy) {
                return view("backend.corrective_add", compact('discrepancy'));
            } else {
                Session::flash('message', 'Oops! Discrepancy not found...');
                Session::flash('class', 'warning');
                Session::flash('display', 'block');
                return redirect(route("corrective_action_list"));
            }
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function post_add_corrective(Request $request, $discrepancy_id) {
        $validator = Validator::make($request->all(), [
            'reg_date' => ['required', 'date'],
            'iso_number' => ['nullable', 'string', 'max:50'],
            'auditor' => ['nullable', 'string', 'max:255'],
            'audit_to' => ['nullable', 'string', 'max:255'],
            'inc_reason' => ['nullable', 'string'],
            'corr_desc_short' => ['required', 'string'],
            'corr_desc' => ['required', 'string'],
            'kd_person' => ['required', 'string', 'max:255'],
            'need_plan' => ['required', 'integer'],
            'kd_status' => ['required', 'integer'],
            'kd_close_plan' => ['nullable', 'date'],
            'kd_close_fact' => ['nullable', 'date'],
            'effect_desc' => ['nullable', 'string'],
            'eff_person' => ['nullable', 'string'],
            'eff_status' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'eff_close_date' => ['nullable', 'date'],
            'document' => ['nullable', 'mimes:doc,docx,xls,xlsx,pdf,txt'],
        ]);
        if ($validator->fails()) {
            Session::flash('message', 'Please fill in the required fields!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->refresh();
        }
        try {
            $request->merge(['created_by'=>Auth::id(), 'master_id'=>$discrepancy_id]);

            if (isset($request->document)) {
                $image_name = 'corrective_' . str_random(4) . '_' . microtime();
                Storage::disk('uploads')->makeDirectory('files/correctives');
                $cover = $request->file('document');
                $extension = $cover->getClientOriginalExtension();
                Storage::disk('uploads')->put('files/correctives/'. $image_name.'.'.$extension,  File::get($cover));
                $image_address = '/uploads/files/correctives/' . $image_name.'.'.$extension;
                $request['doc'] = $image_address;
            }

            unset($request['document']);
            $request = Input::except('document');

            DiscrepancyDetails::create($request);

            Session::flash('message', 'Corrective successfully added!');
            Session::flash('class', 'success');
            Session::flash('display', 'block');
            return redirect()->route("corrective_action_list", $discrepancy_id);
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function delete_corrective(Request $request, $discrepancy_id) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['case' => 'warning', 'title' => 'Warning!', 'content' => 'Id not found!']);
        }
        try {
            DiscrepancyDetails::where(['id'=>$request->id, 'master_id'=>$discrepancy_id])->update(['deleted_at'=>Carbon::now(), 'deleted_by'=>Auth::id()]);

            return response(['case' => 'success', 'title' => 'Success!', 'content' => 'Successful!', 'id'=>$request->id]);
        } catch (\Exception $e) {
            return response(['case' => 'error', 'title' => 'Error!', 'content' => 'An error occurred!']);
        }
    }

    public function get_update_corrective($discrepancy_id, $id) {
        if (!isset($id) || empty($id) || is_int($id)) {
            Session::flash('message', 'Oops! ID not found!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        }

        try {
            $discrepancy = Discrepancy::where('id', $discrepancy_id)->select('id', 'item_desc')->first();

            if (!$discrepancy) {
                Session::flash('message', 'Oops! Discrepancy not found...');
                Session::flash('class', 'warning');
                Session::flash('display', 'block');
                return redirect(route("corrective_action_list"));
            }

            $corrective = DiscrepancyDetails::where('id', $id)->whereNull('deleted_by')->select(
                'id',
                'doc',
                'reg_date',
                'iso_number',
                'auditor',
                'audit_to',
                'inc_reason',
                'corr_desc_short',
                'corr_desc',
                'kd_person',
                'need_plan',
                'kd_status',
                'kd_close_plan',
                'kd_close_fact',
                'effect_desc',
                'eff_person',
                'eff_status',
                'status',
                'eff_close_date'
            )->first();

            if (!$corrective) {
                Session::flash('message', 'Oops! Corrective not found!');
                Session::flash('class', 'warning');
                Session::flash('display', 'block');
                return redirect()->route("corrective_action_list");
            }

            return view("backend.corrective_update", compact(
                'corrective',
                'discrepancy'
            ));
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function post_update_corrective(Request $request, $discrepancy_id, $id) {
        if (!isset($id) || empty($id) || is_int($id)) {
            Session::flash('message', 'Oops! ID not found!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->route("discrepancies");
        }

        $validator = Validator::make($request->all(), [
            'reg_date' => ['required', 'date'],
            'iso_number' => ['nullable', 'string', 'max:50'],
            'auditor' => ['nullable', 'string', 'max:255'],
            'audit_to' => ['nullable', 'string', 'max:255'],
            'inc_reason' => ['nullable', 'string'],
            'corr_desc_short' => ['required', 'string'],
            'corr_desc' => ['required', 'string'],
            'kd_person' => ['required', 'string', 'max:255'],
            'need_plan' => ['required', 'integer'],
            'kd_status' => ['required', 'integer'],
            'kd_close_plan' => ['nullable', 'date'],
            'kd_close_fact' => ['nullable', 'date'],
            'effect_desc' => ['nullable', 'string'],
            'eff_person' => ['nullable', 'string'],
            'eff_status' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'eff_close_date' => ['nullable', 'date'],
            'document' => ['nullable', 'mimes:doc,docx,xls,xlsx,pdf,txt'],
        ]);
        if ($validator->fails()) {
            Session::flash('message', 'Please fill in the required fields!');
            Session::flash('class', 'warning');
            Session::flash('display', 'block');
            return redirect()->refresh();
        }
        try {
            if (isset($request->document)) {
                $image_name = 'corrective_' . str_random(4) . '_' . microtime();
                Storage::disk('uploads')->makeDirectory('files/correctives');
                $cover = $request->file('document');
                $extension = $cover->getClientOriginalExtension();
                Storage::disk('uploads')->put('files/correctives/'. $image_name.'.'.$extension,  File::get($cover));
                $image_address = '/uploads/files/correctives/' . $image_name.'.'.$extension;
                $request['doc'] = $image_address;
            }

            unset($request['document'], $request['_token']);
            $request = Input::except('document');

            DiscrepancyDetails::where(['id'=>$id, 'master_id'=>$discrepancy_id])->whereNull('deleted_by')->update($request);

            Session::flash('message', 'Corrective successfully updated!');
            Session::flash('class', 'success');
            Session::flash('display', 'block');
            return redirect()->route("corrective_action_list", $discrepancy_id);
        } catch (\Exception $exception) {
            return view("backend.error");
        }
    }

    public function delete_corrective_document(Request $request, $discrepancy_id) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return response(['case' => 'warning', 'title' => 'Warning!', 'content' => 'Id not found!']);
        }
        try {
            DiscrepancyDetails::where(['id'=>$request->id, 'master_id'=>$discrepancy_id])->update(['doc'=>null]);

            return response(['case' => 'success', 'title' => 'Success!', 'content' => 'Successful!', 'id'=>$request->id]);
        } catch (\Exception $e) {
            return response(['case' => 'error', 'title' => 'Error!', 'content' => 'An error occurred!']);
        }
    }
}
