<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raffle Winners</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        h3 {
            text-align: center;
            /* margin-bottom: 5px; */
            font-size: 14pt;
            text-transform: uppercase;
        }



        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        /* Print styles */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
            }

            thead { display: table-header-group; }
            tfoot { display: table-footer-group; }

            /* Page numbering */
            @page {
                @bottom-right {
                    content: "Page " counter(page) " of " counter(pages);
                    font-size: 10pt;
                    font-family: Arial, sans-serif;
                }
            }
        }
    </style>
</head>
<body onload="window.print()">

    <h3>Raffle Winners</h3>
    <span class="title-sub" style="display: block; text-align: center; font-size: 11pt; font-weight: bold; margin-bottom: 20px;">
    National Teachers' Day 2025 Celebration
</span>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Prize</th>
                <th>Full Name</th>
                <th>District / Division</th>
                <th>School / Office</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($winners as $index => $winner)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $winner->prize->name }}</td>
                    <td>{{ $winner->participant->full_name }}</td>
                    <td>{{ $winner->participant->district_division }}</td>
                    <td>{{ $winner->participant->school_office }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        // After printing, close tab and reset checkboxes in parent page
        window.onafterprint = function() {
            if (window.opener) {
                window.opener.postMessage({ action: "unselectCheckboxes" }, "*");
            }
            window.close();
        };
    </script>

</body>
</html>
