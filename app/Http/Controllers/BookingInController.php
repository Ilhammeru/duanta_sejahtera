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
            'containers.*.container_number' => 'required',
            'containers.*.container_seal' => 'required',
            'containers.*.volume' => 'required',
            'containers.*.cargo_goods' => 'required',
            'containers.*.container_size_type_id' => 'required',
        ];
        $this->messageRules = [
            'booking_time.required' => 'Waktu Booking Harus Diisi',
            'customer_id.required' => 'Customer Harus Diisi',
            'booked_by.required' => 'Marketing Harus Diisi',
            'containers.*.container_number.required' => 'Nomor Containers Harus Diisi',
            'containers.*.container_seal.required' => 'Seal Containers Harus Diisi',
            'containers.*.volume.required' => 'Volume Containers Harus Diisi',
            'containers.*.cargo_goods.required' => 'Cargo / Goods Harus Diisi',
            'containers.*.container_size_type_id.required' => 'Container Size / Type Harus Diisi',
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
                return '<a href="'. route('booking-in.show', $data->id) .'">'. ucwords($data->customer->name) .'</a>';
            })
            ->editColumn('booking_time', function($data) {
                return date('d F Y', strtotime($data->booking_time));
            })
            ->editColumn('booked_by', function($data){
                return ucwords($data->bookedBy->name);
            })
            ->addColumn('containers', function($data) {
                return '<span style="cursor:pointer; color: #006dab;" onclick="detailContainer('. $data->id .')">'. count($data->containers) .' <i class="fas fa-link ms-3" style=" color: #006dab;"></i></span>';
            })
            ->addColumn('action', function($data) {
                return '<a class="me-2" href="'. route('booking-in.edit', $data->id) .'" style="cursor:pointer;"><i class="fas fa-edit"></i></a>
                    <span class="me-2" style="cursor:pointer;" onclick="deleteBooking('. $data->id .')"><i class="fas fa-trash"></i></span>
                    <span style="cursor:pointer;" onclick="printBooking('. $data->id .')"><i class="fas fa-print"></i></span>';
            })
            ->rawColumns(['customer_id', 'booking_time', 'booked_by', 'containers', 'action'])
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

        $containers = $request->containers;
        for ($v = 0; $v < count($containers); $v++) {
            if ($containers[$v]['container_size_type_id'] == 'custom') {
                if ($containers[$v]['custom_container_size'] == NULL) {
                    return sendResponse(
                        ['error' => ['Custom Size Harus Diisi']],
                        'VALIDATION_FAILED',
                        500
                    );
                }
            }
        }
        // end::validation
        Db::beginTransaction();
        try {
            // variable;
            $bookingTime = $request->booking_time;
            $doReference = $request->do_reference;
            $customerId = $request->customer_id;
            $notes = $request->notes;
            $bookedBy = $request->booked_by;
            $acceptBy = $request->accept_by;
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
                'notes' => $notes,
                'booked_by' => $bookedBy,
                'barcode_path' => 'booking-in/' . $noSpaceCustomerName . '.svg'
            ];
            $bookingInId = BookingIn::insertGetId($data);

            for ($a = 0; $a < count($containers); $a++) {
                $containers[$a]['booking_id'] = $bookingInId;
                $containers[$a]['created_at'] = Carbon::now();
                if ($containers[$a]['container_size_type_id'] == 'custom') {
                    $containers[$a]['is_customer_container_size'] = true;
                    $containers[$a]['container_size_type_id'] = NULL;
                } else {
                    $containers[$a]['is_customer_container_size'] = false;
                }
            }
            BookingInContainer::insert($containers);
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
    public function show($id)
    {
        $pageTitle = 'Detail Booking In';
        $bookingIn = BookingIn::with(['containers.sizeType', 'bookedBy', 'customer'])
            ->find($id);
        return view('transactions.booking-in.show', compact('bookingIn', 'pageTitle'));
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

        $containers = $request->containers;
        for ($v = 0; $v < count($containers); $v++) {
            if ($containers[$v]['container_size_type_id'] == 'custom') {
                if ($containers[$v]['custom_container_size'] == NULL) {
                    return sendResponse(
                        ['error' => ['Custom Size Harus Diisi']],
                        'VALIDATION_FAILED',
                        500
                    );
                }
            }
        }
        // end::validation
        Db::beginTransaction();
        try {
            // variable;
            $bookingTime = $request->booking_time;
            $doReference = $request->do_reference;
            $customerId = $request->customer_id;
            $notes = $request->notes;
            $bookedBy = $request->booked_by;
            $acceptBy = $request->accept_by;

            $data = [
                'booking_time' => $bookingTime,
                'do_reference' => $doReference,
                'customer_id' => $customerId,
                'notes' => $notes,
                'booked_by' => $bookedBy,
                'updated_at' => Carbon::now(),
                'created_at' => Carbon::now(),
            ];
            BookingIn::where('id', $id)->update($data);

            for ($a = 0; $a < count($containers); $a++) {
                $containers[$a]['booking_id'] = $id;
                $containers[$a]['updated_at'] = Carbon::now();
                $containers[$a]['created_at'] = Carbon::now();
                if ($containers[$a]['container_size_type_id'] == 'custom') {
                    $containers[$a]['is_customer_container_size'] = true;
                    $containers[$a]['container_size_type_id'] = NULL;
                } else {
                    $containers[$a]['is_customer_container_size'] = false;
                }
            }
            BookingInContainer::where('booking_id', $id)->delete();
            BookingInContainer::insert($containers);
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
            $bookingIn = BookingIn::with(['containers.sizeType'])->find($id);
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
