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
                <th>Action</th>
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
//                    dd($items->first()->id);

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
                    <td>
                        <button
                            type="button"
                            class="btn btn-primary btn-update"
                            data-id="{{ $items->first()->id }}"
                        >
                            Update
                        </button>
                    </td>
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
@section('custom_script')
    <script type="text/javascript">
        $(document).ready(function () {

            // 1) CLICK UPDATE BUTTON -> load data by ajax -> open modal
            $(document).on('click', '.btn-update', function () {
                let id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    url: "{{ url('/installment') }}/" + id,
                    success: function (res) {
                        $('#edit_id').val(res.id);
                        $('#edit_date').val(res.date);
                        $('#edit_amount').val(res.amount);
                        $('#edit_remarks').val(res.remarks ?? '');

                        $('#reason_edit_modal').modal('show');
                    },
                    error: function () {
                        alert("Failed to load data");
                    }
                });
            });

            // 2) SUBMIT MODAL FORM -> update by ajax
            $('#reasonEditForm').on('submit', function(e){
                e.preventDefault();

                let id = $('#edit_id').val();

                $.ajax({
                    type: "POST",
                    url: "{{ url('/installment') }}/" + id,
                    data: $(this).serialize(),
                    success: function(res){
                        if(res.success){
                            $('#reason_edit_modal').modal('hide');
                            // optional: reload page or update the row in table
                            location.reload();
                        }
                    },
                    error: function(xhr){
                        // optional: show validation errors
                        console.log(xhr.responseText);
                        alert("Update failed");
                    }
                });
            });

        });
    </script>
@endsection
