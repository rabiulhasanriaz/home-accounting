@extends('layout.master')
@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    <form action="{{ route('installmentStore')  }}" method="post">
        @csrf
        <div class="form-row">
            <div class="col">
                <select class="form-control" name="spender">
                    <option value="0">Paid By</option>
                    <option value="1">Riaz</option>
                    <option value="2">Tonni</option>
                </select>
            </div>
            <div class="col">
                <select class="form-control" name="purpose">
                    <option value="0">Select Purpose</option>
                    @foreach($purposes as $purpose)
                        @php
                            $paid = $purpose->installment_rel_sum_amount ?? 0;
                            $isPaid = $paid >= $purpose->amount;
                        @endphp

                        <option value="{{ $purpose->id }}" {{ $isPaid ? 'disabled' : '' }}>
                            {{ $purpose->name }}
                            (Total: {{ number_format($purpose->amount,2) }},
                            Paid: {{ number_format($paid,2) }})
                            {{ $isPaid ? ' — PAID' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <input type="date" class="form-control" name="date" required>
            </div>
            <div class="col">
                <input type="number" class="form-control" step="0.01" placeholder="Amount" name="amount" required>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="remarks" placeholder="Remarks">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        Add Purpose
    </button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Purpose</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('purposeStore')  }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Purpose</label>
                            <input type="text" class="form-control" name="name" id="exampleInputEmail1"  placeholder="Enter Purpose" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputDate">Date</label>
                            <input type="date" class="form-control" name="purposeDate" id="exampleInputDate"  placeholder="Enter Date" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputAmount">Amount</label>
                            <input type="number" step="0.01" class="form-control" name="purposeAmount" id="exampleInputAmount" placeholder="Amount" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputAmount">Remarks</label>
                            <input type="text" class="form-control" name="purposeRemarks" id="exampleInputAmount" placeholder="Remarks" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="box-body bg-white">
        <table id="example" class="table table-striped">
            <thead>
            <tr>
                <th>Name</th>
                <th>Purpose</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Entry date</th>
                <th style="text-align: right;">Paid</th>
                <th style="text-align: right;">Due</th>
                <th style="text-align: right;">Total</th>
            </tr>
            </thead>
            <tbody>
            @php
                $riazTotal = 0;
                $tonniTotal = 0;
                $subtotal = 0;
                $bothDue = 0;
                $totalDue = 0;
            @endphp
            @foreach($installments as $purposeId => $items)
                @php
//                    dd($items->first()->paidBy);

                    $purpose = $items->first()->purposeRel;
                    $totalPaid = $items->sum('amount');
                    $total = $purpose?->amount ?? 0;
                    $due = $total - $totalPaid;
                    $paidByNames = $items
                                ->pluck('paidBy')
                                ->unique()
                                ->map(fn ($p) => $p == 1 ? 'Riaz' : 'Tonni')
                                ->implode(', ');
                    $last = $items->sortByDesc('date')->first();

                    $riazPaid  = $items->where('paidBy', 1)->sum('amount');
                    $tonniPaid = $items->where('paidBy', 2)->sum('amount');

                    $riazTotal += $riazPaid;
                    $tonniTotal += $tonniPaid;
                    $subtotal += $total;
                    $bothDue = $riazTotal + $tonniTotal;
                    $totalDue = $subtotal - $bothDue;
                @endphp

                <tr style="background-color: {{ $due > 0 ? 'red' : 'green' }}">
                    <td>{{ $paidByNames }}</td>
                    <td>{{ $purpose?->name ?? 'Unknown' }}</td>
                    <td>{{ $last?->date ?? '-' }}</td>
                    <td>{{ $last?->remarks ?? '-' }}</td>
                    <td>{{ $last?->created_at ?? '-' }}</td>
                    <td style="text-align:right;">{{ number_format($totalPaid, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($due, 2) }}</td>
                    <td style="text-align:right;">{{ number_format($total, 2) }}</td>
                </tr>
            @endforeach
            </tbody>



            <tfoot>
            <tr>
                <th colspan="6" style="text-align: right; ">Riaz:  {{ number_format($riazTotal,2) }}€</th>
                <th style="text-align: right; ">Tonni: {{ number_format($tonniTotal,2) }}€</th>
                <th style="text-align: right">Total:  {{ number_format($subtotal,2) }}€ Due: {{ number_format($totalDue,2) }}€</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
