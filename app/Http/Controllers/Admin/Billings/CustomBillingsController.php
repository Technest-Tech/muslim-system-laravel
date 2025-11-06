<?php

namespace App\Http\Controllers\Admin\Billings;

use App\Http\Controllers\Controller;
use App\Models\Billings;
use App\Models\User;
use Illuminate\Http\Request;

class CustomBillingsController extends Controller
{
    public function index()
    {
        $students = User::where('user_type', 'student')->get();
        return view('admin.bilings.custom_billings',compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'total' => 'required',
            'currency' => 'required',
        ]);

        $total = round($request->total, 2);
        $currency = $request->currency;
        $month = date('m');

        $url = route('pay2',['0',$month,$total,$currency]);
        return view('admin.bilings.custom_billings_link',compact('url'));
    }

    public function payBill($student_id,$month)
    {
        $billings = Billings::where('student_id',$student_id)->where('month','0'.$month)->update(['is_paid'=>1]);
        return redirect()->back();
    }
}
