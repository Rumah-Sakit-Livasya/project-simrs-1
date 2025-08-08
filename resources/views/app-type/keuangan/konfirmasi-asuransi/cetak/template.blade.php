    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>@yield('title', 'Laporan')</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 10pt;
                margin: 20px;
            }

            .tools {
                margin-bottom: 20px;
                display: flex;
                gap: 5px;
            }

            .tools button,
            .tools a {
                background-color: #f0f0f0;
                color: #000;
                padding: 5px 15px;
                border: 1px solid #ccc;
                text-decoration: none;
                cursor: pointer;
            }

            .report-divider {
                /* border-top: 1px solid #000; */
                margin: 10px 0;
            }

            @media print {
                .tools {
                    display: none;
                }

                body {
                    margin: 0;
                    padding: 15px;
                }

                textarea {
                    display: none !important;
                }

                #keterangan-text {
                    display: block !important;
                    white-space: pre-wrap;
                }
            }
        </style>

        @yield('style')
    </head>

    <body>
        <div class="tools">
            <button onclick="window.print()">Print</button>
            <button onclick="window.close()">Close</button>
        </div>

        <div class="report-divider"></div>

        @yield('content')

        @yield('script')
    </body>

    </html>
