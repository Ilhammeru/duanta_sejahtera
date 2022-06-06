<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService {
    public function saveUser(Request $request, $id = NULL) {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'birth_date' => $request->birth_of_date,
            'date_in' => $request->date_in,
            'division_id' => $request->division,
            'password' => Hash::make(generateRandomString(6)),
            'identity_number' => $request->nik,
            'status' => TRUE,
            'created_at' => Carbon::now()
        ];

        DB::beginTransaction();
        try {
            // save image if exist
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $name = date('Ymd') . "_User_." . $image->getClientOriginalExtension();

                $filePath = Storage::putFileAs('public/user', $image, $name);

                if ($filePath) {
                    $data['photo'] = 'user/' . $name;
                }
            }

            if ($id == NULL) {
                $user = User::create($data);
            } else {
                $data['updated_at'] = Carbon::now();
                unset($data['created_at']);
                $user = User::updateOrCreate(
                    ['id' => $id],
                    $data
                );
                $user->save();
            }
            $dataRole = [
                'role_id' => $request->role,
                'user_id' => $user->id,
                'created_at' => Carbon::now()
            ];

            if ($id == NULL) {
                UserRole::insert($dataRole);
            } else {
                $currentRole = UserRole::select('id')->where('user_id', $id)->first();
                $currentRole->role_id = $request->role;
                $currentRole->save();
            }

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return [
                'message' => 'FAILED',
                'data' => $th->getMessage(),
                'status' => 500
            ];
        }
        DB::commit();
        return [
            'message' => 'SUCCESS',
            'status' => 201,
            'data' => [
                'user' => $user ?? [],
                'role' => $currentRole ?? []
            ]
        ];
    }

    public function getUserById($id) {
        $user = User::where(['id' => $id, 'status' => 1])->first();
        return $user;
    }

    public function delete($id) {
        try {
            $delete = User::where('id', $id)->update([
                'status' => 0,
                'updated_at' => Carbon::now()
            ]);
            return [
                'data' => $delete,
                'message' => 'SUCCESS',
                'status' => 201
            ];
        } catch (\Throwable $th) {
            return [
                'data' => $th->getMessage(),
                'message' => "FAILED",
                'status' => '500'
            ];
        }
    }
}
