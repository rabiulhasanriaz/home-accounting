<!DOCTYPE html>
<html>
<title>Accounting</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />



<body onload="startTime()">
<div class="container p-3 mb-2 bg-info text-dark">
    <h1>Account</h1>
    @include('layout.header')
    @yield('content')
    @include('layout.footer')
</div>

<!-- 1️⃣ jQuery FIRST (full version, not slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- 2️⃣ Popper -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>

<!-- 3️⃣ Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

<!-- 4️⃣ DataTables -->
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.js"></script>

<script>
    new DataTable('#example');
</script>

<!-- 5️⃣ Your page scripts LAST -->
@yield('custom_script')
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
