<div class="card text-center">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="dropdown show">

            @php
                $current = Route::currentRouteName();
            @endphp

            <a class="btn btn-secondary dropdown-toggle" href="#" role="button"
               id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @switch($current)
                    @case('index') Monthly @break
                    @case('installment') Installments @break
                    @case('history') History @break
                    @case('salary') Salary @break
                    @case('utility') Utility @break
                    @default Menu
                @endswitch
            </a>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item" href="{{ route('index') }}">Monthly</a>
                <a class="dropdown-item" href="{{ route('installment') }}">Installments</a>
                <a class="dropdown-item" href="{{ route('salary') }}">Salary</a>
                <a class="dropdown-item" href="{{ route('utility') }}">Utility</a>
                <a class="dropdown-item" href="{{ route('history') }}">History</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <h5 class="card-title">Month of {{ date("F, Y") }}</h5>
    </div>
    <div class="card-footer text-muted">
        {{ $daysLeft }} Days to go
        (<span class="text-bold txt text-left"></span>)
    </div>
