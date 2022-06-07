<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class DivisionController extends Controller
{
    public function index() {
        $pageTitle = 'Divisi';
        $user = User::with('userRole.role')->find(Auth::user()->id);
        return view('master.division.index', compact('pageTitle', 'user'));
    }

    public function json() {
        $data = Division::orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->editColumn('name', function($data) {
                return ucwords($data->name);
            })
            ->addColumn('action', function ($data) {
                return '<span onclick="edit('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" onclick="deleteDivision('. $data->id .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request) {
        $data = [
            'name' => strtolower($request->name)
        ];

        try {
            $division = Division::updateOrCreate($data);
            return sendResponse($division, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['data' => ['error' => $th->getMessage()]], 'FAILED', 500);
        }
    }

    public function update(Request $request, $id) {
        $name = $request->name;
        try {
            $division = Division::updateOrCreate(
                ['id' => $id],
                ['name' => strtolower($name)]
            );
            return sendResponse($division, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['data' => ['error' => $th->getMessage()]], 'FAILED', 500);
        }
    }

    public function detail($id) {
        $data = Division::find($id);
        return sendResponse(['name' => $data->name], 'SUCCESS', 201);
    }

    public function destroy($id) {
        try {
            $user = User::select('id')->where('division_id', $id)->first();
            if (!$user) {
                $delete = Division::where('id', $id)->delete();

                return sendResponse($delete, 'SUCCESS', '201');
            }
            return sendResponse(['error' => 'Masih ada user yang menggunakan divisi ini'], 'FOREIGN_FAILED', 201);
        } catch (\Throwable $th) {
            //throw $th;
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }
}
