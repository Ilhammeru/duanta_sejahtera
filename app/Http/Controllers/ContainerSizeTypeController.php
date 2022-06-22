<?php

namespace App\Http\Controllers;

use App\Models\ContainerSizeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ContainerSizeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Container Size / Type';
        return view('master.container-size-type.index', compact('pageTitle'));
    }

    /**
     * Get data for dataTables
     * 
     * @return DataTables
     */
    public function json() {
        $data = ContainerSizeType::all();
        return DataTables::of($data)
            ->addColumn('name', function($data) {
                $size = $data->size;
                $type = $data->type;
                $name = $size . '  ' . $type;
                return $name; 
            })
            ->addColumn('action', function ($data) {
                return '<span onclick="editContainer('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" onclick="deleteContainer('. $data->id .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['name', 'action'])
            ->make(true);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $size = $request->size;
        $type = $request->type;

        // validation
        $rules = [
            'size' => 'required',
            'type' => 'required',
        ];
        $messageRules = [
            'size.required' => 'Ukuran Harus Diisi',
            'type.required' => 'Tipe Harus Diisi',
            'size.unique' => 'Ukuran Sudah Terdaftar di Database',
            'type.unique' => 'Tipe Sudah Terdaftar di Database',
        ];
        $validation = Validator::make(
            $request->all(),
            $rules,
            $messageRules
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        try {
            $data = [
                'size' => $size,
                'type' => $type
            ];
            ContainerSizeType::updateOrCreate(
                $data
            );
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContainerSizeType  $containerSizeType
     * @return \Illuminate\Http\Response
     */
    public function show(ContainerSizeType $containerSizeType)
    {
        return sendResponse($containerSizeType);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContainerSizeType  $containerSizeType
     * @return \Illuminate\Http\Response
     */
    public function edit(ContainerSizeType $containerSizeType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ContainerSizeType  $containerSizeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContainerSizeType $containerSizeType)
    {
        $size = $request->size;
        $type = $request->type;

        // validation
        $rules = [
            'size' => 'required',
            'type' => 'required',
        ];
        if ($containerSizeType->size != $size && $containerSizeType->type != $type) {
            $rules['size'] = 'unique:container_size_and_type,size';
            $rules['type'] = 'unique:container_size_and_type,type';
        }
        $messageRules = [
            'size.required' => 'Ukuran Harus Diisi',
            'type.required' => 'Tipe Harus Diisi',
            'size.unique' => 'Ukuran Sudah Terdaftar di Database',
            'type.unique' => 'Tipe Sudah Terdaftar di Database',
        ];
        $validation = Validator::make(
            $request->all(),
            $rules,
            $messageRules
        );
        if ($validation->fails()) {
            $error = $validation->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        try {
            $containerSizeType->size = $size;
            $containerSizeType->type = $type;
            $containerSizeType->save();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContainerSizeType  $containerSizeType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContainerSizeType $containerSizeType)
    {
        try {
            $containerSizeType->delete();
            return sendResponse([]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }
}
