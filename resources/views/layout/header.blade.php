<div class="card text-center">
    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="{{ route('index') }}">
            <button class="btn btn-info">
                <span>Monthly Expenses</span>
            </button>
        </a>
        <a href="{{ route('installment') }}">
            <button class="btn btn-info">
                <span>Installments</span>
            </button>
        </a>
        <span class="text-bold txt"></span>
    </div>
    <div class="card-body">
        <h5 class="card-title">Month of {{ date("F, Y") }}</h5>
    </div>
    <div class="card-footer text-muted">
        {{ $daysLeft }} Days to go
    </div>
</div>
