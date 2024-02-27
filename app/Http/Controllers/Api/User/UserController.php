<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Str;

class UserController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService  $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $users = User::query();
        $users->with('roles');
        $users->when($request->name, function($query) use ($request){
            return $query->where('name', 'like', '%'.$request->name.'%');
        });
        $users->when($request->email, function($query) use ($request){
            return $query->where('email', 'like', '%'.$request->email.'%');
        });
        $users->when($request->phone, function($query) use ($request){
            return $query->where('phone', 'like', '%'.$request->phone.'%');
        });
        $users = $users
            ->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users'],
        ]);
        $randomPassword = Str::random(8);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($randomPassword),
        ]);
        if($user){
            $user->assignRole('Member');
            $this->notificationService->sendNotification($user->id,
                '
Akun anda telah dibuat pada '.\Carbon\Carbon::now()->diffForHumans().'
Nama: '.$request->name.'
Email: '.$request->email.'
Password: '.$randomPassword.'
Silahkan login ke sistem untuk mengganti password anda
Terima kasih

`Notifikasi ini dikirim melalui sistem SI-RIM`
                ');
        }
        return response()->json([
            'status' => true,
            'data' => $user
        ]);
    }
}
