<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContract;
use App\Models\CustomerServices;
use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Service;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Hashids;

class CustomerController extends Controller
{
    private $formType;

    public function __construct() 
    {
        $this->formType = [
            'personal' => 'master.customers._personal-form',
            'service' => 'master.customers._service-form',
            'contract' => 'master.customers._contract-form'
        ];
    }

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
            ->editColumn('name', function($data) {
                $ids = Hashids::encode($data->id);
                return '<a href="'. route('customers.show', $ids) .'">'. ucwords($data->name) .'</a>';
            })
            ->addColumn('pic', function($data) {
                $phone = $data->pic_phone;
                $name = ucwords($data->pic_name);
                return $name . " ( $phone )";
            })
            ->addColumn('action', function($data) {
                return '<span onclick="edit('. $data->id .')" class="text-info me-4"><i class="fas fa-edit"></i></span>
                <span class="text-info" onclick="deleteCustomer('. $data->id .')"><i class="fas fa-trash"></i></span>';
            })
            ->rawColumns(['action', 'pic', 'name'])
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
        // variable
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $npwp = $request->npwp;
        $province = $request->province;
        $city = $request->city;
        $district = $request->district;
        $address = $request->address;
        $picName = $request->pic_name;
        $picPhone = $request->pic_phone;
        $services = $request->customer_service_id;
        $billings = $request->customer_billing_type_id;
        $contractDate = $request->contract_date;
        $contractPeriod = $request->contract_period;
        $contractRenewal = $request->customer_renewal;
        if ($request->hasFile('aggreement_letter')) {
            $file = $request->file('aggreement_letter');
        }

        // validation
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:customers,email',
            'phone' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required'
        ];
        $messageRules = [
            'name.required' => 'Nama Customer Harus Disii',
            'email.required' => 'Email Customer Harus Disii',
            'phone.required' => 'Telepon Customer Harus Disii',
            'province.required' => 'Provinsi Customer Harus Disii',
            'city.required' => 'Kota Customer Harus Disii',
            'district.required' => 'Kecamatan / Kelurahan Customer Harus Disii',
            'address.required' => 'Address Customer Harus Disii',
            'pic_name.required' => 'Nama PIC Customer Harus Disii',
            'pic_phone.required' => 'No Telpon PIC Customer Harus Disii',
        ];
        $validator = Validator::make($request->all(), $rules, $messageRules);
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        DB::beginTransaction();

        try {
            $customerData = [
                'name' => $name,
                'email' => $email,
                'address' => $address,
                'phone' => $phone,
                'province' => $province,
                'city' => $city,
                'district' => $district,
                'npwp' => $npwp,
                'pic_name' => $picName,
                'pic_phone' => $picPhone,
                'created_at' => Carbon::now()
            ];
            $customer = Customer::create($customerData);

            $customerService = [];
            for ($a = 0; $a < count($services); $a++) {
                $customerService[] = [
                    'customer_id' => $customer->id,
                    'service_id' => $services[$a],
                    'billing_type_id' => $billings[$a],
                    'created_at' => Carbon::now()
                ];
            }
            $custService = CustomerServices::create($customerService);

            // handle image
            if ($request->hasFile('aggreement_letter')) {
                $file = $request->file('aggreement_letter');
                $fileName = $file->getClientOriginalName();
                $folderName = implode('', str_split($name));
                $path = Storage::putFileAs('public/customer/contract', $file, $fileName);
                $pathToFile = asset('/storage/customer/contract/' . $fileName);
            }

            $contractData = [
                'customer_id' => $customer->id,
                'contract_period_in_day' => $contractPeriod,
                'is_auto_renewal' => $contractRenewal,
                'agreement_letter_img' => $pathToFile ?? NULL,
                'start_date' => $contractDate,
                'end_date' => date('Y-m-d', strtotime("+$contractPeriod days", strtotime($contractDate))),
                'created_at' => Carbon::now()
            ];
            CustomerContract::create($contractData);
        } catch (\Throwable $th) {
            DB::rollBack();

            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }

        DB::commit();

        return sendResponse(
            $customer,
            'SUCCESS',
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ids = Hashids::decode($id);
        $customer = Customer::with(['services.service', 'contract', '_province', 'regency'])
            ->where('id', $ids[0])
            ->first();
        $splitDistrict = explode('/', $customer->district);
        $district = District::select('name')->where('id', $splitDistrict[0])->first()->name;
        $village = Village::select('name')->where('id', $splitDistrict[1])->first()->name;
        $address = $customer->address;
        $completeAddress = $address . ", $village $district, " . $customer->regency->name . ' ' . $customer->_province->name;
        $pageTitle = 'Detail Customer';
        return view('master.customers.show', compact('pageTitle', 'customer', 'completeAddress', 'id'));
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
        // variable
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $npwp = $request->npwp;
        $province = $request->province;
        $city = $request->city;
        $district = $request->district;
        $address = $request->address;
        $picName = $request->pic_name;
        $picPhone = $request->pic_phone;
        $currentCustomer = Customer::with(['_province', 'regency'])->find($id);

        // validation
        $rules = [
            'name' => 'required',
            'phone' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
            'address' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required'
        ];
        if ($currentCustomer->email != $email) {
            $rules['email'] = 'unique:customers,email';
        }
        $messageRules = [
            'name.required' => 'Nama Customer Harus Disii',
            'email.required' => 'Email Customer Harus Disii',
            'phone.required' => 'Telepon Customer Harus Disii',
            'province.required' => ' ProvinsiCustomer Harus Disii',
            'city.required' => 'Kota Customer Harus Disii',
            'district.required' => 'Kecamatan / Kelurahan Customer Harus Disii',
            'address.required' => 'Address Customer Harus Disii',
            'pic_name.required' => 'Nama PIC Customer Harus Disii',
            'pic_phone.required' => 'No Telpon PIC Customer Harus Disii'
        ];
        $validator = Validator::make($request->all(), $rules, $messageRules);
        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return sendResponse(
                ['error' => $error],
                'VALIDATION_FAILED',
                500
            );
        }

        try {
            $currentCustomer->name = $name;
            $currentCustomer->email = $email;
            $currentCustomer->address = $address;
            $currentCustomer->phone = $phone;
            $currentCustomer->province = $province;
            $currentCustomer->city = $city;
            $currentCustomer->district = $district;
            $currentCustomer->npwp = $npwp;
            $currentCustomer->pic_name = $picName;
            $currentCustomer->pic_phone = $picPhone;
            $currentCustomer->updated_at = Carbon::now();
            $currentCustomer->save();
            $splitDistrict = explode('/', $district);
            $districts = District::select('name')->where('id', $splitDistrict[0])->first()->name;
            $village = Village::select('name')->where('id', $splitDistrict[1])->first()->name;
            $currentCustomer = Customer::with(['_province', 'regency'])->find($id);
            $completeAddress = $address . ", $village $districts, " . $currentCustomer->regency->name . ' ' . $currentCustomer->_province->name;
            return sendResponse(
                ['customer' => $currentCustomer, 'address' => $completeAddress]
            );
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

    public function showForm($type, $id) {
        $ids = Hashids::decode($id);
        $customer = Customer::find($ids[0]);
        $provinces = Province::all();
        $regencies = Regency::where('province_id', $customer->province)->get();
        $splitDistrict = explode('/', $customer->district);

        $districts = District::where('regency_id', $customer->city)->get();

        $formatDistrict = [];
        foreach ($districts as $district) {
            $villages = Village::where('district_id', $splitDistrict[0])->get();

            foreach ($villages as $village) {
                $formatDistrict[] = [
                    'id' => $district->id . '/' . $village->id,
                    'name' => 'Kec. ' . $district->name . ' / ' . 'Kel. ' . $village->name
                ];
            }
        }

        $view = view($this->formType[$type], compact(
            'customer', 'provinces', 'regencies', 'formatDistrict'
        ))->render();

        return sendResponse(
            ['view' => $view, 'format' => $formatDistrict],
            'SUCCESS',
            201
        );
    }
}
