<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Installment;
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

        $purposes = Purpose::all();

        $installments = Installment::with('purposeRel')->get();
//        dd($installments);

        return view('installment',compact('daysLeft','purposes','installments'));
    }

    public function purposeAdd(Request $request)
    {
        $purpose = new Purpose();
        $purpose->name = $request->name;
        $purpose->date = $request->purposeDate;
        $purpose->amount = $request->purposeAmount;
        $purpose->remarks = $request->purposeRemarks;
        $purpose->save();
        return redirect()->back()->with('success','Purpose Added Successfully');
    }

    public function purposeShow()
    {
        $purposes = Purpose::all();
        return view('installment',compact('purposes'));
    }

    public function installmentStore(Request $request)
    {
        $data = new Installment();
        $data->paidBy = $request->spender;
        $data->purpose = $request->purpose;
        $data->date = $request->date;
        $data->amount = $request->amount;
        $data->remarks = $request->remarks;
        $data->save();
        return redirect()->back()->with('success','Installment Added Successfully');
    }
}
