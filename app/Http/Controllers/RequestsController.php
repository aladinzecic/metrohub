<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class RequestsController extends Controller
{

    public function approve($id){
        User::findOrFail($id)->update(['status'=>'active']);
        return back()->with('success','User approved successfully.');
    }

    public function reject($id){
        User::findOrFail($id)->update(['status'=>'inactive']);
        return back()->with('error','User rejected successfully.');
    }


    public function index(){
        $pendingCount= User::where('status','pending')->count();

        $approvedCount=User::where('status','active')
                            ->whereIn('role',['driver','mechanic','dispatcher','controller'])
                            ->whereMonth('updated_at',now()->month)
                            ->count();
                            
        $rejectedCount=User::where('status','rejected')
                            ->whereIn('role',['driver','mechanic','dispatcher','controller'])
                            ->whereMonth('updated_at',now()->month)
                            ->count();
        
        $pendingUsers=User::where('status','pending')
                            ->whereIn('role',['driver','mechanic','dispatcher','controller'])
                            ->latest()
                            ->get();
                            
        $processedUsers=User::whereNot('status','pending')
                            ->whereIn('role',['driver','mechanic','dispatcher','controller'])
                            ->orderBy('updated_at', 'desc')
                            ->get();
        return view('dispatcher.dispatcherrequests',[
            'pendingCount'=>$pendingCount,
            'approvedCount'=>$approvedCount,
            'rejectedCount'=>$rejectedCount,
            'pendingUsers'=>$pendingUsers,
            'processedUsers'=>$processedUsers,
        ]);
    }
}
