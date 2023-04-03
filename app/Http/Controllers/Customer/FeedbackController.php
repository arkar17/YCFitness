<?php

namespace App\Http\Controllers\Customer;

use App\Models\Feedback;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FeedbackController extends Controller
{
    //
    public function feedback_send(Request $request ){
        // dd($request->description);
        $user_id = Auth::user()->id;
        $feedback_store = New Feedback();
        $feedback_store->user_id = $user_id;
        $feedback_store->description = $request->description;
        $feedback_store->save();
        Alert::success('Success', 'Feedback sent to admin!');
        return redirect()->back();
    }
}
