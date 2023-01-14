<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerLoginController extends Controller

{
    public function login()
    {
        return view('customer.customer_login');
    }
}
