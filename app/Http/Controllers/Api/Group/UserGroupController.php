<?php

namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\UserGroup;
use App\Models\Group;
use App\Models\User;

class UserGroupController extends Controller
{
    private $notificationService;
    public  function __construct(NotificationService  $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function index(Request $request)
    {
        $userGroups = UserGroup::query();
        $userGroups->with('user');
        $userGroups->with('group');
        $userGroups->when($request->user_id, function ($query) use ($request) {
            return $query->where('user_id', $request->user_id);
        });
        $userGroups->when($request->group_id, function ($query) use ($request) {
            return $query->where('group_id', $request->group_id);
        });
        $userGroups = $userGroups
            ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $userGroups
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'group_id' => ['required', 'exists:groups,id']
        ]);
        $userGroup = UserGroup::create([
            'user_id' => $request->user_id,
            'group_id' => $request->group_id,
        ]);
        $group = Group::where('id', $request->group_id)->first();
        if($userGroup){
//            $this->sendNotification($request->user_id, 'Anda telah ditambahkan ke grup - '.$group->name);
            $this->notificationService->sendNotification($request->user_id, 'Anda telah ditambahkan ke grup - '.$group->name.' pada '.date('Y-m-d H:i:s'));
        }else {
            return response()->json([
                'status' => false,
                'data' => [
                    'message' => 'Failed to add user to group'
                ]
            ], 400);
        }
        return response()->json([
            'status' => true,
            'data' => $userGroup
        ]);
    }

//    public function sendNotification($id, $data)
//    {
//        $user = User::where('id', $id)->first();
//        $body = array(
//            "api_key" => "38f71e4b924e4b88aa1d5674db2db35f358087f7",
//            "receiver" => $user->phone,
//            "data" => array("message" => 'Selamat di Sistem SI-RIM, *'.$user->name.'* . Anda telah ditambahkan ke grup - '.$data)
//        );
//
//        $curl = curl_init();
//        curl_setopt_array($curl, [
//            CURLOPT_URL => "https://wa.timpandawalima.id/api/send-message",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => json_encode($body),
//            CURLOPT_HTTPHEADER => [
//                "Accept: */*",
//                "Content-Type: application/json",
//            ],
//        ]);
//
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//
//        curl_close($curl);
//
//        if ($err) {
//           echo "cURL Error #:" . $err;
//        } else {
//              echo $response;
//        }
//    }
}
