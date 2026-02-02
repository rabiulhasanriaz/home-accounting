<!DOCTYPE html>
<html>
<head>
    <title>Accounting</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
</head>
<body onload="startTime()">
<div class="container p-3 mb-2 bg-info text-dark">
    <h1>Account</h1>
    <div class="card text-center">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Menu
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    <a class="dropdown-item" href="{{ route('index') }}">Monthly</a>
                    <a class="dropdown-item" href="{{ route('installment') }}">Installments</a>
                    <a class="dropdown-item" href="{{ route('history') }}">History</a>
                    <a class="dropdown-item" href="{{ route('salary') }}">Salary</a>
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
    </div>

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
                            <h5 class="card-title">
                                Total: {{ number_format($m['total'], 2) }} €
                            </h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <footer>
        <p>Author: Rabiul Hasan, Sumiya Islam Tonni</p>
        <p><a href="rabiulhasanriaz@gmail.com">Email Me</a></p>
    </footer>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.js"></script>
<script>
    new DataTable('#example');
</script>
<script>
    function startTime() {
        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();

        m = checkTime(m);
        s = checkTime(s);

        document.getElementsByClassName('txt')[0].innerHTML =
            h + ":" + m + ":" + s;

        setTimeout(startTime, 1000);
    }

    function checkTime(i) {
        return i < 10 ? "0" + i : i;
    }

    startTime(); // 🔴 IMPORTANT: call the function
</script>
{{--@vite('resources/js/form/jquery.min.js')--}}

{{--@vite('resources/js/form/demo.js')--}}
{{--@vite('resources/js/form/adminlte.min.js')--}}

{{--@vite('resources/js/form/bs-custom-file-input.min.js')--}}
{{--@vite('resources/js/form/bootstrap.bundle.min.js')--}}

</body>
</html>
