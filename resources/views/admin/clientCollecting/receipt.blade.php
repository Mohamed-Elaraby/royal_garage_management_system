<!doctype html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSS Files -->
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- RTL Files -->
    <link rel="stylesheet" href="{{ asset('assets/dist/rtl/css/bootstrap.rtl.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/receipt/css/payments&collecting_receipt.css') }}">
    <style>
        @font-face {
            font-family: myFirstFont;
            src:url({{ asset('assets/dist/fonts/sst-arabic-medium.ttf') }});
        }
        .collecting_receipt
        {
            width: 90%;
            padding: 10px;
            /*margin: auto;*/
            border: 1px solid #0d0d0d;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <title>سند قبض من العميل </title>
</head>
<body>
<div class="buttons_area">
    <button id="printBTN" class="btn btn-primary btn-sm btn-block" onclick="window.print()" >
        <span>طباعة <i class="fa fa-print"></i></span>
    </button>

    <a href="{{ route('admin.clientCollecting.index') }}" class="btn btn-warning btn-sm btn-block">
        <span>الذهاب الى صفحة سندات قبض العملاء <i class="fa fa-backward"></i></span>
    </a>

</div>
<div class="container">
    <div class="collecting_receipt">
        <div class="receipt_title" style="height: 100px;">
            <table style="width: 100%; table-layout: fixed">
                <tr>
                    <td style="width: 33.33%; text-align: right">
                        <div style="text-align: center">
                            <p>مركز الكراج الملكي</p>
                            <p>لصيانة السيارات الاوروبية</p>
                        </div>
                    </td>
                    <td style="width: 33.33%; text-align: center">
                        <h6 class="text-center" style="font-weight: bold">سند قبض رقم {{ $collecting_data -> receipt_number ?? '-----' }}</h6>
                        <hr style="border-bottom: 1px solid #000; width: 70px; margin: auto">
                    </td>
                    <td style="width: 33.33%; text-align: left">
                        <img src="{{ asset('storage/logo.png') }}" alt="" width="150">
                    </td>
                </tr>
            </table>
        </div>
        <div class="receipt_body">
            <table style="width: 100%">
                <tr>
                    <td style="width: 30%">تم تحصيل مبلغ وقدره:</td>
                    <td style="width: 70%">{{ $collecting_data -> amount_paid + $collecting_data -> amount_paid_bank }} دولار</td>
                </tr>
                <tr>
                    <td style="width: 30%">عن طريق :</td>
                    <td style="width: 70%">
                        @if($collecting_data -> payment_method && $collecting_data -> payment_method_bank)

                            {{ ' جزء '. $collecting_data -> payment_method . ' و جزء ' . $collecting_data -> payment_method_bank }}

                        @elseif ($collecting_data -> payment_method)

                            {{ $collecting_data -> payment_method }}

                        @elseif($collecting_data -> payment_method_bank)

                            {{ $collecting_data -> payment_method_bank }}

                        @endif
                    </td>
                </tr>
                <tr>
                    <td>من العميل:</td>
                    <td>{{ $collecting_data -> client -> name }}</td>
                </tr>
                <tr>
                    <td>في فرع:</td>
                    <td>{{ $collecting_data -> branch -> display_name }}</td>
                </tr>
                <tr>
                    <td>وذلك قيمة:</td>
                    <td>{{ $collecting_data -> notes ? $collecting_data -> notes : '-----------' }}</td>
                </tr>
                <tr>
                    <td>بتاريخ:</td>
                    <td>{{ $collecting_data -> created_at -> format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>
        <div class="receipt_footer">
            <table style="width: 100%">
                <tr>
                    <td style="width: 50%"><h6 class="text-center" style="font-weight: bold">توقيع المستلم</h6></td>
                    <td style="width: 50%"><h6 class="text-center" style="font-weight: bold">الختم</h6></td>
                </tr>
                <tr>
                    <td style="width: 50%"><p class="text-center">{{ $collecting_data -> user -> name }}</p></td>
                    <td style="width: 50%"><p class="text-center">..........</p></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<hr style="border-bottom: 1px solid #000; width: 20px">
<!-- Java Script Files -->
<!-- jQuery 3 -->
<script src="{{ asset('assets/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
@if (LaravelLocalization::getCurrentLocale() === 'ar')
    <!-- RTL Js Files -->
    <script src="{{ asset('assets/dist/rtl/js/adminlte-rtl.js') }}"></script>
@endif
<script src="{{ asset('assets/receipt/js/mainReceipt.js') }}"></script>

<script>

</script>
</body>
</html>
