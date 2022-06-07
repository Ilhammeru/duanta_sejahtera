<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Role';
        return view('master.role.index', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function json() {
        $data = Role::orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords($data->name);
            })
            ->addColumn('action', function ($data) {
                return '<span onclick="edit('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" onclick="deleteRole('. $data->id .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $slug = $this->setSlug($name);
        $data = [
            'name' => $request->name,
            'slug' => $slug
        ];

        try {
            $save = Role::updateOrCreate($data);
            return sendResponse($save, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['data' => ['error' => $th->getMessage()]], 'FAILED', 500);
        }
    }

    public function setSlug($name)
    {
        $split = explode(' ', $name);
        $slug = implode('-', $split);

        return strtolower($slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Role::find($id);
        return sendResponse(['name' => $data->name], 'SUCCESS', 201);
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
        $name = $request->name;
        $slug = $this->setSlug($name);

        try {
            $update = Role::updateOrCreate(
                ['id' => $id],
                ['name' => $name, 'slug' => $slug]
            );
            return sendResponse($update, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['data' => ['error' => $th->getMessage()]], 'FAILED', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $role = UserRole::select('id')->where('role_id', $id)->first();
            if (!$role) {
                $delete = Role::where('id', $id)->delete();

                return sendResponse($delete, 'SUCCESS', '201');
            }
            return sendResponse(['error' => 'Masih ada user yang menggunakan role ini'], 'FOREIGN_FAILED', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
}
