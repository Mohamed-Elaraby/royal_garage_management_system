<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>number</th>
        <th>اسم العميل</th>
        <th>رقم العميل</th>
    </tr>
    </thead>
    <tbody>
    @foreach($checks as $check)
        <tr>
            <td>{{ $check -> check_number }}</td>
            <td>{{ $check -> client -> name }}</td>
            <td>{{ $check -> client -> phone }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
