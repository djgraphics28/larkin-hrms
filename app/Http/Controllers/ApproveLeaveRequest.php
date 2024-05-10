<?php

namespace App\Http\Controllers;

use App\Events\NotifyUser;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class ApproveLeaveRequest extends Controller
{
    public function approve(Request $request, $id)
    {
        try {
            // $token = $request->input('token');

            // if($token == 'XBsQ2od6PCHGfReIQHXHZAYUGAxhrZhKA7o75JDRcrr9PaQMxPB0ZudVahDRusIp') {
            //     $leaveRequest->update([
            //         'status' => 'Approved',
            //         'approved_by' => 1,
            //         'date_approved' => now()
            //     ]);

            //     return redirect()->route('success-leave-request')->with('success', 'Leave Request has been approved successfully.');
            // } else {
            //     return redirect()->route('success-leave-request')->with('error', 'Something went wrong!, please try again.');
            // }
            // $leaveRequest->update([
            //     'status' => 'Approved',
            //     'approved_by' => 1,
            //     'date_approved' => now()
            // ]);
            $leave = LeaveRequest::findOrFail($id);
            $leave->update([
                'status' => 'Approved',
                'approved_by' => 1,
                'date_approved' => now()
            ]);

            // if($leave->status == 'Approved') {
            //     $user = $leave->user;
            //     $user->update([
            //         'le' => $user->leave_balance - $leave->days
            //     ]);
            // }

            if ($leave) {
                //notify
                $name = "Leave Request Approved!";

                event(new NotifyUser($name));
                return redirect()->route('success-leave-request')->with('success', 'Leave Request has been approved successfully.');
            } else {
                return redirect()->route('success-leave-request')->with('error', 'Something went wrong!, please try again.');
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->route('success-leave-request')->with('error', 'Something went wrong!, please try again.');
        }
    }

    public function success()
    {
        return view('success-leave-request');
    }
}
