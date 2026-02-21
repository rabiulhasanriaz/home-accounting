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

    public function editAjax($id)
    {
        $installment = Installment::findOrFail($id);

        return response()->json([
            'id' => $installment->id,
            'date' => $installment->date,
            'amount' => $installment->amount,
            'remarks' => $installment->remarks,
            'paidBy' => $installment->paidBy,
            'purpose_id' => $installment->purpose_id,
        ]);
    }

    public function updateAjax(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        $installment = Installment::findOrFail($id);

        $installment->update([
            'date' => $request->date,
            'amount' => $request->amount,
            'remarks' => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Updated successfully',
            'data' => $installment
        ]);
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

        $rows = Account::selectRaw('MONTH(date) as month, spender, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'spender')
            ->orderBy(DB::raw('MONTH(date)'))
            ->get();

        $monthlyTotals = collect(range(1, 12))->map(function ($m) use ($rows, $year) {

            $monthRows = $rows->where('month', $m);

            return [
                'month_num'   => $m,
                'month_name'  => Carbon::createFromDate($year, $m, 1)->format('F'),

                'total'       => (float) $monthRows->sum('total'),

                'spenders'    => $monthRows->pluck('total', 'spender')->toArray(),
            ];
        });

        $day = date('j');
        $month = date('n');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        return view('history', compact('monthlyTotals', 'daysLeft', 'year'));
    }

    public function utility(){
        $year = now()->year;
        $day = date('j');
        $month = date('n');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;
        return view('utilities',compact('daysLeft'));
    }
}
