<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Installment;
use App\Models\Purpose;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        $day = date('j');
        $month = date('n');
        $year = date('Y');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        $data = Account::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('amount', 'asc')
            ->get();
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

    public function delete($id){
        Account::where('id',$id)->delete();
        return redirect()->back()->with('danger','Account Deleted Successfully');
    }

    public function installment()
    {
        $day = date('j');
        $month = date('n');
        $year = date('Y');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

//        $purposes = Purpose::with('installmentRel')->get();
        $purposes = Purpose::withSum('installmentRel', 'amount')->get();
//        dd($purposes);

        $installments = Installment::with('purposeRel')
            ->orderBy('purpose')
            ->get()
            ->groupBy('purpose');
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

    public function salary()
    {
        $day = date('j');
        $month = date('n');
        $year = date('Y');

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;
        return view('salary',compact('daysLeft'));
    }

    public function history()
    {
        $year = now()->year;

        // 1) get totals per month that exist in DB
        $rows = Account::selectRaw('MONTH(date) as month, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'))
            ->orderBy(DB::raw('MONTH(date)'))
            ->get()
            ->keyBy('month'); // easy lookup by month number

        // 2) build a complete Jan..Dec array, filling missing months with 0
        $monthlyTotals = collect(range(1, 12))->map(function ($m) use ($rows, $year) {
            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'total'      => (float) ($rows[$m]->total ?? 0),
            ];
        });

        // (your existing "days left" logic if you still need it)
        $day = date('j');
        $month = date('n');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        return view('history', compact('monthlyTotals', 'daysLeft', 'year'));
    }
}
