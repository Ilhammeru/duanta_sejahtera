<?php

namespace App\Http\Controllers;

use App\Models\BookingIn;
use App\Models\BookingInContainer;
use App\Models\ContainerSizeType;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Service;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class BookingInController extends Controller
{
    private $rules;
    private $messageRules;

    public function __construct()
    {
        $this->rules = [
            'booking_time' => 'required',
            'customer_id' => 'required',
            'volume' => 'required',
            'service_id' => 'required',
            'custom_size' => 'required_with:is_custom_size'
        ];
        $this->messageRules = [
            'booking_time.required' => 'Waktu Booking Harus Diisi',
            'customer_id.required' => 'Customer Harus Diisi',
            'volume.required' => 'Volum Kontainer Harus Diisi',
            'booked_by.required' => 'Marketing Harus Diisi',
            'service_id.required' => 'Layanan Harus Diisi',
            'custom_size.required_with' => 'Detail Ukuran Custom Harus Diisi Jika Anda Memilih Ukuran Custom'
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Booking In';
        return view('transactions.booking-in.index', compact(
            'pageTitle'
        ));
    }

    /**
     * Display data for DataTables
     * 
     * @return DataTables
     */
    public function json() {
        $data = BookingIn::with(['customer', 'containers', 'bookedBy', 'service'])
            ->where('is_complete', 0)
            ->get();
        return DataTables::of($data)
            ->editColumn('customer_id', function($data) {
                return ucwords($data->customer->name);
            })
            ->editColumn('booking_time', function($data) {
                return date('d F Y', strtotime($data->booking_time));
            })
            ->editColumn('booked_by', function($data){
                return ucwords($data->bookedBy->name);
            })
            ->editColumn('service_id', function($data){
                return ucwords($data->service->name);
            })
            ->editColumn('billing_type_id', function($data) {
                return $data->billing_type_id == 1 ? 'CASH' : 'TEMPO';
            })
            ->addColumn('containers', function($data) {
                return '<span style="cursor:pointer; color: #006dab;" onclick="detailContainer('. $data->id .')">'. count($data->containers) .' <i class="fas fa-link ms-3" style=" color: #006dab;"></i></span>';
            })
            ->addColumn('action', function($data) {
                return '<a class="me-2" href="'. route('booking-in.edit', $data->id) .'" style="cursor:pointer;"><i class="fas fa-edit"></i></a>
                    <span class="me-2" style="cursor:pointer;" onclick="deleteBooking('. $data->id .')"><i class="fas fa-trash"></i></span>
                    <span style="cursor:pointer;" onclick="printBooking('. $data->id .')"><i class="fas fa-print"></i></span>';
            })
            ->rawColumns(['customer_id', 'booking_time', 'booked_by', 'service_id', 'billing_type_id', 'containers', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = getRole();
        $marketing = [];
        if ($role == 'admin') {
            $marketingRoleRaw = Role::where('name', 'like', '%marketing%')
                ->first();
            $marketingRoleId = $marketingRoleRaw ? $marketingRoleRaw->id : '';
            $marketing = UserRole::with(['user'])->where('role_id', $marketingRoleId)->get();
        }
        $pageTitle = 'Tambah Booking In';
        $containersType = ContainerSizeType::all();
        $customers = Customer::where('deleted_at', '=', null)->get();
        $containerSize = ContainerSizeType::all();
        $service = Service::all();
        $cargoGoods = [
            ['id' => 'FULL', 'name' => 'FULL'],
            ['id' => 'EMPTY', 'name' => 'EMPTY'],
            ['id' => 'SPECIAL CARGO', 'name' => 'SPECIAL CARGO'],
        ];
        return view('transactions.booking-in.create', compact(
            'pageTitle',
            'containersType',
            'customers',
            'containerSize',
            'cargoGoods',
            'role',
            'marketing',
            'service'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = getRole();
        // begin::validation
        if ($role == 'admin') {
            $this->rules['booked_by'] = 'required';
        }
        $validation = Validator::make(
            $request->all(),
            $this->rules,
            $this->messageRules
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
            // variable;
            $bookingTime = $request->booking_time;
            $doReference = $request->do_reference;
            $customerId = $request->customer_id;
            $containerSize = $request->container_size;
            $isCustomSize = $request->is_custom_size;
            $customSize = $request->custom_size;
            $cargoGoods = $request->cargo_goods;
            $volume = $request->volume;
            $notes = $request->notes;
            $bookedBy = $request->booked_by;
            $billingType = $request->billing_type;
            $acceptBy = $request->accept_by;
            $transportCompany = $request->transport_company;
            $transportPlateNumber = $request->transport_plate_number;
            $serviceId = $request->service_id;
            $containerNumbers = $request->container_numbers;
            $containerSeals = $request->container_seals;
            $bookingCode = generateBookingCode(BookingIn::count(), $customerId, 'IN');

            $customerDetail = Customer::find($customerId);
            $customerName = $customerDetail->name;
            $noSpaceCustomerName = implode('', explode(' ', $customerName));
            $qr = QrCode::generate($bookingCode . '/booking-in/' . $noSpaceCustomerName . '.svg');

            $data = [
                'booking_code' => $bookingCode,
                'booking_time' => $bookingTime,
                'do_reference' => $doReference,
                'customer_id' => $customerId,
                'container_size_type_id' => $containerSize,
                'cargo_goods' => $cargoGoods,
                'volume' => $volume,
                'notes' => $notes,
                'booked_by' => $bookedBy,
                'transport_company' => $transportCompany,
                'transport_plate_number' => $transportPlateNumber,
                'service_id' => $serviceId,
                'billing_type_id' => $billingType,
                'barcode_path' => 'booking-in/' . $noSpaceCustomerName . '.svg'
            ];
            
            if ($isCustomSize) {
                $data['is_custom_container_size'] = $isCustomSize;
                $data['custom_container_size'] = $customSize;
            }
            $bookingInId = BookingIn::insertGetId($data);

            $dataContainer = [];
            for ($a = 0; $a < count($containerNumbers); $a++) {
                $dataContainer[] = [
                    'booking_id' => $bookingInId,
                    'container_number' => $containerNumbers[$a],
                    'container_seal' => $containerSeals[$a]
                ];
            }
            BookingInContainer::insert($dataContainer);
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
     * Display the specified resource.
     *
     * @param  \App\Models\BookingIn  $bookingIn
     * @return \Illuminate\Http\Response
     */
    public function show(BookingIn $bookingIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookingIn  $bookingIn
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = getRole();
        $marketing = [];
        if ($role == 'admin') {
            $marketingRoleRaw = Role::where('name', 'like', '%marketing%')
                ->first();
            $marketingRoleId = $marketingRoleRaw ? $marketingRoleRaw->id : '';
            $marketing = UserRole::with(['user'])->where('role_id', $marketingRoleId)->get();
        }
        $pageTitle = 'Tambah Booking In';
        $containersType = ContainerSizeType::all();
        $customers = Customer::where('deleted_at', '=', null)->get();
        $containerSize = ContainerSizeType::all();
        $service = Service::all();
        $cargoGoods = [
            ['id' => 'FULL', 'name' => 'FULL'],
            ['id' => 'EMPTY', 'name' => 'EMPTY'],
            ['id' => 'SPECIAL CARGO', 'name' => 'SPECIAL CARGO'],
        ];
        $bookingIn = BookingIn::with(['containers'])->find($id);
        return view('transactions.booking-in.edit', compact(
            'pageTitle',
            'containersType',
            'customers',
            'containerSize',
            'cargoGoods',
            'role',
            'marketing',
            'service',
            'bookingIn'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // begin::validation
        $validation = Validator::make(
            $request->all(),
            $this->rules,
            $this->messageRules
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
            // variable;
            $bookingCode = $request->booking_code;
            $bookingTime = $request->booking_time;
            $doReference = $request->do_reference;
            $customerId = $request->customer_id;
            $containerSize = $request->container_size;
            $isCustomSize = $containerSize == 'custom' ? true : false;
            $customSize = $request->custom_size;
            $cargoGoods = $request->cargo_goods;
            $volume = $request->volume;
            $notes = $request->notes;
            $bookedBy = $request->booked_by;
            $billingType = $request->billing_type;
            $acceptBy = $request->accept_by;
            $transportCompany = $request->transport_company;
            $transportPlateNumber = $request->transport_plate_number;
            $serviceId = $request->service_id;
            $containerNumbers = $request->container_numbers;
            $containerSeals = $request->container_seals;

            $data = [
                'booking_time' => $bookingTime,
                'do_reference' => $doReference,
                'customer_id' => $customerId,
                'container_size_type_id' => $containerSize,
                'cargo_goods' => $cargoGoods,
                'volume' => $volume,
                'notes' => $notes,
                'booked_by' => $bookedBy,
                'transport_company' => $transportCompany,
                'transport_plate_number' => $transportPlateNumber,
                'service_id' => $serviceId,
                'billing_type_id' => $billingType,
                'is_customer_container_size' => NULL,
                'custom_container_size' => NULL,
                'updated_at' => Carbon::now()
            ];
            
            if ($isCustomSize) {
                $data['is_customer_container_size'] = $isCustomSize;
                $data['custom_container_size'] = $customSize;
                $data['container_size_type_id'] = NULL;
            }

            BookingIn::where('id', $id)->update($data);

            $dataContainer = [];
            for ($a = 0; $a < count($containerNumbers); $a++) {
                $dataContainer[] = [
                    'booking_id' => $id,
                    'container_number' => $containerNumbers[$a],
                    'container_seal' => $containerSeals[$a]
                ];
            }
            BookingInContainer::where('booking_id', $id)->delete();
            BookingInContainer::insert($dataContainer);
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
     * @param  \App\Models\BookingIn  $bookingIn
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            BookingInContainer::where('booking_id', $id)->delete();
            BookingIn::where('id', $id)->delete();
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

    public function detailContainer($id)
    {
        try {
            $bookingIn = BookingIn::with(['containers'])->find($id);
            $containers = $bookingIn->containers;
            $view = view('transactions.booking-in._detail-container', compact('containers'))->render();
    
            return sendResponse(['view' => $view]);
        } catch (\Throwable $th) {
            return sendResponse(
                ['error' => $th->getMessage()],
                'FAILED',
                500
            );
        }
    }

    public function printContainerView($id)
    {
        $bookingIn = BookingIn::select('id', 'booking_code', 'customer_id')
            ->with(['customer'])
            ->where('id', $id)
            ->first();
        $view = view('transactions.booking-in._print-view', compact('bookingIn'))->render();

        return sendResponse(['view' => $view]);
    }
}
