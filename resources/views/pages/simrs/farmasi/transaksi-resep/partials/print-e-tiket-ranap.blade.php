<html>

<head>
    <title>Print</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet/less" type="text/css" media="all" href="{{ asset('css/print.css') }}">
    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
    <style type="text/css" media="all" id="less:testing-include-styles-print">
        /*  Document Reset */
        @charset "utf-8";

        /*html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td { margin: 0; padding: 0; border: 0; outline: 0; font-size: 100%; vertical-align: baseline; background: transparent; }*/
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        font,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tfoot,
        thead,
        th {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        :focus {
            outline: 0;
        }

        ins {
            text-decoration: none;
        }

        del {
            text-decoration: line-through;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        @font-face {
            font-family: 'Open Sans';
            src: url({{ asset('font/opensans.ttf') }});
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Open Sans';
            /*Arial, Helvetica, sans-serif;*/

            font-size: .75em;
        }

        h1,
        h2,
        h3 {
            margin: 5px 0;
            padding: 5px 0;
            font-weight: normal;
            clear: both;
            overflow: hidden;
        }

        h1.bdr,
        h2.bdr,
        h3.bdr {
            border-bottom: 2px solid #CCCCCC;
        }

        h1 span,
        h2 span,
        h3 span {
            display: block;
            margin: 5px 0;
        }

        h1 span.rgt,
        h2 span.rgt,
        h3 span.rgt {
            border: 1px solid #CCCCCC;
            float: right;
        }

        h1 span.rgt span,
        h2 span.rgt span,
        h3 span.rgt span {
            font-size: 1em !important;
            display: inline-block;
            margin: 0;
            padding: 8px 10px;
        }

        h1 span.rgt span.til,
        h2 span.rgt span.til,
        h3 span.rgt span.til {
            background: #CCCCCC;
        }

        h1.ctr,
        h2.ctr,
        h3.ctr {
            text-align: center;
        }

        h1.nul,
        h2.nul,
        h3.nul {
            padding: 0;
            margin: 0;
        }

        h1 {
            font-size: 3em;
        }

        h1 span {
            font-size: .4em;
        }

        h2 {
            font-size: 2em;
        }

        h2 span {
            font-size: .5em;
        }

        h3 {
            font-size: 1.5em;
        }

        h3 span {
            font-size: .6em;
        }

        #functions {
            background: #EDEDED;
            border-bottom: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
            padding: 10px 5px;
            position: relative;
            overflow: hidden;
            top: 0;
            width: 100%;
        }

        #functions ul li {
            display: inline-block;
            margin: 2px 5px 2px 0;
        }

        #functions ul li a {
            background: #FFFFFF;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
            color: #000000;
            /*text-shadow: 0 0 2px rgba(0,0,0,.3); */

        }

        #previews {
            margin: 20px;
            margin-top: 50px;
            padding: 0;
            overflow: hidden;
            clear: both;
        }

        #previews.bdr {
            border: 1px solid #CCCCCC;
        }

        #lhd {
            border-bottom: 3px double #CCCCCC;
            clear: both;
            overflow: hidden;
            margin: 1px 0;
        }

        #lhd .lgo {
            float: left;
            width: 45px;
            height: 75px;
            margin: 5px 10px 5px 0;
        }

        #lhd .lgo img {
            height: 50px;
        }

        #lhd .ttl {
            float: left;
            line-height: 1.2em;
            padding: 15px 0 10px 0;
        }

        #lhd .ttl span {
            display: block;
        }

        #lhd .ttl span.nme {
            font-weight: bold;
            font-size: 2em;
            margin-bottom: 5px;
        }

        #lhd .lgo_persen {
            float: left;
            width: 8%;
            height: 75px;
            margin: 5px 10px 5px 0;
        }

        #lhd .lgo_persen img {
            height: 70px;
        }

        #lhd .ttl_persen {
            float: left;
            width: 10%;
            line-height: 1.2em;
            padding: 15px 0 10px 0;
            text-align: center;
        }

        #lhd .ttl_persen span {
            display: block;
        }

        #lhd .ttl_persen span.nme {
            font-weight: bold;
            font-size: 1.4em;
            margin-bottom: 5px;
        }

        #hhr {
            width: 50%;
        }

        #hhr.ful {
            width: 100%;
        }

        #hhr.pad1 {
            padding: 0 20px;
        }

        #hhr.hlf {
            float: left;
        }

        #hhr.hrt {
            float: right;
        }

        #hhr ul {
            margin: 10px 0;
            padding: 0;
        }

        #hhr ul li {
            margin: 5px 0;
        }

        #hhr ul li div {
            display: inline-block;
        }

        #hhr ul li span {
            display: inline-block;
            width: 120px;
            vertical-align: text-top;
        }

        #ftr {
            overflow: hidden;
            margin: 10px 0;
        }

        #ftr.tp1 .wpr {
            width: 100%;
        }

        #ftr.tp2 .wpr {
            width: 50%;
        }

        #ftr.tp3 .wpr {
            width: 33.33333333333333%;
        }

        #ftr.tp4 .wpr {
            width: 25%;
        }

        #ftr.tp5 .wpr {
            width: 20%;
        }

        #ftr.tp6 .wpr {
            width: 16.66666666666667%;
        }

        #ftr .wpr {
            float: left;
        }

        #ftr .wpr div {
            float: none;
            padding: 10px;
            margin: 0 5px;
        }

        #ftr .wpr div.dot {
            /* This For Sample */

            text-align: center;
            text-transform: uppercase;
            border: 1px dotted #ccc;
        }

        #ftr .wpr div.npm {
            margin: 0;
            padding: 0;
        }

        #ftr .wpr div p {
            display: block;
            margin: 10px 0;
            line-height: 1.4em;
        }

        #ftr .wpr.rt {
            float: right;
        }

        table {
            margin: 0;
            padding: 0;
            border-collapse: collapse;
        }

        table table {
            font-size: 1em !important;
        }

        table.bdr1 {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr1 th {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr1 td {
            border: 1px solid #CCCCCC !important;
        }

        table.bdr2 {
            border: none !important;
        }

        table.bdr2 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr2 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr2 th:first-child {
            border-left: none !important;
        }

        table.bdr2 th:last-child {
            border-right: none !important;
        }

        table.bdr2 td {
            border: none !important;
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr3 {
            border: none !important;
        }

        table.bdr3 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr3 tr:last-child {
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr3 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr3 th:first-child {
            border-left: none !important;
        }

        table.bdr3 th:last-child {
            border-right: none !important;
        }

        table.bdr3 td {
            border: none !important;
            border-right: 1px solid #CCCCCC !important;
        }

        table.bdr3 td:first-child {
            border-left: none !important;
        }

        table.bdr3 td:last-child {
            border-right: none !important;
        }

        table.bdr4 {
            border: none !important;
        }

        table.bdr4 tbody {
            border-top: 1px solid #CCCCCC !important;
        }

        table.bdr4 tr:last-child {
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr4 th {
            border: none !important;
            border: 1px solid #CCCCCC !important;
        }

        table.bdr4 th:first-child {
            border-left: none !important;
        }

        table.bdr4 th:last-child {
            border-right: none !important;
        }

        table.bdr4 td {
            border: none !important;
            border-right: 1px solid #CCCCCC !important;
            border-bottom: 1px solid #CCCCCC !important;
        }

        table.bdr4 td:first-child {
            border-left: none !important;
        }

        table.bdr4 td:last-child {
            border-right: none !important;
        }

        table.pad th {
            padding: 8px;
        }

        table.pad td {
            padding: 4px;
        }

        table.sml {
            font-size: .85em;
        }

        table th {
            background: #D9D9D9;
            padding: 0;
            vertical-align: middle;
        }

        table td {
            padding: 0;
            vertical-align: text-top;
            line-height: 1.2em;
        }

        table td span {
            color: #414141;
            display: block;
            font-size: .8em;
        }

        .watermark {
            background-image: url(http://192.168.1.253/testing/include/styles/../images/logocx.png);
            background-position: center center;
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.1;
            position: absolute;
            min-height: 80%;
            min-width: 80%;
        }

        @media print {
            #functions {
                display: none;
            }

            #preview {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <!-- start printEtiket.html -->
    <style>
        .etiket {
            margin-top: 20px !important;
        }

        .etiket:first-child {
            margin-top: 0 !important;
        }

        /* eTicket */

        .etiket {
            font-size: 1.7em;
            clear: both;
            font-family: Arial, Helvetica, sans-serif;
            border: 0px dashed #000;
            padding: 0;
            page-break-after: always;
        }

        .etiket:last-child {
            page-break-after: avoid;
        }

        .etiket .patient {
            font-size: .5em;
            font-weight: bold;
            border-bottom: 1px double #000;
            padding: 2px 0;
            margin-bottom: 2px;
        }

        .etiket .number {
            font-size: .5em;
            color: #000;
        }

        .etiket .number span {
            float: right;
        }

        .etiket .status {
            font-size: .4em;
            color: #000;
            font-weight: bold;
            line-height: 12px;
        }

        .etiket .prescriptionnum {
            font-size: 1em;
            border-bottom: 1px double #000;
            padding: 2px 0;
            font-weight: bold;
        }

        .etiket .prescriptionnum span {
            float: right;
            font-weight: normal;
        }

        .etiket .medicine {
            font-weight: bold;
            font-size: .4em;
            margin-bottom: 2px;
            padding: 3px 0;
            border-bottom: 1px double #000;
            margin-top: 1px;
        }

        .etiket .medicine span {
            float: right;
        }

        .etiket .usage {
            border: 0px solid #000;
            margin-bottom: 2px;
            padding: 2px;
            background: #fff;
            font-size: .4em;
        }

        .etiket {
            width: 4.0cm;
            height: 4.2cm;
            clear: both;
            font-family: Arial, Helvetica, sans-serif;
            border: 0px dashed #000;
            padding: 0px;
            margin-bottom: 2px;
            margin-left: 38px;
            page-break-after: always;
        }

        .etiket:last-child {
            page-break-after: avoid;
        }

        .etiket .patient {
            font-size: .5em;
            font-weight: bold;
            border-bottom: 1px double #000;
            padding: 2px 0;
            margin-bottom: 2px;
        }

        .etiket .patient span {
            float: right;
            font-size: .9em;
            padding: 1px 0;
            margin-left: 5px;
        }

        .etiket .status {
            font-size: .4em;
            font-weight: bold;
            margin-bottom: 1px;
            color: #000;
        }

        .etiket .prescriptionnum {
            font-size: .3em;
            border-bottom: 1px double #000;
            padding: 2px 0;
            font-weight: bold;
        }

        .etiket .prescriptionnum span {
            float: right;
            font-weight: bold;
        }

        .etiket .medicine {
            font-weight: bold;
            font-size: .4em;
            margin-bottom: 2px;
            padding: 3px 0;
            border-bottom: 1px double #000;
            margin-top: 1px;
        }

        .etiket .medicine span {
            float: right;
        }

        .etiket .usage {
            border: 0px solid #000;
            margin-bottom: 1.5px;
            padding: 1.5px;
            background: #fff;
            font-size: .56em;
            font-weight: bold;
        }

        table tr td {
            padding: 2%;
        }
    </style>
    <script language="javascript">
        function printx() {
            document.getElementById('functions').style.display = "none";

            @php
                $count = 0;
            @endphp
            @foreach ($resep->items as $item)
                @if ($item->racikan_id == null)
                    document.getElementById('date_pemberianview{{ ++$count }}').innerHTML = change_date(document
                        .getElementById(
                            'date_pemberian{{ $count }}').value);
                    document.getElementById('date_pemberian{{ $count }}').style.display = 'none';
                    document.getElementById('time_pemberianview{{ $count }}').innerHTML = document.getElementById(
                        'time_pemberian{{ $count }}').value;
                    document.getElementById('time_pemberian{{ $count }}').style.display = 'none';

                    document.getElementById('date_penyiapanview{{ $count }}').innerHTML = change_date(document
                        .getElementById(
                            'date_penyiapan{{ $count }}').value);
                    document.getElementById('date_penyiapan{{ $count }}').style.display = 'none';
                    document.getElementById('time_penyiapanview{{ $count }}').innerHTML = document
                        .getElementById('time_penyiapan{{ $count }}').value;
                    document.getElementById('time_penyiapan{{ $count }}').style.display = 'none';

                    document.getElementById('date_kadaluarsaview{{ $count }}').innerHTML = change_date(document
                        .getElementById(
                            'date_kadaluarsa{{ $count }}').value);
                    document.getElementById('date_kadaluarsa{{ $count }}').style.display = 'none';
                    document.getElementById('time_kadaluarsaview{{ $count }}').innerHTML = document
                        .getElementById('time_kadaluarsa{{ $count }}')
                        .value;
                    document.getElementById('time_kadaluarsa{{ $count }}').style.display = 'none';
                @endif
            @endforeach

            window.print();

            document.getElementById('functions').style.display = "";
            document.getElementById('date_pemberian').style.display = '';
            document.getElementById('date_pemberianview').style.display = 'none';
            document.getElementById('time_pemberian').style.display = '';
            document.getElementById('time_pemberianview').style.display = 'none';

            document.getElementById('date_penyiapan').style.display = '';
            document.getElementById('date_penyiapanview').style.display = 'none';
            document.getElementById('time_penyiapan').style.display = '';
            document.getElementById('time_penyiapanview').style.display = 'none';

            document.getElementById('date_kadaluarsa').style.display = '';
            document.getElementById('date_kadaluarsaview').style.display = 'none';
            document.getElementById('time_kadaluarsa').style.display = '';
            document.getElementById('time_kadaluarsaview').style.display = 'none';
        }

        function change_date(date) {
            if (date === '' || date === null) {
                return '';
            }
            var parts = date.split('-');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            return formattedDate;
        }
    </script>
    <div id="functions">
        <ul>
            <li><a href="#" onclick="printx()">Print</a></li>
            <li><a href="#" onclick="window.close()">Close</a></li>
        </ul>
    </div>
    <div style="clear:both"></div>
    <div id="previews" style="margin-top: 20px;">

        @php
            $isOTC = $resep->tipe_pasien == 'otc';
            $count = 0;
        @endphp

        @foreach ($resep->items as $item)
            @if ($item->racikan_id == null)
                <div class="etiket" style="width:7cm; height: auto; margin-left: 0px;margin-bottom:10px; ">
                    <table width="100%" border="2px" style="font-size:.5em; font-weight:bold">
                        <tbody>
                            <tr>
                                <td colspan="2" style="font-size:1.1em" align="center">NAMA :
                                    {{ $isOTC ? $resep->otc->nama_pasien : $resep->registration->patient->name }}</td>
                            </tr>
                            <tr>
                                <td style="font-size:1.1em">NO RM :
                                    {{ $isOTC ? 'OTC' : $resep->registration->patient->medical_record_number }}</td>
                                <td style="font-size:1.1em">RUANGAN :
                                    {{ $isOTC ? 'OTC' : $resep->registration->patient->bed->room->no_ruang }}</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size:1em" align="center">OBAT :
                                    {{ $item->tipe == 'obat' ? $item->stored->pbi->nama_barang : $item->nama_racikan }}
                                    <br>DALAM : {{ $item->qty }}
                                    {{ $item->tipe == 'obat' ? $item->stored->pbi->unit_barang : 'RACIKAN' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="font-size:1em">
                                    <table style="width:100%">
                                        <tbody>
                                            <tr>
                                                <td style="width: 51%">Tgl&amp;Waktu Pemberian:
                                                    <input type="date" value=""
                                                        name="date_pemberian{{ ++$count }}"
                                                        id="date_pemberian{{ $count }}" style="width: 80%;">
                                                </td>
                                                <td style="width:26%">
                                                    <div style="margin: 0"
                                                        id="date_pemberianview{{ $count }}"></div>
                                                </td>
                                                <td style="width: 15%">Jam:
                                                    <input type="text" value=""
                                                        name="time_pemberian{{ $count }}"
                                                        id="time_pemberian{{ $count }}" style="width: 95%;">
                                                </td>
                                                <td style="width: 8%">
                                                    <div style="margin: 0"
                                                        id="time_pemberianview{{ $count }}"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 51%">Tgl&amp;Waktu Penyiapan:
                                                    <input type="date" value=""
                                                        name="date_penyiapan{{ $count }}"
                                                        id="date_penyiapan{{ $count }}" style="width: 80%;">
                                                </td>
                                                <td style="width:26%">
                                                    <div style="margin: 0"
                                                        id="date_penyiapanview{{ $count }}"></div>
                                                </td>
                                                <td style="width: 15%">Jam:
                                                    <input type="text" value=""
                                                        name="time_penyiapan{{ $count }}"
                                                        id="time_penyiapan{{ $count }}" style="width: 95%;">
                                                </td>
                                                <td style="width: 8%">
                                                    <div style="margin: 0"
                                                        id="time_penyiapanview{{ $count }}"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 51%">Tgl&amp;Waktu Kadaluarsa:
                                                    <input type="date" value=""
                                                        name="date_kadaluarsa{{ $count }}"
                                                        id="date_kadaluarsa{{ $count }}" style="width: 80%;">
                                                </td>
                                                <td style="width:26%">
                                                    <div style="margin: 0"
                                                        id="date_kadaluarsaview{{ $count }}"></div>
                                                </td>
                                                <td style="width: 15%">Jam:
                                                    <input type="text" value=""
                                                        name="time_kadaluarsa{{ $count }}"
                                                        id="time_kadaluarsa{{ $count }}" style="width: 95%;">
                                                </td>
                                                <td style="width: 8%">
                                                    <div style="margin: 0"
                                                        id="time_kadaluarsaview{{ $count }}"></div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>
            @endif
        @endforeach

    </div>

</body>

</html>
