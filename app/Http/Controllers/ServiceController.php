<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = "Layanan / Produk";
        return view('master.services.index', compact('pageTitle'));
    }

    public function json() {
        $data = Service::all();

        return DataTables::of($data)
            ->addColumn('action', function($data) {
                return '<a href="'. route('services.show', $data->id) .'" class="text-info me-4" style="cursor: pointer;"><i class="fas fa-edit"></i></a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageTitle = 'Tambah Layanan';
        return view('master.services.create', compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errMessage = [
            'name.required' => 'Nama layanan harus diisi',
            'price.required' => 'Harga layanan harus diisi'
        ];
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ], $errMessage);

        if ($validate->fails()) {
            $error = $validate->errors()->all();
            return sendResponse(['error' => $error], 'VALIDATION_FAILED', 500);
        }

        $name = strtolower($request->name);
        $price = $request->price;
        $payload = [
            'name' => $name,
            'price' => $price,
        ];

        try {
            $service = Service::updateOrCreate(
                $payload,
                ['created_at' => Carbon::now()]
            );

            return sendResponse($service, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
        $pageTitle = 'Detail Layanan';
        return view('master.services.create', compact('service', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $errMessage = [
            'name.required' => 'Nama layanan harus diisi',
            'price.required' => 'Harga layanan harus diisi'
        ];
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'price' => 'required'
        ], $errMessage);

        if ($validate->fails()) {
            $error = $validate->errors()->all();
            return sendResponse(['error' => $error], 'VALIDATION_FAILED', 500);
        }

        $name = strtolower($request->name);
        $price = $request->price;
        $payload = [
            'id' => $id
        ];

        try {
            $service = Service::updateOrCreate(
                $payload,
                [
                    'name' => $name,
                    'price' => $price,
                    'updated_at' => Carbon::now()
                ]
            );

            return sendResponse($service, 'SUCCESS', 201);
        } catch (\Throwable $th) {
            return sendResponse(['error' => $th->getMessage()], 'FAILED', 500);
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
        //
    }
}
