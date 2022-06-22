<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "User";
        return view('master.user.index', compact('pageTitle'));
    }

    public function json() {
        $data = User::with('division')->where('status', 1)->orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return "<a href='". route('user.show', $data->id) ."'>". ucwords($data->name) ."</a>";
            })
            ->addColumn('division', function($data) {
                $division = ucwords($data->division->name) ?? "-";
                return $division;
            })
            ->addColumn('work_time', function($data) {
                $start = date_create(date('Y-m-d', strtotime($data->date_in)));
                $end = date_create(date('Y-m-d'));
                $diff = date_diff($start, $end);
                $format = $diff->format("%y Tahun %m bulan %d hari");
                return $format ?? '-';
            })
            ->addColumn('action', function ($data) {
                return '<a class="text-info me-4" href="'. route('user.show', $data->id) .'"><i class="fas fa-edit"></i></a>
                <span class="text-info" onclick="deleteUser('. $data->id .')" id="deleteUser"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'division', 'work_time', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = "Tambah user";
        $division = Division::all();
        $role = Role::all();
        return view('master.user.create', compact('pageTitle', 'division', 'role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // get param for validate
        $ruleMessage = $this->getMessageRule();
        $rules = $this->getRule();

        $validate = Validator::make($request->all(), $rules, $ruleMessage);
        if ($validate->fails()) {
            $error = $validate->errors()->all();
            return sendResponse(['error' => $error], 'VALIDATION_FAILED', 500);
        }
        $service = new UserService();
        $save = $service->saveUser($request);

        return sendResponse($save['data'], $save['message'], $save['status']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with(['division', 'userRole.role'])->find($id);
        $division = Division::all();
        $role = Role::all();
        $pageTitle = "Detail User";
        return view('master.user.create', compact('user', 'division', 'role', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $service = new UserService();
        $currentUser = $service->getUserById($id);

        if ($currentUser) {
            if ($currentUser->email != $request->email) {
                $rules['email'] = 'unique:users,email';
            }
        }

        // get param for validate
        $ruleMessage = $this->getMessageRule();
        $rules = $this->getRule();
        unset($rules['password']);

        $validate = Validator::make($request->all(), $rules, $ruleMessage);
        if ($validate->fails()) {
            $error = $validate->errors()->all();
            return sendResponse(['error' => $error], 'VALIDATION_FAILED', 500);
        }

        $save = $service->saveUser($request, $id);

        return sendResponse($save['data'], $save['message'], $save['status']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = new UserService();
        $delete = $service->delete($id);

        return sendResponse($delete['data'], $delete['message'], $delete['status']);
    }

    public function getRule() {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'nik' => 'required',
            'birth_of_date' => 'required',
            'phone' => 'required',
            'division' => 'required',
            'date_in' => 'required',
            'role' => 'required',
            'password' => 'required'
        ];

        return $rules;
    }

    public function getMessageRule() {
        $ruleMessage = [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email ini sudah terdaftar di database',
            'nik.required' => 'Nomor KTP harus diisi',
            'birth_of_date.required' => 'Tanggal lahir harus diisi',
            'phone.required' => 'No Telfon harus diisi',
            'division.required' => 'Divisi harus diisi',
            'date_in.required' => 'Tanggal masuk harus diisi',
            'role.required' => 'Role harus diisi',
            'password.required' => 'Password harus diisi',
        ];

        return $ruleMessage;
    }

    public function deletePhoto($id) {
        try {
            $user = User::find($id);
            $currentPhoto = $user->photo;
            $user->photo = NULL;
            if ($user->save()) {
                File::delete($currentPhoto);
            }

            return sendResponse(['user' => $user, 'current' => $currentPhoto]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
