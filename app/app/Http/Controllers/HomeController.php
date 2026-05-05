<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\ProductSale;
use App\Models\CustomerServiceReport;
use Carbon\Carbon;
use App\Models\Product;
use DB;
use App\Models\ProductMovement;
use App\Models\ProductRejected;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {        
        return view('home');
    }
}
