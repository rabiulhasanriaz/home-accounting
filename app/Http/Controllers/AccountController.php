<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Purpose;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $day = date('j');
        $month = date('n');
        $year = date('Y');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        $data = Account::orderBy('amount', 'asc')->get();
//        dd($data);
        return view('account',compact('data','daysLeft'));
    }
    public function store(Request $request)
    {
//        dd($request->all());
        $data = new Account();
        $data->spender = $request->spender;
        $data->purpose = $request->purpose;
        $data->date = $request->date;
        $data->amount = $request->amount;
        $data->remarks = $request->remarks;
        $data->save();
        return redirect()->back()->with('success','Account Added Successfully');
    }

    public function installment()
    {
        $day = date('j');
        $month = date('n');
        $year = date('Y');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;
        return view('installment',compact('daysLeft'));
    }

    public function purposeAdd(Request $request)
    {
        $purpose = new Purpose();
        $purpose->name = $request->purpose;
        $purpose->date = $request->purposeDate;
        $request->amount = $request->purposeAmount;
        $purpose->save();
        return redirect()->back()->with('success','Purpose Added Successfully');
    }
}
