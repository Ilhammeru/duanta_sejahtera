<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\UserImage;
use App\Models\UserNetwork;
use App\Models\Bonus;
use App\Models\BonusLog;
use App\Models\Prospect;
use App\Models\Serial;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Write code on Method
 *
 * @return response()
 */
if (! function_exists('menuActive')) {
    function menuActive($routeName)
    {
        $class = 'active';

        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('sendResponse')) {
    function sendResponse($data, $message = 'SUCCESS', $status = 201) {
        return response()->json([
            'data' => $data,
            'message' => $message
        ], $status);
    }
}

if (! function_exists('menuShow')) {
    function menuShow($routeName)
    {
        $class = 'show';

        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 8) {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('generateRandomNumber')) {
    function generateRandomNumber($length = 6) {
        $chars = '0123456789';
        $charsLength = strlen($chars);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $chars[rand(0, $charsLength - 1)];
        }
        return $randomString;
    }
}

/**
 * booking code Format => ID Int -> Customer ID -> YYYYMMDD
 */
if (!function_exists('generateBookingCode')) {
    function generateBookingCode($ids = 0, $custId, $status) {
        $ids = $ids == 0 ? '1' : '0';
        $bookingCode = '0' . $ids;
        return $bookingCode . '-' . strtoupper($status) . '-' . $custId . '-' . date('Ymd');
    }
}

if(!function_exists('getRole')) {
    function getRole() {
        $userId = Auth::id();
        $rawRole = User::with(['userRole.role'])->find($userId);
        $role = $rawRole->userRole->role->name;
        return strtolower($role);
    }
}
