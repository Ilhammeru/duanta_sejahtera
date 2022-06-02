<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DivisionController extends Controller
{
    public function index() {
        $pageTitle = 'Divisi';
        return view('master.division.index', compact('pageTitle'));
    }

    public function json() {
        $data = Division::orderBy('id', 'desc')->get();
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<span onclick="editDivision('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" id="deleteDivision"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request) {
        $data = [
            'name' => $request->name
        ];

        try {
            $division = Division::updateOrCreate($data);
            return sendResponse($division, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['data' => ['error' => $th->getMessage()]], 'FAILED', 500);
        }
    }

    public function update(Request $request, $id) {
        return sendResponse($id, 'SUCCESS', 201);
    }
}
