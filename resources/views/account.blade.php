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
<form action="{{ route('store')  }}" method="post">
    @csrf
    <div class="form-row">
        <div class="col">
            <select class="form-control" name="spender">
                <option value="0">Select User</option>
                <option value="1">Riaz</option>
                <option value="2">Tonni</option>
            </select>
        </div>
        <div class="col">
            <select class="form-control" name="purpose">
                <option value="0">Select Purpose</option>
                <option value="1">Self</option>
                <option value="2">Family Maintenance</option>
                <option value="3">Other</option>
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

    <div class="box-body bg-white">
    <table id="example" class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Purpose</th>
            <th>Date</th>
            <th>Remarks</th>
            <th>Entry date</th>
            <th style="text-align: right;">Amount</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @php $total=0; @endphp
        @php $riaz=0; $tonni=0; @endphp
        @foreach($data as $d)
            @php $total += $d->amount @endphp
            @if($d->spender == 1)
                @php $riaz += $d->amount @endphp
            @else
                @php $tonni += $d->amount @endphp
            @endif
            <tr>
                <td>
                    @if($d->spender == 1)
                        Riaz
                    @else
                        Tonni
                    @endif
                </td>
                <td>
                    @if($d->purpose == 1)
                        Self
                    @elseif($d->purpose == 2)
                        Family Maintenance
                    @else
                        Other
                    @endif
                <td>{{ $d->date }}</td>
                <td>{{ $d->remarks }}</td>
                @php
                    $datetime = $d->created_at;

                    $date = new DateTime($datetime);
                @endphp
                <td>{{ $date->format('D, M j, Y • h:i A') }}</td>
                <td style="text-align: right;"><strong>{{ $d->amount }} €</strong></td>
                <td style="text-align: right">
                    <a href="{{ route('delete', $d->id) }}"
                       onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger">
                        Delete
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th colspan="4" style="text-align: right; color: {{ $riaz > $tonni ? 'red' : 'green' }}">Riaz: {{ number_format($riaz,2) }} €</th>
            <th style="text-align: right; color: {{ $riaz < $tonni ? 'red' : 'green' }}">Tonni: {{ number_format($tonni,2) }} €</th>
            <th style="text-align: right">Total: {{ number_format($total,2)  }} €</th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
@endsection
