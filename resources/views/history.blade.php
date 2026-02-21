@extends('layout.master')
@section('content')
    <div class="box-body bg-white">
        <div class="row">
            @foreach($monthlyTotals as $m)
                <div class="col-sm-3 mb-3">
                    <div class="card text-white bg-{{ $m['total'] > 600 ? 'danger' : 'success' }}">
                        <div class="card-header d-flex justify-content-between">
                            <span>{{ $m['month_name'] }}</span>
                            <span>{{ $year }}</span>
                        </div>

                        <div class="card-body">
                            <h5>Total: {{ number_format($m['total'], 2) }} €</h5>

                            @if(!empty($m['spenders']))
                                <ul class="mb-0 ps-3">
                                    @foreach($m['spenders'] as $spender => $amount)
                                        <li>{{  $spender == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
                                    @endforeach
                                </ul>
                            @else
                                <small>No data</small>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
