@extends('layout.master')
@section('content')
    <div class="box-body bg-white">
        <div class="row">
            @foreach($months as $m)
                <div class="col-sm-3 mb-3">

                    {{-- Option 1: color by expenses --}}
                    <div class="card text-white bg-{{ $m['grand_total'] > 1300 ? 'danger' : 'success' }}">

                        {{-- Option 2: color by difference (uncomment if you prefer) --}}
                        {{-- <div class="card text-white bg-{{ $m['difference'] < 0 ? 'danger' : 'success' }}"> --}}

                        <div class="card-header d-flex justify-content-between">
                            <span>{{ $m['month_name'] }}</span>
                            <span>{{ $year }}</span>
                        </div>

                        <div class="card-body">
                            <h5>Expenses (Grand): {{ number_format($m['grand_total'], 2) }} €</h5>

                            <h6 class="{{ $m['difference'] < 0 ? 'text-warning' : 'text-light' }}">
                                Difference: {{ number_format($m['difference'], 2) }} €
                            </h6>


                            @if(!empty($m['accounts_spenders']))
                                <hr>
                                <div class="mt-2">
                                    <strong>Accounts:</strong> {{ number_format($m['accounts_total'], 2) }} €
                                </div>
                                <small><b>Accounts by spender</b></small>
                                <ul class="mb-0 ps-3">
                                    @foreach($m['accounts_spenders'] as $spender => $amount)
                                        <li>{{ $spender == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($m['utilities_spenders']))
                                <hr>
                                <div class="mt-2">
                                    <strong>Utilities:</strong> {{ number_format($m['utilities_total'], 2) }} €
                                </div>
                                <small><b>Utilities by spender</b></small>
                                <ul class="mb-0 ps-3">
                                    @foreach($m['utilities_spenders'] as $spender => $amount)
                                        <li>{{ $spender == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if(!empty($m['salary_users']))
                                <hr>
                                <h5>Salary: {{ number_format($m['salary_total'], 2) }} €</h5>
                                <small><b>Salary by user</b></small>
                                <ul class="mb-0 ps-3">
                                    @foreach($m['salary_users'] as $user => $amount)
                                        <li>{{ $user == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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
            $(document).on('click', '.spender_details', function () {
                let id = $(this).data('id');
                console.log(id);
                $.ajax({
                    type: "GET",
                    url: "{{ url('/spender-details') }}/" + id,
                    success: function (res) {
                        alert(res);
                    },
                    error: function () {
                        alert("Failed to load data");
                    }
                });
            });


        });
    </script>
@endsection
