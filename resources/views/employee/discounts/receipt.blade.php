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

    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
    <style>
        @font-face {
            font-family: myFirstFont;
            src:url({{ asset('assets/dist/fonts/sst-arabic-medium.ttf') }});
        }
        .payment_receipt
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
    <title>سند اخطار بالخصم</title>
</head>
<body>
<div class="buttons_area">
{{--    <button id="printBTN" class="btn btn-primary btn-sm btn-block" onclick="window.print()" >--}}
{{--        <span>طباعة <i class="fa fa-print"></i></span>--}}
{{--    </button>--}}
    <button
        id="discount_print"
        class="btn btn-primary btn-sm btn-block"
        data-link="{{ route('employee.discount_receipt_print', ['discount_id' => $discount -> id]) }}"
        href="javascript:void(0);"
        onclick="printJS($(this).data('link'))"
    >
        <span> طباعة <i class="fa fa-print"></i></span>
    </button>

    <a href="{{ route('employee.discounts.index') }}" class="btn btn-warning btn-sm btn-block">
        <span>الذهاب الى صفحة جميع الخصومات <i class="fa fa-backward"></i></span>
    </a>

</div>
<div class="container">
    <div class="payment_receipt">
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
                        <h6 class="text-center" style="font-weight: bold">سند اخطار بالخصم</h6>
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
                    <td>اقر انا الموظف:</td>
                    <td>{{ $discount -> employee -> name }}</td>
                </tr>
                <tr>
                    <td>اعمل في فرع:</td>
                    <td>{{ $discount -> employee -> branch -> display_name }}</td>
                </tr>
                <tr>
                    <td style="width: 30%">انه قد تم ابلاغى بخصم مبلغ وقدره:</td>
                    <td style="width: 70%">{{ $discount -> amount }} دولار</td>
                </tr>
                <tr>
                    <td style="width: 30%">عن طريق :</td>
                    <td style="width: 70%">من الراتب</td>
                </tr>
                <tr>
                    <td>وذلك بسبب:</td>
                    <td>{{ $discount -> notes ? $discount -> notes : '-----------' }}</td>
                </tr>
                <tr>
                    <td>بتاريخ:</td>
                    <td>{{ $discount -> created_at -> format('d/m/Y') }}</td>
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
                    <td style="width: 50%" class="text-center">
                        <img src="{{ asset('storage' . DIRECTORY_SEPARATOR . 'employees_signature' . DIRECTORY_SEPARATOR . 'discount_signature' . DIRECTORY_SEPARATOR . $discount -> id  . DIRECTORY_SEPARATOR . $employee_discount_signature -> image_name) }}" alt="" height="100" width="200">

                    </td>
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
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<script>

</script>
</body>
</html>
