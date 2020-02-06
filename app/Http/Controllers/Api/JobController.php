<?php

namespace App\Http\Controllers\Api;

use App\Company;
use App\Http\Controllers\Controller;
use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    public $company;

    public $job;

    /**
     * JobController constructor.
     * @param Company $company
     * @param Job $job
     */
    public function __construct(Company $company, Job $job)
    {
        $this->company = $company;

        $this->job = $job;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty(request()->only('role')))
        {

        } else {
            $jobs = $this->job->with('company')->paginate(10);
        }

        return response()->json([
            'status' => 'Ok',
            'result' => compact('jobs')
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'location' => 'required',
            'position' => 'required',
            'salary_range' => 'required',
            'details' => 'required',
            'company_id' => 'required',
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Failed',
            'result' => ['error' => $validate->errors()]
        ], 200);

        $company = $this->company->find($request->company_id);

        $job = $company->jobs()->create([
            'title' => $request->title,
            'location' => $request->location,
            'position' => $request->position,
            'salary_range' => $request->salary_range,
            'details' => $request->details
        ]);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('job')
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = $this->job->with('company')->find($id);

        if(is_null($job)) return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => 'Invalid job id.']
        ], 422);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('job')
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $job = $this->job->find($id);

        if(is_null($job)) return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => 'Invalid company id.']
        ], 422);

        $job->update([
            'title' => $request->title,
            'location' => $request->location,
            'position' => $request->position,
            'salary_range' => $request->salary_range,
            'details' => $request->details
        ]);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('job')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->job->destroy($id))
        {
            return response()->json([
                'status' => 'Ok',
                'result' => [
                    'message' => "Job with id $id has been deleted."
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => "Unable to delete job."
            ]
        ], 422);
    }
}
