@extends('layout.master')
@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('danger'))
        <div class="alert alert-danger">
            {{ session()->get('danger') }}
        </div>
    @endif
    <form action="{{ route('salaryStore')  }}" method="post">
        @csrf
        <div class="form-row">
            <div class="col">
                <select class="form-control" name="user_name">
                    <option value="0">Select User</option>
                    <option value="1">Riaz</option>
                    <option value="2">Tonni</option>
                </select>
            </div>
            <div class="col">
                <input type="text" class="form-control" name="company_name" placeholder="Company Name">
            </div>
            <div class="col">
                <input type="date" class="form-control" name="date" required>
            </div>
            <div class="col">
                <input type="number" class="form-control" step="0.01" placeholder="Amount" name="amount" required>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>

    <!-- Button trigger modal -->

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
                <th>Company</th>
                <th>Date</th>
                <th>Entry date</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $riazTotal = 0;
                $tonniTotal = 0;
                $subtotal = 0;
            @endphp
            @foreach($salaries as $salary)
                @if($salary->user == 1)
                    @php
                        $riazTotal += $salary->amount;
                    @endphp
                @else
                    @php
                        $tonniTotal += $salary->amount;
                    @endphp
                @endif

                @php
                    $subtotal = $riazTotal + $tonniTotal;
                @endphp

                <tr>
                    <td>{{ $salary->name = 1 ? 'Riaz' : 'Tonni' }}</td>
                    <td>{{ $salary->company }}</td>
                    <td>{{ $salary->date }}</td>
                    <td>{{ $salary->created_at ?? '-' }}</td>
                    <td>{{ $salary->amount }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-primary btn-update"
                            data-id=""
                        >
                            Update
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>



            <tfoot>
            <tr>
                <th colspan="3" style="text-align: right; ">Riaz:  {{ number_format($riazTotal,2) }}€</th>
                <th style="text-align: right; ">Tonni: {{ number_format($tonniTotal,2) }}€</th>
                <th style="text-align: right">Total:  {{ number_format($subtotal,2) }}€</th>
            </tr>
            </tfoot>
        </table>
    </div>

    <div class="modal fade" id="reason_edit_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="reasonEditForm">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Update Installment</h4>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" id="edit_date" name="date">
                        </div>

                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" step="0.01" class="form-control" id="edit_amount" name="amount">
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <input type="text" class="form-control" id="edit_remarks" name="remarks">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
