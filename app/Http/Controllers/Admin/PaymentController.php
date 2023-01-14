<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Payment;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function detail($id){

            $payment = Payment::where('user_id',$id)->latest()->first();
           
            return view('admin.request.paymentdetail', compact('payment'));
    }

    public function transctionView(){
        return view('admin.payment.paymentTransction');
    }

    public function bankPaymentTransction(Request $request)
    {
        if(request()->ajax())
        {
            if(!empty($request->start_date)){
                $banktransction = Payment::where('payment_type','banking')->with('user')->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->whereHas('user', function($query){
                    $query->where('active_status',2);
                });

                return Datatables::of($banktransction)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {

                $detail_icon = '';

                $detail_icon = '<a href=" ' . route('transactionbank.detail', $each->id) . ' " class="text-warning mx-1 mt-1" title="payment">
                                    <i class="fa-solid fa-circle-info fa-xl"></i>
                              </a>';

                return '<div class="d-flex justify-content-center">' .$detail_icon. '</div>';
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('m-d-Y');
            })
            ->make(true);
            }
            else{
                $banktransction = Payment::where('payment_type','banking')->with('user')->whereHas('user', function($query){
                    $query->where('active_status',2);
                });

                return Datatables::of($banktransction)
            ->addIndexColumn()
            ->addColumn('action', function ($each) {

                $detail_icon = '';

                $detail_icon = '<a href=" ' . route('transactionbank.detail', $each->id) . ' " class="text-warning mx-1 mt-1" title="payment">
                                    <i class="fa-solid fa-circle-info fa-xl"></i>
                              </a>';

                return '<div class="d-flex justify-content-center">' .$detail_icon. '</div>';
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('m-d-Y');
            })
            ->make(true);
            }
        }

        //dd($banktransction->toArray());

    }

    public function transactionBankDetail($id){

        $banktransctiondetail = Payment::where('payment_type','banking')->where('id',$id)->with('user')->first();
        //dd($banktransctiondetail->toArray());
        return view('admin.payment.transactionBankDetail', compact('banktransctiondetail'));
    }

    public function EPaymentTransction(Request $request)
    {
        if(request()->ajax()){
            if(!empty($request->start_date)){

                $wallettransction = Payment::where('payment_type','ewallet')->with('user')->whereDate('created_at','>=',$request->start_date)->whereDate('created_at','<=',$request->end_date)->whereHas('user', function($query){
                    $query->where('active_status',2);
                });
                return Datatables::of($wallettransction)
                    ->addIndexColumn()
                    ->addColumn('action', function ($each) {

                        $detail_icon = '';

                        $detail_icon = '<a href=" ' . route('transactionwallet.detail', $each->id) . ' " class="text-warning mx-1 mt-1" title="paymentfhkgldh">
                                            <i class="fa-solid fa-circle-info fa-xl"></i>
                                      </a>';

                        return '<div class="d-flex justify-content-center">' .$detail_icon. '</div>';
                    })
                    ->editColumn('created_at', function ($each) {
                        return Carbon::parse($each->created_at)->format('m-d-Y');
                    })
                    ->make(true);

            }else{
                $wallettransction = Payment::where('payment_type','ewallet')->with('user')->whereHas('user', function($query){
                    $query->where('active_status',2);
                });
                return Datatables::of($wallettransction)
                    ->addIndexColumn()
                    ->addColumn('action', function ($each) {

                        $detail_icon = '';

                        $detail_icon = '<a href=" ' . route('transactionwallet.detail', $each->id) . ' " class="text-warning mx-1 mt-1" title="payment">
                                            <i class="fa-solid fa-circle-info fa-xl"></i>
                                      </a>';

                        return '<div class="d-flex justify-content-center">' .$detail_icon. '</div>';
                    })
                    ->editColumn('created_at', function ($each) {
                        return Carbon::parse($each->created_at)->format('m-d-Y');
                    })
                    ->make(true);
            }
        }


    }

    public function transactionWalletDetail($id){
        $wallettransctiondetail = Payment::where('payment_type','ewallet')->where('id',$id)->with('user')->first();
        return view('admin.payment.transactionWalletDetail', compact('wallettransctiondetail'));
    }
}
