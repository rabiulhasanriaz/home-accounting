<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly History {{ $year }}</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        h2 { margin-bottom: 5px; }
        .month {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }
        ul { margin: 5px 0 5px 15px; }
    </style>
</head>
<body>

<h1>Monthly Financial History - {{ $year }}</h1>

@foreach($months as $m)
<div class="month">
    <h2>{{ $m['month_name'] }}</h2>

    <strong>Expenses (Grand):</strong> {{ number_format($m['grand_total'],2) }} €<br>
    <strong>Salary:</strong> {{ number_format($m['salary_total'],2) }} €<br>
    <strong>Difference:</strong> {{ number_format($m['difference'],2) }} €<br><br>

    <strong>Accounts:</strong> {{ number_format($m['accounts_total'],2) }} €
    @if(!empty($m['accounts_spenders']))
    <ul>
        @foreach($m['accounts_spenders'] as $spender => $amount)
        <li>{{ $spender == 1 ? 'Riaz' : ($spender == 2 ? 'Tonni' : 'Travel') }} : {{ number_format($amount, 2) }} €</li>
        @endforeach
    </ul>
    @endif

    <strong>Utilities:</strong> {{ number_format($m['utilities_total'],2) }} €
    @if(!empty($m['utilities_spenders']))
    <ul>
        @foreach($m['utilities_spenders'] as $spender => $amount)
        <li>{{ $spender == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount,2) }} €</li>
        @endforeach
    </ul>
    @endif

    <strong>Installments:</strong> {{ number_format($m['installments_total'],2) }} €
    @if(!empty($m['installments_paid_by']))
    <ul>
        @foreach($m['installments_paid_by'] as $payer => $amount)
        <li>{{ $payer == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount,2) }} €</li>
        @endforeach
    </ul>
    @endif

    <strong>Salary Breakdown:</strong>
    @if(!empty($m['salary_users']))
    <ul>
        @foreach($m['salary_users'] as $user => $amount)
        <li>{{ $user == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount,2) }} €</li>
        @endforeach
    </ul>
    @endif
</div>
@endforeach

</body>
</html>
