<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        h1, h2 { margin: 0 0 10px 0; }
        .box { border: 1px solid #ccc; padding: 10px; margin-top: 10px; }
        ul { margin: 5px 0 5px 18px; }
        .row { margin-bottom: 6px; }
    </style>
</head>
<body>

<h1>Monthly Report: {{ $month_name }} {{ $year }}</h1>

<div class="box">
    <div class="row"><strong>Expenses (Grand):</strong> {{ number_format($grand_total, 2) }} €</div>
    <div class="row"><strong>Salary:</strong> {{ number_format($salary_total, 2) }} €</div>
    <div class="row"><strong>Difference:</strong> {{ number_format($difference, 2) }} €</div>
</div>

<div class="box">
    <div class="row"><strong>Accounts:</strong> {{ number_format($accounts_total, 2) }} €</div>
    @if(!empty($accounts_spenders))
        <ul>
            @foreach($accounts_spenders as $spender => $amount)
                <li>{{ $spender == 1 ? 'Riaz' : ($spender == 2 ? 'Tonni' : 'Travel') }} : {{ number_format($amount, 2) }} €</li>
            @endforeach
        </ul>
    @endif
</div>

<div class="box">
    <div class="row"><strong>Utilities:</strong> {{ number_format($utilities_total, 2) }} €</div>
    @if(!empty($utilities_spenders))
        <ul>
            @foreach($utilities_spenders as $spender => $amount)
                <li>{{ $spender == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
            @endforeach
        </ul>
    @endif
</div>

<div class="box">
    <div class="row"><strong>Installments:</strong> {{ number_format($installments_total, 2) }} €</div>
    @if(!empty($installments_paid_by))
        <ul>
            @foreach($installments_paid_by as $payer => $amount)
                <li>{{ $payer == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
            @endforeach
        </ul>
    @endif
</div>

<div class="box">
    <div class="row"><strong>Salary by user:</strong></div>
    @if(!empty($salary_users))
        <ul>
            @foreach($salary_users as $user => $amount)
                <li>{{ $user == 1 ? 'Riaz' : 'Tonni' }} : {{ number_format($amount, 2) }} €</li>
            @endforeach
        </ul>
    @endif
</div>

</body>
</html>
