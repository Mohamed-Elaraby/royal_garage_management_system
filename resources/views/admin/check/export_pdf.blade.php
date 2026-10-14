<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <title>jsPDF Arabic Font Example</title>
</head>
<body>
<table>

    <thead>
    <tr>
        <th>{{ __('trans.car type') }}</th>
        <th>{{ __('trans.car color') }}</th>
        <th>{{ __('trans.plate number') }}</th>
        <th>{{ __('trans.client name') }}</th>
        <th>{{ __('trans.client phone') }}</th>
        <th>{{ __('trans.client branch') }}</th>
    </tr>
    </thead>
    <tbody>
        @foreach($checks as $check)

            <tr>
                <td>{{ $check['plate_number'] }}</td>
            </tr>

        @endforeach
    </tbody>
</table>
</body>
</html>
