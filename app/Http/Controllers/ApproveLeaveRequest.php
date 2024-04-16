<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApproveLeaveRequest extends Controller
{
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        try {
            $token = $request->input('token');

            if($token == 'XBsQ2od6PCHGfReIQHXHZAYUGAxhrZhKA7o75JDRcrr9PaQMxPB0ZudVahDRusIp') {
                $leaveRequest->update([
                    'status' => 'Approved',
                    'approved_by' => 1,
                    'date_approved' => now()
                ]);

                return redirect()->route('success-leave-request')->with('success', 'Leave Request has been approved successfully.');
            } else {
                return redirect()->route('success-leave-request')->with('error', 'Something went wrong!, please try again.');
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function success()
    {
        return view('success-leave-request');
    }
}
