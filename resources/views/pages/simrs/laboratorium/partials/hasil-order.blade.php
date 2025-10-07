<!DOCTYPE html>
<html>

<head>
    <title>Print</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <style>
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
            src: url('opensans.ttf');
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

            &.bdr {
                border-bottom: 2px solid #CCCCCC;
            }

            span {
                display: block;
                margin: 5px 0;

                &.rgt {
                    border: 1px solid #CCCCCC;
                    float: right;

                    span {
                        font-size: 1em !important;
                        display: inline-block;
                        margin: 0;
                        padding: 8px 10px;

                        &.til {
                            background: #CCCCCC;
                        }
                    }
                }
            }

            &.ctr {
                text-align: center;
            }

            &.nul {
                padding: 0;
                margin: 0;
            }
        }

        h1 {
            font-size: 3em;

            span {
                font-size: .4em;
            }
        }

        h2 {
            font-size: 2em;

            span {
                font-size: .5em;
            }
        }

        h3 {
            font-size: 1.5em;

            span {
                font-size: .6em;
            }
        }

        #functions {
            background: #EDEDED;
            border-bottom: 1px solid #CCCCCC;
            box-shadow: 0 0 2px rgba(0, 0, 0, .5);
            padding: 10px 5px;
            position: relative;
            overflow: hidden;
            top: 0;
            width: 100%;

            ul {
                li {
                    display: inline-block;
                    margin: 2px 5px 2px 0;

                    a {
                        background: #FFFFFF;
                        text-decoration: none;
                        padding: 5px 10px;
                        border: 1px solid #CCCCCC;
                        box-shadow: 0 0 2px rgba(0, 0, 0, .2);
                        color: #000000;
                        /*text-shadow: 0 0 2px rgba(0,0,0,.3); */
                    }
                }
            }
        }

        #previews {
            margin: 20px;
            margin-top: 50px;
            padding: 0;
            overflow: hidden;
            clear: both;

            &.bdr {
                border: 1px solid #CCCCCC;
            }
        }

        #lhd {
            border-bottom: 3px double #CCCCCC;
            clear: both;
            overflow: hidden;
            margin: 1px 0;

            .lgo {
                float: left;
                width: 45px;
                height: 75px;
                margin: 5px 10px 5px 0;

                img {
                    height: 50px;
                }
            }

            .ttl {
                float: left;
                line-height: 1.2em;
                padding: 15px 0 10px 0;

                span {
                    display: block;

                    &.nme {
                        font-weight: bold;
                        font-size: 2em;
                        margin-bottom: 5px;
                    }
                }
            }

            .lgo_persen {
                float: left;
                width: 8%;
                height: 75px;
                margin: 5px 10px 5px 0;

                img {
                    height: 70px;
                }
            }

            .ttl_persen {
                float: left;
                width: 10%;
                line-height: 1.2em;
                padding: 15px 0 10px 0;
                text-align: center;

                span {
                    display: block;

                    &.nme {
                        font-weight: bold;
                        font-size: 1.4em;
                        margin-bottom: 5px;
                    }
                }
            }
        }

        #hhr {
            width: 50%;

            &.ful {
                width: 100%;
            }

            &.pad1 {
                padding: 0 20px;
            }

            &.hlf {
                float: left;
            }

            &.hrt {
                float: right;
            }

            ul {
                margin: 10px 0;
                padding: 0;

                li {
                    margin: 5px 0;

                    div {
                        display: inline-block;
                    }

                    span {
                        display: inline-block;
                        width: 120px;
                        vertical-align: text-top;
                    }
                }
            }
        }

        #ftr {
            overflow: hidden;
            margin: 10px 0;

            &.tp1 {
                .wpr {
                    width: 100%;
                }
            }

            &.tp2 {
                .wpr {
                    width: 50%;
                }
            }

            &.tp3 {
                .wpr {
                    width: 33.33333333333333%;
                }
            }

            &.tp4 {
                .wpr {
                    width: 25%;
                }
            }

            &.tp5 {
                .wpr {
                    width: 20%;
                }
            }

            &.tp6 {
                .wpr {
                    width: 16.66666666666667%;
                }
            }

            .wpr {
                float: left;

                div {
                    float: none;
                    padding: 10px;
                    margin: 0 5px;

                    &.dot {
                        /* This For Sample */
                        text-align: center;
                        text-transform: uppercase;
                        border: 1px dotted #ccc;
                    }

                    &.npm {
                        margin: 0;
                        padding: 0;
                    }

                    p {
                        display: block;
                        margin: 10px 0;
                        line-height: 1.4em;
                    }
                }

                &.rt {
                    float: right;
                }
            }
        }

        table {
            margin: 0;
            padding: 0;
            border-collapse: collapse;

            table {
                font-size: 1em !important;
            }

            &.bdr1 {
                border: 1px solid #CCCCCC !important;

                th {
                    border: 1px solid #CCCCCC !important;
                }

                td {
                    border: 1px solid #CCCCCC !important;
                }
            }

            &.bdr2 {
                border: none !important;

                tbody {
                    border-top: 1px solid #CCCCCC !important;
                }

                th {
                    border: none !important;
                    border: 1px solid #CCCCCC !important;

                    &:first-child {
                        border-left: none !important;
                    }

                    &:last-child {
                        border-right: none !important;
                    }
                }

                td {
                    border: none !important;
                    border-bottom: 1px solid #CCCCCC !important;
                }
            }

            &.bdr3 {
                border: none !important;

                tbody {
                    border-top: 1px solid #CCCCCC !important;
                }

                tr:last-child {
                    border-bottom: 1px solid #CCCCCC !important;
                }

                th {
                    border: none !important;
                    border: 1px solid #CCCCCC !important;

                    &:first-child {
                        border-left: none !important;
                    }

                    &:last-child {
                        border-right: none !important;
                    }
                }

                td {
                    border: none !important;
                    border-right: 1px solid #CCCCCC !important;

                    &:first-child {
                        border-left: none !important;
                    }

                    &:last-child {
                        border-right: none !important;
                    }
                }
            }

            &.bdr4 {
                border: none !important;

                tbody {
                    border-top: 1px solid #CCCCCC !important;
                }

                tr:last-child {
                    border-bottom: 1px solid #CCCCCC !important;
                }

                th {
                    border: none !important;
                    border: 1px solid #CCCCCC !important;

                    &:first-child {
                        border-left: none !important;
                    }

                    &:last-child {
                        border-right: none !important;
                    }
                }

                td {
                    border: none !important;
                    border-right: 1px solid #CCCCCC !important;
                    border-bottom: 1px solid #CCCCCC !important;

                    &:first-child {
                        border-left: none !important;
                    }

                    &:last-child {
                        border-right: none !important;
                    }
                }
            }

            &.pad {
                th {
                    padding: 8px;
                }

                td {
                    padding: 4px;
                }
            }

            &.sml {
                font-size: .85em;
            }

            th {
                background: #D9D9D9;
                padding: 0;
                vertical-align: middle;
            }

            td {
                padding: 0;
                vertical-align: text-top;
                line-height: 1.2em;

                span {
                    color: #414141;
                    display: block;
                    font-size: .8em;
                }
            }
        }

        .watermark {
            background-image: url(../images/logocx.png);
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
    <script src="{{ asset('js/jquery.js') }}"></script>
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
            src: url('/font/opensans.ttf');
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
            background-image: url(/img/logo.png);
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
    <style type="text/css">
        @media print {
            input#pj {
                border: none;
            }

        }
    </style>
    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="index-fancy.html" onclick="window.close()">Close</a></li>
        </ul>
    </div>
    <!-- E: Functions -->

    <!-- B: Print View -->
    <div id="previews" style="margin-bottom:0px; margin-top:-10px">

        <div style="float:left; width:40%; border-bottom:none">
            <div class="lgo_persen" style=" width:100%; height:120px;"><img src="/img/logo.png"
                    style="height:60%; margin-top:3%">
            </div>
        </div>

        <div
            style="width:50%; text-align:right; border-left:1px solid #666; height:100px; float:right; margin-right:2%; padding-top:2%">
            <span style="font-size:.8em; font-family:'Arial Black', Gadget, sans-serif; line-height:1.3em">
                Rumah Sakit Livasya Kab. Majalengka<br>
                Jawa Barat - Indonesia<br>
                Telp : 081211151300<br>
                Fax : <br>
                Email : rslivasya@gmail.com<br>
                https://livasya.com/<br>
            </span>
        </div>

        <div style="margin-bottom: 3cm;"></div>
        <h2 class="bdr">
            <span class="rgt"><span class="til">NO Order #</span><span>{{ $order->no_order }}</span></span>
            Hasil Laboratorium
        </h2>

        @if ($order->registration)
            <div id="hhr" class="hlf">
                <ul style="line-height:1.5em">
                    <li><span>No RM / No Registrasi</span>: {{ $order->registration->patient->medical_record_number }} /
                        {{ $order->registration->registration_number }}</li>
                    <li><span>Nama </span>: {{ $order->registration->patient->name }}</li>
                    <li><span>Jenis Kelamin</span>: {{ $order->registration->patient->gender }}</li>
                    <li><span>Tgl Lahir / Umur</span>: {{ $order->registration->patient->date_of_birth }} /
                        {{ displayAge($order->registration->patient->date_of_birth) }}</li>
                    <li style="width:100%;">
                        <span style="float:left;">Alamat</span><span style="float:left; width:5px">:</span>
                        <div style="float:; width:50%">{{ $order->registration->patient->address }} </div>
                        <div style="clear:both"></div>
                    </li>
                    <li><span>No telp / Hp</span>: {{ $order->registration->patient->mobile_phone_number }}</li>
                    <li><span>Penjamin</span>: {{ $order->registration->penjamin->nama_perusahaan }}</li>
                </ul>
            </div>
            <div id="hhr" class="hrt">
                <ul style="line-height:1.5em">
                    <li><span>Tgl Order</span>: {{ $order->order_date }}</li>
                    <li><span>Dokter Perujuk</span>: {{ $order->registration->doctor->employee->fullname }}</li>
                    <li><span>Poly/Ruang</span>: {{ $order->registration->poliklinik }}</li>
                    <li><span>Dokter Penanggung Jawab</span>: {{ $order->doctor->employee->fullname }}</li>
                    <li><span>Tanggal / jam Sampel</span>: {{ $order->inspection_date }}</li>
                    <li><span>Tanggal / jam Hasil</span>: {{ $order->result_date }}</li>
                    <li><span>Analis</span>: {{ $order->diagnosa_klinis }}</li>
                </ul>
            </div>
        @else
            {{-- OTC --}}
            <div id="hhr" class="hlf">
                <ul style="line-height:1.5em">
                    <li><span>No RM / No Registrasi</span>: OTC
                        /
                        {{ $order->registration_otc->registration_number }}</li>
                    <li><span>Nama </span>: {{ $order->registration_otc->nama_pasien }}</li>
                    <li><span>Jenis Kelamin</span>: {{ $order->registration_otc->jenis_kelamin }}</li>
                    <li><span>Tgl Lahir / Umur</span>: {{ $order->registration_otc->date_of_birth }} /
                        {{ displayAge($order->registration_otc->date_of_birth) }}</li>
                    <li style="width:100%;">
                        <span style="float:left;">Alamat</span><span style="float:left; width:5px">:</span>
                        <div style="float:; width:50%">{{ $order->registration_otc->alamat }} </div>
                        <div style="clear:both"></div>
                    </li>
                    <li><span>No telp / Hp</span>: {{ $order->registration_otc->no_telp }}</li>
                    <li><span>Penjamin</span>: Pasien Umum</li>
                </ul>
            </div>
            <div id="hhr" class="hrt">
                <ul style="line-height:1.5em">
                    <li><span>Tgl Order</span>: {{ $order->order_date }}</li>
                    {{-- <li><span>Dokter Perujuk</span>: {{ $order->registration_otc->doctor->employee->fullname }}</li> --}}
                    <li><span>Poly/Ruang</span>: {{ $order->registration_otc->poly_ruang }}</li>
                    <li><span>Dokter Penanggung Jawab</span>: {{ $order->doctor->employee->fullname }}</li>
                    <li><span>Tanggal / jam Sampel</span>: {{ $order->inspection_date }}</li>
                    <li><span>Tanggal / jam Hasil</span>: {{ $order->result_date }}</li>
                    <li><span>Analis</span>: {{ $order->diagnosa_klinis }}</li>
                </ul>
            </div>
        @endif
        <table width="100%" class="bdr2 pad">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Pemeriksaan</th>
                    <th width="10%">Hasil</th>
                    <th width="10%">Nilai Rujukan</th>
                    <th width="15%">Satuan</th>
                    <th width="15%">Metode</th>
                    <th width="15%">Keterangan</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($order->order_parameter_laboratorium as $parameter)
                    <tr>
                        <td align="center">{{ $loop->iteration }}</td>
                        <td>{{ $parameter->parameter_laboratorium->parameter }}</td>
                        @php
                            $is_kritis = false;
                            $is_abnormal = false;
                            $nilai_normal_parameter = null;

                            if ($order->registration) {
                                $dob = $order->registration->patient->date_of_birth;
                                $jenis_kelamin = $order->registration->patient->gender;
                            } else {
                                // otc
                                $dob = $order->registration_otc->date_of_birth;
                                $jenis_kelamin = $order->registration_otc->jenis_kelamin;
                            }

                            foreach ($nilai_normals as $nilai_normal) {
                                if ($nilai_normal->parameter_laboratorium_id == $parameter->parameter_laboratorium_id) {
                                    if (
                                        isWithinAgeRange($dob, $nilai_normal->dari_umur, $nilai_normal->sampai_umur) &&
                                        ($nilai_normal->jenis_kelamin == $jenis_kelamin ||
                                            $nilai_normal->jenis_kelamin == 'Semuanya')
                                    ) {
                                        $nilai_normal_parameter = $nilai_normal;
                                        break;
                                    }
                                }
                            }

                            if (
                                $nilai_normal_parameter != null &&
                                $parameter->parameter_laboratorium->tipe_hasil == 'Angka'
                            ) {
                                // check if abnormal
                                if (
                                    $parameter->hasil < $nilai_normal_parameter->min ||
                                    $parameter->hasil > $nilai_normal_parameter->max
                                ) {
                                    $is_abnormal = true;
                                    // check if critical
                                    if (
                                        $parameter->hasil < $nilai_normal_parameter->min_kritis ||
                                        $parameter->hasil > $nilai_normal_parameter->max_kritis
                                    ) {
                                        $is_kritis = true;
                                    }
                                }
                            }
                        @endphp

                        <td align="center">
                            @if ($is_abnormal && !$is_kritis)
                                <p style="font-weight: bold;"> {{ $parameter->hasil }}*</p>
                            @elseif ($is_kritis)
                                <p style="color:#FF0000; font-weight: bold;"> {{ $parameter->hasil }}**</p>
                            @else
                                <p>{{ $parameter->hasil }}</p>
                            @endif
                        </td>
                        <td align="center">
                            @if ($nilai_normal_parameter)
                                @if ($parameter->parameter_laboratorium->tipe_hasil == 'Angka')
                                    {{ $nilai_normal_parameter->min }} - {{ $nilai_normal_parameter->max }}
                                @else
                                    {{ $nilai_normal_parameter->nilai_normal }}
                                @endif
                            @endif
                        </td>
                        <td align="center">{{ $parameter->parameter_laboratorium->satuan }}</td>
                        <td align="center">{{ $parameter->parameter_laboratorium->metode }}</td>
                        <td align="center">{{ $parameter->catatan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="ftr" class="tp1">
            <div class="wpr">
                <div class="npm">
                    <p><b>Tanda *</b> : Menunjukkan hasil dibawah atau diatas nilai rujukan</p>
                    <p><b style="color:#FF0000;">Tanda **</b> : Menunjukkan nilai kritis</p>
                    <p>
                        Catatan :
                    </p>
                </div>
            </div>
        </div>

        <div id="ftr" class="tp1" style="float:left; margin-left: 20px;">
            <div class="wpr">
                <div class="npm">
                    <p align="center">
                        Penanggung Jawab Laboratorium,
                        <br>
                        <img src="/img/ttd-dr-dillar.png" style="width: 2.65cm; height: 2.6cm">
                        <br>
                        {{ $order->doctor->employee->fullname }}
                    </p>

                </div>
            </div>
        </div>

        <div id="ftr" class="tp1" style="float:right; margin-right: 20px;">
            <div class="wpr">
                <div class="npm">
                    <p align="center">
                        User {{ auth()->user()->employee->fullname }}, <br>Tanggal Print : {{ date('d-m-Y h:i') }}
                        <!--
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <input class="form-control" style="text-align:center; font-weight: normal; font-size: 1em;" type="text" name="pj" id="pj" value="Adib Mangaraja" />
-->
                    </p>
                    <!--			<span  style="margin:1%">Tanggal Print : 14-04-2025</span> -->
                </div>
            </div>
        </div>
        <div style="clear:both"></div>
        <div {{-- style="width:100%; height:30px; background:url(http://192.168.1.253/testing/include/image_assets/foot.png)"> --}} </div>
        </div>


</body>

</html>
