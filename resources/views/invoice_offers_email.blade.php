<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #e7f5f7;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
        }

        .logo-heading {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #007bff, #00c2ff);
            color: white;
            padding: 10px;
            border-radius: 20px;
            margin: 10px auto 5px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            position: relative;
            border: 1px solid rgb(205, 204, 204);
        }

        .logo-heading img {
            width: 60px;
            height: auto;
        }

        .contact-info {
            font-size: 1rem;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            text-align: right;
            flex-grow: 1;
        }

        @media (min-width: 768px) {
            .contact-info {
                position: absolute;
                bottom: 10px;
                right: 20px;
                flex-direction: row;
                align-items: center;
                text-align: right;
            }

            .contact-info p {
                padding: 0 8px;
                margin: 0;
            }
        }

        @media (max-width: 767.98px) {
            .logo-heading {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .contact-info {
                position: static;
                margin-top: 10px;
                align-items: center;
                text-align: center;
            }

            .contact-info p {
                padding: 2px 0;
            }
        }

        .contact-info p {
            margin: 0;
            padding: 0 10px;
        }

        .invoice-heading {
            font-size: 1.8rem;
            text-align: center;
            margin: 10px 0 30px;
            color: #007bff;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 2px 2px 5px #000000, -2px -2px 5px #000000;
            position: relative;
            text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-decoration: underline;
        }

        .card-custom {
            border: 1px solid rgb(205, 204, 204);
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 25px 30px;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: white;
            text-transform: capitalize;
            background: linear-gradient(135deg, #007bff, #00c2ff);
            padding: 12px 20px;
            border-radius: 10px 10px 0 0;
            margin: -25px -30px 20px -30px;
        }

        .highlight-saving {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.9rem;
        }

        .info-icon {
            color: #84ddf8;
            margin-right: 8px;
        }

        .card-body p {
            margin-bottom: 0.8rem;
            color: #495057;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-animated {
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards;
        }

        .card-animated:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card-animated:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card-animated:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card-animated:nth-child(4) {
            animation-delay: 0.4s;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="logo-heading d-flex" style="justify-content: space-between">
            <img src="{{ public_path('assets/images/mylogo.png') }}" alt="Company Logo">

            <div class="contact-info">
                <p><strong>Email:</strong> contact@mybillsmart.com</p>
                <p><strong>Phone:</strong> +1 800-123-4567</p>
            </div>
        </div>

        <div class="invoice-heading">OFFERS</div>
        {{-- <p class="text-center"><strong>Invoice ID:</strong> {{ $invoice_id }} | <strong>Client ID:</strong> {{ $client_id }}</p> --}}

        <div class="row">
            @foreach($offers as $offer)
            <div class="col-12 col-md-6 col-lg-4 mb-4 d-flex card-animated">
                <div class="card card-custom w-100">
                    <div class="card-body">
                        <h5 class="card-title">
                        </h5>
                        <p><strong>Provider Name:</strong> {{ $offer['provider_name'] }}</p>
                        <p><strong>Product Name:</strong> {{ $offer['product_name'] }}</p>
                        <p><strong>Sales Commission:</strong> {{ $offer['sales_commission'] }}</p>/span></p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</html>
