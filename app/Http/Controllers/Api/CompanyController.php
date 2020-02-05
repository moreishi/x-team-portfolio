<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;
use App\Job;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public $company;

    public $job;

    /**
     * CompanyController constructor.
     * @param Company $company
     * @param Job $job
     */
    public function __construct(Company $company, Job $job)
    {
        $this->company = $company;

        $this->job = $job;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        if(!empty(request()->only('role')))
        {

        } else {
            $companies = $this->company->paginate(10);
        }

        return response()->json([
            'status' => 'Ok',
            'result' => compact('companies')
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
            'name' => 'required|unique:companies',
            'email' => 'required',
            'business_phone' => 'required',
            'mobile' => 'required',
            'contact_person' => 'required',
            'url' => 'required'
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Failed',
            'result' => ['error' => $validate->errors()]
        ], 200);

        $company = $this->company->create([
            'name' => $request->name,
            'email' => $request->email,
            'business_phone' =>$request->business_phone,
            'mobile' => $request->mobile,
            'contact_person' => $request->contact_person,
            'url' => $request->url
        ]);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('company')
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
        $company = $this->company->find($id);

        if(is_null($company)) return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => 'Invalid company id.']
        ], 422);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('company')
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
        $company = $this->company->find($id);

        if(is_null($company)) return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => 'Invalid company id.']
        ], 422);

        $company->update([
            'name' => $request->name,
            'email' => $request->email,
            'business_phone' =>$request->business_phone,
            'mobile' => $request->mobile,
            'contact_person' => $request->contact_person,
            'url' => $request->url
        ]);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('company')
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
        if($this->company->destroy($id))
        {
            return response()->json([
                'status' => 'Ok',
                'result' => [
                    'message' => "Company with id $id has been deleted."
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => "Unable to delete company."
            ]
        ], 422);
    }

    /**
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobs($companyId)
    {
        $company = $this->job->with('company')->whereHas('company', function($q) use ($companyId) {
            return $q->where('id',$companyId);
        })->paginate(10);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('company')
        ], 200);
    }
}
