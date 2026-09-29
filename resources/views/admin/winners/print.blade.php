<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OFFICIAL LIST OF RAFFLE WINNERS - WORLD TEACHERS' DAY 2026</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 15mm 20mm 15mm;

            @bottom-left {
                content: "DepEd Davao del Sur | Generated On: {{ now('Asia/Manila')->format('F d, Y h:i A') }} PHT";
                font-size: 8pt;
                font-family: Arial, sans-serif;
                color: #555555;
            }

            @bottom-right {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 9pt;
                font-family: "Times New Roman", Times, serif;
                font-style: italic;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000000;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .doc-title-container {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 15px;
            border-bottom: 2px solid #000000;
            padding-bottom: 8px;
        }

        .doc-title {
            font-family: Arial, sans-serif;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .doc-subtitle {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            font-weight: bold;
            margin-top: 3px;
            text-transform: uppercase;
            color: #333333;
        }

        /* Standard Table Styling */
        table.gov-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.gov-table th,
        table.gov-table td {
            border: 1px solid #000000;
            padding: 6px 8px;
            vertical-align: middle;
        }

        table.gov-table th {
            background-color: #e6e6e6;
            font-family: Arial, sans-serif;
            font-size: 9.5pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        table.gov-table td {
            font-size: 9.5pt;
        }

        table.gov-table tr {
            page-break-inside: avoid;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Signatory / Approval Block */
        .signatory-container {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .signatory-table {
            width: 100%;
            border-collapse: collapse;
            border: none !important;
        }

        .signatory-table td {
            border: none !important;
            vertical-align: top;
            width: 33.33%;
            padding: 0 15px;
        }

        .sign-title {
            font-size: 9.5pt;
            margin-bottom: 45px;
            /* Signature line height space */
        }

        .sign-name {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            margin-bottom: 2px;
        }

        .sign-position {
            font-size: 9pt;
            color: #222222;
        }

        /* Print Rule Overrides */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* Forces header repeating behavior on browsers & PDF engines */
            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <table class="gov-table">
        <!-- THEAD REPEATS ON EVERY PRINTED PAGE -->
        <thead>
            <!-- Header Block -->
            <tr style="border: none; background: transparent;">
                <th colspan="6"
                    style="border: none; background: transparent; padding: 0 0 10px 0; font-weight: normal;">
                    <div class="doc-title-container">
                        <h1 class="doc-title">OFFICIAL LIST OF RAFFLE WINNERS</h1>
                        <div class="doc-subtitle">World Teachers' Day Celebration 2026</div>
                    </div>
                </th>
            </tr>

            <!-- Table Column Headers -->
            <tr>
                <th style="width: 4%;" class="text-center">#</th>
                <th style="width: 20%;">Prize / Award</th>
                <th style="width: 23%;">Full Name of Winner</th>
                <th style="width: 20%;">District / Division</th>
                <th style="width: 21%;">School / Office</th>
                <th style="width: 12%;" class="text-center">Signature / Acknowledgment</th>
            </tr>
        </thead>

        <!-- DATA ROWS -->
        <tbody>
            @forelse ($winners as $index => $winner)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-bold">{{ $winner->prize->name }}</td>
                    <td><strong>{{ strtoupper($winner->participant->full_name) }}</strong></td>
                    <td>{{ $winner->participant->district_division }}</td>
                    <td>{{ $winner->participant->school_office }}</td>
                    <td></td> <!-- Left blank intentionally for physical signature -->
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">
                        <em>No official winners recorded for this event.</em>
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>



    <script>
        // Automatic cleanup script post-print
        window.onafterprint = function() {
            if (window.opener) {
                window.opener.postMessage({
                    action: "unselectCheckboxes"
                }, "*");
            }
            window.close();
        };
    </script>

</body>

</html>
