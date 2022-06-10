<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Regency;
use App\Models\Village;
use Illuminate\Http\Request;

class RegionController extends Controller
{    
    /**
     * Function to get City by province id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getCity($id) {
        $city = Regency::where('province_id', $id)->get();

        return response()->json([
            'data' => $city,
            'message' => 'SUCCESS'
        ], 200);
    }

    /**
     * Function to get District / Village by city id
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getDistrict($id) {
        $districts = District::where('regency_id', $id)->get();

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

        return response()->json([
            'data' => $format,
            'message' => 'SUCCESS'
        ], 201);
    }
}
