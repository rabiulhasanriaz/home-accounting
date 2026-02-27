<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Installment;
use App\Models\Purpose;
use App\Models\Salary;
use App\Models\Utility;
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

        $salaries = Salary::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('amount', 'asc')
            ->get();

        return view('salary',compact('daysLeft','salaries'));
    }

    public function salaryStore(Request $request)
    {
        $data = new Salary();
        $data->user = $request->user_name;
        $data->company = $request->company_name;
        $data->date = $request->date;
        $data->amount = $request->amount;
        $data->save();
        return redirect()->back()->with('success','Salary Added Successfully');
    }

    public function history()
    {
        $year = now()->year;

// --- Accounts grouped ---
        $accountsRows = Account::selectRaw('MONTH(date) as month, spender, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'spender')
            ->get();

        $monthlyTotals = collect(range(1, 12))->map(function ($m) use ($accountsRows, $year) {
            $monthRows = $accountsRows->where('month', $m);

            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'total'      => (float) $monthRows->sum('total'),
                'spenders'   => $monthRows->pluck('total', 'spender')->toArray(),
            ];
        })->keyBy('month_num');


// --- Utilities grouped ---
        $utilityRows = Utility::selectRaw('MONTH(date) as month, spender, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'spender')
            ->get();

        $monthlyTotalUtilities = collect(range(1, 12))->map(function ($m) use ($utilityRows, $year) {
            $monthRows = $utilityRows->where('month', $m);

            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'total'      => (float) $monthRows->sum('total'),
                'spenders'   => $monthRows->pluck('total', 'spender')->toArray(),
            ];
        })->keyBy('month_num');


// --- Salary grouped ---
// IMPORTANT: use Salary model/table here (NOT Utility)
// If you don't have Salary model, use DB::table('salaries') instead.
        $salaryRows = Salary::selectRaw('MONTH(date) as month, user, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'user')
            ->get();

        $monthlySalaries = collect(range(1, 12))->map(function ($m) use ($salaryRows, $year) {
            $monthRows = $salaryRows->where('month', $m);

            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'total'      => (float) $monthRows->sum('total'),
                'users'      => $monthRows->pluck('total', 'user')->toArray(),
            ];
        })->keyBy('month_num');

        $installmentsRows = Installment::selectRaw('MONTH(date) as month, paidBy, SUM(amount) as total')
            ->whereYear('date', $year)
            ->groupBy(DB::raw('MONTH(date)'), 'paidBy')
            ->get();
        $monthlyInstallments = collect(range(1, 12))->map(function ($m) use ($installmentsRows, $year) {
            $monthRows = $installmentsRows->where('month', $m);

            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),
                'total'      => (float) $monthRows->sum('total'),
                'paid_by'    => $monthRows->pluck('total', 'paidBy')->toArray(),
            ];
        })->keyBy('month_num');

// --- Merge into one structure per month ---
        $months = collect(range(1, 12))->map(function ($m) use (
            $monthlyTotals,
            $monthlyTotalUtilities,
            $monthlySalaries,
            $monthlyInstallments,
            $year
        ) {
            $acc = $monthlyTotals->get($m, ['total' => 0, 'spenders' => []]);
            $uti = $monthlyTotalUtilities->get($m, ['total' => 0, 'spenders' => []]);
            $sal = $monthlySalaries->get($m, ['total' => 0, 'users' => []]);
            $ins = $monthlyInstallments->get($m, ['total' => 0, 'paid_by' => []]);

            // expenses
            $accountsTotal     = (float) ($acc['total'] ?? 0);
            $utilitiesTotal    = (float) ($uti['total'] ?? 0);
            $installmentsTotal = (float) ($ins['total'] ?? 0);

            $grand = $accountsTotal + $utilitiesTotal + $installmentsTotal;

            // income
            $salary = (float) ($sal['total'] ?? 0);

            return [
                'month_num'  => $m,
                'month_name' => Carbon::createFromDate($year, $m, 1)->format('F'),

                // totals
                'accounts_total'     => $accountsTotal,
                'utilities_total'    => $utilitiesTotal,
                'installments_total' => $installmentsTotal,
                'grand_total'        => $grand,

                // breakdowns
                'accounts_spenders'     => $acc['spenders'] ?? [],
                'utilities_spenders'    => $uti['spenders'] ?? [],
                'installments_paid_by'  => $ins['paid_by'] ?? [],

                // salary
                'salary_total' => $salary,
                'salary_users' => $sal['users'] ?? [],

                // diff (salary - expenses)
                'difference' => $salary - $grand,
            ];
        });

        $day = date('j');
        $month = date('n');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        return view('history', compact('months', 'daysLeft', 'year'));
    }

    public function spenderDetails($id){
        $details = Account::where('spender',$id)->get();
        return response()->json([
            'data' => $details
        ]);
    }

    public function utility(){
        $year = now()->year;
        $day = date('j');
        $month = date('n');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $daysLeft = $daysInMonth - $day;

        $data = Utility::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('amount', 'asc')
            ->get();
        return view('utilities',compact('daysLeft','data'));
    }

    public function utilityStore(Request $request){
        $data = new Utility();
        $data->spender = $request->spender;
        $data->purpose = $request->purpose;
        $data->date = $request->date;
        $data->amount = $request->amount;
        $data->remarks = $request->remarks;
        $data->save();
        return redirect()->back()->with('success','Utility Added Successfully');
    }
}
