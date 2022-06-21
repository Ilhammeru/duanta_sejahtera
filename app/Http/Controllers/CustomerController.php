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
use Illuminate\Support\Facades\File;

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
                return '<a href="'. route('customers.edit', $data->id) .'" class="text-info me-4"><i class="fas fa-edit"></i></a>
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
        $customerType = $request->customer_type;
        $nameNoSpace = implode('', explode(' ', $name));

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
                'type' => $customerType,
                'created_at' => Carbon::now()
            ];

            $customerId = Customer::insertGetId($customerData);

            $serviceData = [];
            for ($a = 0; $a < count($services); $a++) {
                $serviceData[] = [
                    'customer_id' => $customerId,
                    'service_id' => $services[$a],
                    'billing_type_id' => $billings[$a],
                    'created_at' => Carbon::now()
                ];
            }
            CustomerServices::insert($serviceData);

            $contractData = [
                'customer_id' => $customerId,
                'contract_period_in_day' => $contractPeriod,
                'is_auto_renewal' => $contractRenewal,
                'start_date' => date('Y-m-d', strtotime($contractDate)),
                'end_date' => date('Y-m-d', strtotime("+$contractPeriod days", strtotime($contractDate)))
            ];
            if ($request->has('aggreement_letter')) {
                $file = $request->file('aggreement_letter');
                $filename = $nameNoSpace . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('customer/contract/' . $nameNoSpace, $filename, 'public');
                if ($path) {
                    $contractData['aggreement_letter_img'] = $path;
                }
            }
            CustomerContract::insert($contractData);

            DB::commit();
            return sendResponse([
                'customer' => $customerData,
                'service' => $serviceData,
                'contract' => $contractData,
                'file' => $path ?? ''
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

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
     * Get data and render view for customer's service
     * 
     * @return \Illuminate\Contract\Renderable
     */
    public function changeService($id) {
        $customer = Customer::with(['services'])->find($id);
        $service = Service::all();
        $view = view('master.customers._service-form', compact('customer', 'service'))->render();
        return sendResponse(['view' => $view]);
    }

    /**
     * Get data and render view for customer's contract
     * 
     * @return \Illuminate\Contract\Renderable
     */
    public function changeContract($id) {
        $customer = Customer::with(['contract'])->find($id);
        $view = view('master.customers._contract-form', compact('customer'))->render();
        $aggrementImg = $customer->contract->aggreement_letter_img != NULL ? asset($customer->contract->aggreement_letter_img) : '';
        return sendResponse(['view' => $view, 'aggrementImg' => $aggrementImg]);
    }

    /**
     * Function to store edited service data
     * 
     * @return \Illuminate\Http\Response
     */
    public function storeService(Request $request, $id) {
        $services = $request->service_id;
        $billings = $request->billing_type_id;
        
        // begin::validation
        if (count($services) != count($billings)) {
            return sendResponse(
                ['error' => 'Pastikan Semua Layanan atau Jenis Pembayaran Terisi Semua'],
                'VALIDATION_FAILED',
                500
            );
        }
        // end::validation

        DB::beginTransaction();
        try {
            // delete all data
            CustomerServices::where('customer_id', $id)->delete();
            $dataService = [];
            for ($a = 0; $a < count($services); $a++) {
                $dataService[] = [
                    'customer_id' => $id,
                    'service_id' => $services[$a],
                    'billing_type_id' => $billings[$a],
                    'created_at' => Carbon::now()
                ];
            }
            CustomerServices::insert($dataService);

            DB::commit();
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Function to store edited contract data
     * 
     * @return /Illuminate/Http/Response
     */
    public function storeContract(Request $request, $id) {
        $contractDate = $request->contract_date;
        $contractPeriod = $request->contract_period;
        $contractRenewal = $request->customer_renewal;
        $customerType = $request->customer_type;
        $contract = CustomerContract::where('customer_id', $id)->first();
        // begin::validation
        $rules = [
            'contract_date' => 'required',
            'contract_period' => 'required',
            'customer_renewal' => 'required',
            'customer_type' => 'required'
        ];
        $messageRules = [
            'contract_date.required' => 'Tanggal Mulai Kontrak Harus Diisi',
            'contract_period.required' => 'Lama Kontrak Harus Diisi',
            'customer_renewal.required' => 'Pembaruhan Kontrak Otomatis Harus Diisi',
            'customer_type.required' => 'Jenis Pelanggan Harus Diisi'
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
        // end::validation
        
        DB::beginTransaction();
        try {
            Customer::where('id', $id)->update(['type' => $customerType]);
            $contract->start_date = $contractDate;
            $contract->contract_period_in_day = $contractPeriod;
            $contract->is_auto_renewal = $contractRenewal;
            $contract->end_date = date('Y-m-d', strtotime("+$contractPeriod days", strtotime($contractDate)));
            $contract->save();

            DB::commit();
            return sendResponse($contract);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::with(['contract', 'services'])
            ->find($id);
        $pageTitle = 'Edit Data Pelanggan';
        $services = Service::all();
        $provinces = Province::all();
        $district = $customer->district;
        $regencies = Regency::where('province_id', $customer->province)->get();
        $districts = District::where('regency_id', $customer->city)->get();

        $format = [];
        foreach ($districts as $district) {
            $villages = Village::where('district_id', $district->id)->get();

            foreach ($villages as $village) {
                $format[] = [
                    'id' => $district->id . '/' . $village->id,
                    'name' => 'Kec. ' . $district->name . ' / ' . 'Kel. ' . $village->name
                ];
            }
        }
        return view('master.customers.edit', compact(
            'customer', 'pageTitle', 'provinces',
            'services', 'regencies', 'format'
        ));
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
        $type = $request->customer_type;
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
        $nameNoSpace = implode('', explode(' ', $name));
        $currentCustomer = Customer::with(['_province', 'regency', 'contract'])->find($id);

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

        DB::beginTransaction();

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
            $currentCustomer->type = $type;
            $currentCustomer->pic_phone = $picPhone;
            $currentCustomer->updated_at = Carbon::now();
            $currentCustomer->save();

            $serviceData = [];
            for ($a = 0; $a < count($services); $a++) {
                $serviceData[] = [
                    'customer_id' => $id,
                    'service_id' => $services[$a],
                    'billing_type_id' => $billings[$a],
                    'updated_at' => Carbon::now()
                ];
            }
            CustomerServices::where('customer_id', $id)->delete();
            CustomerServices::where('customer_id', $id)->insert($serviceData);

            $contractData = [
                'customer_id' => $id,
                'contract_period_in_day' => $contractPeriod,
                'is_auto_renewal' => $contractRenewal,
                'start_date' => date('Y-m-d', strtotime($contractDate)),
                'end_date' => date('Y-m-d', strtotime("+$contractPeriod days", strtotime($contractDate)))
            ];
            $currentImagePath = $currentCustomer->contract->aggreement_letter_img;
            if ($request->aggreement_letter != null && $request->aggreement_letter->getClientOriginalName() != 'blob') {
                $file = $request->aggreement_letter;
                $filename = $nameNoSpace . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('customer/contract/' . $nameNoSpace, $filename, 'public');
                if ($path) {
                    $contractData['aggreement_letter_img'] = $path;
                }
            }
            $saveContract = CustomerContract::where('customer_id', $id)->update($contractData);
            if (isset($path)) {
                if ($saveContract) {
                    File::delete($currentImagePath);
                }
            }

            DB::commit();
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();
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
        DB::beginTransaction();
        try {
            $pathToFile = CustomerContract::select('aggreement_letter_img')->where('customer_id', $id)
                ->first();
            CustomerContract::where('customer_id', $id)->delete();
            CustomerServices::where('customer_id', $id)->delete();
            Customer::where('id', $id)->delete();

            DB::commit();
            File::delete($pathToFile->aggreement_letter_img);
            return sendResponse([]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
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

    public function detailInit($id, $type) {
        try {
            $customer = Customer::with(['services', 'contract', '_province', 'regency'])
                ->find($id);
            $splitDistrict = explode('/', $customer->district);
            $district = District::select('name')->where('id', $splitDistrict[0])->first()->name;
            $village = Village::select('name')->where('id', $splitDistrict[1])->first()->name;
            $completeAddress = $customer->address . ", $village $district, " . $customer->regency->name . ' ' . $customer->_province->name;
            if ($type == 'all') {
                $viewService = view('master.customers._init-service', compact('customer'))->render();
                $viewPersonal = view('master.customers._init-personal', compact('customer', 'completeAddress'))->render();
                $viewContract = view('master.customers._init-contract', compact('customer', 'completeAddress'))->render();
                return sendResponse([
                    'personal' => $viewPersonal,
                    'service' => $viewService,
                    'contract' => $viewContract
                ]);
            } else {
                $view = view('master.customers.' . $type, compact('customer', 'completeAddress'))->render();
                return sendResponse(['view' => $view]);
            }
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function deleteContractPhoto($id) {
        try {
            $currentData = CustomerContract::where('customer_id', $id)->first();
            $currentFile = $currentData->aggreement_letter_img;
            $currentData->aggreement_letter_img = NULL;
            $delete = $currentData->save();
            if ($delete) {
                File::delete($currentFile);
            }
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
