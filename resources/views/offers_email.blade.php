<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Offers</title>
</head>
<body>
    <h1>Your Offers</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Provider Name</th>
                <th>Product Name</th>
                <th>Sales Commission</th>
                <th>Saving</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offers as $offer)
                <tr>
                    <td>{{ $offer->provider_name }}</td>
                    <td>{{ $offer->product_name }}</td>
                    <td>{{ $offer->sales_commission }}</td>
                    <td>{{ $offer->saving }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
