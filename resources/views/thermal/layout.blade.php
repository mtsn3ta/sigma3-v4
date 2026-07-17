<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @page {
            size: 58mm auto;
            margin: 2mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 54mm;
            margin: 0;
            padding: 0;
            background: #fff;
            color: #000;

            font-family: monospace;
            font-size: 11px;
            line-height: 1.4;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 8px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 0;
            vertical-align: top;
        }

        td.label {
            width: 35%;
        }

        td.separator {
            width: 4%;
            text-align: center;
        }

        td.value {
            width: 61%;
        }

        .signature {
            margin-top: 18px;
            text-align: center;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }

        .text-wrap {
            white-space: pre-wrap;
            word-break: break-word;
        }

        @media print {

            html,
            body {
                width: 54mm;
            }

            .no-print {
                display: none !important;
            }

        }
    </style>

    @stack('styles')

</head>

<body>

    @yield('content')

    @stack('scripts')

</body>

</html>
