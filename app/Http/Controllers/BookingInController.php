<?php

namespace App\Http\Controllers;

use App\Models\BookingIn;
use Illuminate\Http\Request;

class BookingInController extends Controller
{
    private $staticCode;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Booking In';
        return view('transactions.booking-in', compact('pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
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
    public function edit(BookingIn $bookingIn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookingIn  $bookingIn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookingIn $bookingIn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookingIn  $bookingIn
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookingIn $bookingIn)
    {
        //
    }
}
