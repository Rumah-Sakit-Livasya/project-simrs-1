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
    <script language="javascript">
        function printit() {
            sahana = document.getElementById("sahana").value;
            document.getElementById("saha").innerHTML = sahana;
            window.print();
        }
    </script>
    <div id="functions">
        <ul>
            <li><a href="#" onclick="printit();">Print</a></li>
            <li><a href="index-fancy.html" onclick="window.close()">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews">

        <div id="lhd">
            <div class="lgo"><img src="{{ asset('/img/logo.png') }}" width="65"></div>
            <div class="ttl" style="margin-left:28px;">
                <span class="nme">Rumah Sakit Livasya</span>
                <span>Jl. Raya Timur III Dawuan No. 875 Kab. Majalengka, Kab. Majalengka - Jawa Barat</span>
                <span>Phone. 081211151300</span>
            </div>
        </div>

        <h2 class="bdr">
            <span class="rgt"><span class="til">NO PO #</span><span>{{ $po->kode_po }}</span></span>
            Purchase Order
            <span>Tanggal : {{ tgl($po->tanggal_po) }}</span>
        </h2>

        <div id="hhr" class="hlf">
            <ul>
                <li><span>Supplier</span>: {{ $po->supplier->nama }}</li>
                <li><span>Contact Person</span>: {{ $po->supplier->contact_person }}</li>
                <li><span>Alamat</span>: {{ $po->supplier->alamat }}</li>
            </ul>
        </div>
        <div id="hhr" class="hrt">
            <ul>
                @php
                    if ($po->top == 'COD') {
                        $top = 'Cash On Delivery';
                    } else {
                        // top + tipe_top
                        // top == between 7HARI - 45HARI
                        // tipe_top == SETELAH_TUKAR_FAKTUR / SETELAH_TERIMA_BARANG

                        // first process top
                        // split between the number and the "HARI"
                        // and ucfirst the "HARI"
                        $top = str_replace('HARI', '', $po->top); // remove "HARI" from top
                        $top = $top . ' Hari'; // add "HARI" back to the top variable

                        if ($po->tipe_top == 'SETELAH_TUKAR_FAKTUR') {
                            $top = $top . ' Setelah Tukar Faktur';
                        } else {
                            $top = $top . ' Setelah Terima Barang';
                        }
                    }
                @endphp
                <li><span>TOP</span>: {{ $top }}</li>
                <li><span>Tgl Kirim</span>: {{ $po->tanggal_kirim ? tgl($po->tanggal_kirim) : '' }}</li>
                <li><span>PIC Penerima</span>: {{ $po->pic_terima }}</li>
            </ul>
        </div>
        <table width="100%" class="bdr2 pad">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th>NAMA ITEM</th>
                    <th width="10%">UOM</th>
                    <th width="10%">QTY</th>
                    <th width="15%">HARGA</th>
                    <th width="15%">DISC</th>
                    <th width="20%">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($po->items as $item)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->unit_barang }}</td>
                        <td align="right">{{ $item->qty }}</td>
                        <td align="right">{{ rp($item->barang->hna) }}</td>
                        <td align="right">{{ ($item->discount_nominal / ($item->subtotal + $item->discount_nominal)) * 100 }}%</td>
                        <td align="right">{{ rp($item->subtotal) }}</td>
                    </tr>
                @endforeach

                @php
                    $total = $item->sum('subtotal');
                    $ppn = ($total * $po->ppn) / 100;
                    $disc_sum = $item->sum('discount_nominal');
                @endphp

                <tr>
                    <td align="right" colspan="6"><strong>Total</strong></td>
                    <td align="right">{{ rp($total) }}</td>
                </tr>
                <tr>
                    <td align="right" colspan="6"><strong>Total Diskon</strong></td>
                    <td align="right">{{ rp($disc_sum) }}</td>
                </tr>
                <tr>
                    <td align="right" colspan="6"><strong>PPN</strong></td>
                    <td align="right">{{ rp($ppn) }}</td>
                </tr>
                <tr>
                    <td align="right" colspan="6"><strong>Grand Total</strong></td>
                    <td align="right">{{ rp($po->nominal) }}</td>
                </tr>
            </tbody>
        </table>

        <div id="ftr" class="tp1">
            <div class="wpr">
                <div class="npm">
                    <p>
                        Catatan : {{ $po->keterangan }}
                    </p>
                </div>
            </div>
        </div>

        <div id="ftr" class="tp2">
            <div class="wpr">
                <div class="npm" style="display: none;">
                    <p align="center">
                        Apoteker,
                        <br>
                        <br>
                        <br>
                        <br>
                        <!--<u>Apt. Lia Vallini, S.Farm.</u><br />503/65/DPMPTSP/VIII/2023-->
                        <u>apt. Nurani Eka Gumilang, S.Farm.</u>
                    </p>
                </div>
            </div>
            <div class="wpr">
                <div class="npm">
                    <p align="center">
                        Ka.Sub.Bag. Logistik,
                        <br>
                        <br>
                        <br>
                        <br>
                        <span id="saha"><input type="text" id="sahana" style="width:50%"></span><br>
                    </p>
                </div>
            </div>
            <div class="wpr .rt">
                <div class="npm">
                    <p align="center">
                        CEO,
                        <br>
                        <br>
                        <br>
                        <br>
                        <u>Apt. Lia Vallini, S.Farm.</u><br>503/65/DPMPTSP/VIII/2023
                        <!--Iing Syapei Sudjono-->
                    </p>
                </div>
            </div>
        </div>


    </div>


</body>

</html>
