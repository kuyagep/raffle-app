<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config("app.name")}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .btn-deped {
    background-color: #003399;
    border-color: #003399;
    color: #fff;
}
.btn-deped:hover {
    background-color: #002b73;
    border-color: #002b73;
     color: #fff;
}

  </style>
</head>
  <body>


    <div class="container-fluid py-5" style="background-color: #f1f3f4;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-4">

                <!-- Image on Top -->
                <div class="text-center mb-4">
                    <img src="{{asset('images/banner.png')}}"
                         alt="Teacher's Day Banner"
                         class="img-fluid rounded"
                         style="max-height: 200px; object-fit: cover;">
                </div>

                <!-- Registration Form -->
                <div class="p-4 rounded shadow-sm bg-white">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  @yield("scripts")
  @stack('scripts')
</body>
</html>
