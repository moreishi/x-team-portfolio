<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public $user;

    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            $users = User::whereHas('roles', function($q) {
                return $q->where('slug',request()->role);
            })->with(['roles'])->paginate(10);
            $users->appends(request()->query());
        } else {
            $users = $this->user->paginate(10);
        }

        return response()->json([
            'status' => 'Ok',
            'result' => compact('users')
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
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users'
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Failed',
            'result' => ['error' => $validate->errors()]
        ], 200);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make(Carbon::now()->timestamp)
        ]);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('user')
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
        $user = User::find($id);

        if(is_null($user)) return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => 'Invalid user id.']
        ], 422);

        return response()->json([
            'status' => 'Ok',
            'result' => compact('user')
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
        $user = $this->user->find($id);

        if(!$user)
        {
            return response()->json([
                'status' => 'Failed',
                'result' => [
                    'message' => 'Invalid course Id.']
            ], 422);
        }

        $validate = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'email' => 'required'
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Failed',
            'result' => ['error' => $validate->errors()]
        ], 422);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->save();

        return response()->json([
            'status' => 'Ok',
            'result' => compact('user')
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
        if($this->user->destroy($id))
        {
            return response()->json([
                'status' => 'Ok',
                'result' => [
                    'message' => "User with id $id has been deleted."
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'Failed',
            'result' => [
                'message' => "Unable to delete user."
            ]
        ], 422);
    }
}
