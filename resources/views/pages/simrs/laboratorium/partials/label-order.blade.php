<html>

<head>
    <title>Print</title>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
    <script src="{{ asset('js/jquery.js') }}"></script>
    <style type="text/css" media="all">
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
                display: none !important;
            }

            @page {
                size: 1.57in 2.36in;
                margin: 0;
            }

            #blok {
                margin-left: -2rem !important;
            }

            body {
                /* Orientasi Portrait ke Bawah (default) */
                width: 1.57in;
                height: 2.36in;
                overflow: hidden;
                position: absolute;
                top: 0;
                left: 0;
                transform: rotate(90deg);
            }

            @page {
                /* Portrait: Lebar lebih kecil dari tinggi */
                size: 1.57in 2.36in;
                margin: 0;
            }

            #preview {
                margin-top: 20px;
            }
        }
    </style>
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
            src: url('https://cdn.jsdelivr.net/fontsource/fonts/open-sans:vf@latest/latin-wght-normal.woff2');
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

        @media print {
            #functions {
                display: none;
            }

            #preview {
                margin-top: 20px;
            }
        }
    </style>
    @foreach ($order->order_parameter_laboratorium as $parameter)
        <meta hidden class="parameter" id="{{ $parameter->id }}"
            content="{{ $parameter->load(['parameter_laboratorium'])->toJson() }}">
    @endforeach
</head>

<body>
    <style type="text/css">
        table {
            //	margin:1%;
        }

        .head_label {
            font-size: 0.3cm;
        }

        .head_anak {
            font-size: 0.3cm;
        }

        table tr td {
            height: 0.6cm;
            font-size: 0.3cm;
        }

        #blok {
            width: 10cm;
            height: 4cm;
            margin-top: 0.5cm;
            margin-left: 1cm;
        }

        .trigger {
            font-size: 20px;
            color: red !important;
            font-weight: 1000;
        }


        @media print {
            .trigger {
                display: none !important;
            }
        }
    </style>


    <div id="printdiv" style="height:100px; width:100px; display:none"></div>


    <div id="functions">
        <ul>
            <li><a href="#" onclick="window.print();">Print</a></li>
            <li><a href="index-fancy.html" onclick="window.close()">Close</a></li>
        </ul>
    </div>

    <div id="blok">

        <div class="trigger" style="float:right; cursor: pointer;">X</div>
        <table width="98%" border="0">
            @if ($order->registration)
                <tbody>
                    <tr>
                        <td style="width:30%"><span class="head_label">No RM</span></td>
                        <td style="width:70i2
                        %"><span class="head_anak">:
                                <b>{{ $order->registration->patient->medical_record_number }}</b></span></td>
                    </tr>
                    <tr>
                        <td style="width:30%"><span class="head_label">Nama Pasien</span></td>
                        <td style="width:70%"><span class="head_anak">: <b>{{ $order->registration->patient->name }}
                                    ({{ substr($order->registration->patient->gender, 0, 1) }})</b></span></td>
                    </tr>
                    <tr>
                        <td><span class="head_label">Tanggal Lahir</span></td>
                        <td><span class="head_anak">:
                                <b>{{ \Carbon\Carbon::parse($order->registration->patient->date_of_birth)->translatedFormat('d F Y') }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="head_label">Alamat</span></td>
                        <td><span class="head_anak">:
                                <b>{{ $order->registration->patient->address ?? '-' }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="head_label">No. Telpon</span></td>
                        <td><span class="head_anak">:
                                <b>{{ $order->registration->patient->mobile_phone_number ?? '-' }}</b></span>
                        </td>
                    </tr>
                </tbody>
            @else
                {{-- otc --}}
                <tbody>
                    <tr>
                        <td style="width:30%"><span class="head_label">Nama Pasien</span></td>
                        <td style="width:70%"><span class="head_anak">: <b>{{ $order->registration_otc->nama_pasien }}
                                    ({{ substr($order->registration_otc->jenis_kelamin, 0, 1) }})</b></span></td>
                    </tr>
                    <tr>
                        <td><span class="head_label">Tanggal Lahir</span></td>
                        <td><span class="head_anak">:
                                <b>{{ \Carbon\Carbon::parse($order->registration_otc->date_of_birth)->format('d M Y') }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="head_label">Alamat</span></td>
                        <td><span class="head_anak">:
                                <b>{{ $order->registration_otc->alamat ?? '-' }}</b></span>
                        </td>
                    </tr>
                    <tr>
                        <td><span class="head_label">No. Telpon</span></td>
                        <td><span class="head_anak">:
                                <b>{{ $order->registration_otc->no_telpon ?? '-' }}</b></span>
                        </td>
                    </tr>
                </tbody>
            @endif
        </table>

    </div>

    <script type="text/javascript">
        const parameter = {};

        $(document).ready(function() {

            $(".toggle_container").show();

            $(".trigger").click(function() {
                $(this).toggleClass("active").next().fadeOut("fast");
                $(this).fadeOut("fast");
            });

            $("meta.parameter").each(function() {
                var parameterValue = $(this).attr('content');
                var parameterObject = JSON.parse(parameterValue);
                console.log(parameterObject);

                parameter[parameterObject.id] = parameterObject
            });
            console.log(parameter);
        });



        function toggleParameter(event) {
            var parameterId = $(event.target).val();

            $('#parameter-name').text(': ' + parameter[parameterId].parameter_laboratorium.parameter);
        }
    </script>
</body>

</html>
