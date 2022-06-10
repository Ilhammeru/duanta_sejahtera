<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Province;
use App\Models\Service;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Customer';
        return view('master.customers.index', compact('pageTitle'));
    }
    
    /**
     * Function to display data for DataTables
     *
     * @return Yajra\Datatables\Facades\DataTables
     */
    public function json() {
        $data = Customer::all();

        return DataTables::of($data)
            ->addColumn('action', function($data) {
                return '<span onclick="edit('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" onclick="deleteCustomer('. $data->id .')"><i class="fas fa-trash"></i></span>';
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
        $pageTitle = 'Tambah Customer';
        $provinces = Province::all();
        $services = Service::all();
        return view('master.customers.create', compact('pageTitle', 'provinces', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
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

    public function getFormService($count) {
        $services = Service::all();
        $view = view('master.customers.service-form', compact('count', 'services'))->render();

        return sendResponse(['html' => $view], 'SUCCESS', 201);
    }
}
