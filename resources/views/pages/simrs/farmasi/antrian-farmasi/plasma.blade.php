<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Plasma Antrian Farmasi</title>
    <style>
        @font-face {
            font-family: 'Open Sans';
            src: url({{ asset('font/opensans.ttf') }});
        }

        :root {
            --orange: #f37005;
            --dark-orange: #d65700;
            --accent: #ffb54d;
            --muted: #efefef;
            --thin: #333;
            --purple: #7b1fa2;
            --card-bg: #fff;
            --shadow: 0 2px 0 rgba(0, 0, 0, 0.06);
            /* font-family: 'Helvetica Neue', Arial, sans-serif; */
            font-family: 'Open Sans', sans-serif;
            font-weight: 900;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            background: #fff
        }

        .wrap {
            max-width: 92%;
            margin: 24px auto;
            padding: 0 16px;
            display: grid;
            grid-template-columns: 1fr 480px 1fr;
            gap: 24px;
            align-items: start
        }

        /* Left/Right cards */
        .panel {
            background: var(--card-bg);
            border-top: 6px solid transparent;
            max-height: 160px;
            overflow: auto;

            overflow: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;

            padding: 80px 22px;
            box-shadow: var(--shadow);
            min-height: 160px
        }

        .panel h3 {
            margin: 0 0 12px 0;
            font-size: 16px;
            letter-spacing: 1px
        }

        .panel .divider {
            height: 2px;
            background: #111;
            margin: 8px 0 14px
        }

        .panel .id {
            font-weight: 700;
            font-size: 14px;
            color: var(--thin)
        }

        .panel .name {
            font-weight: 700;
            font-size: 34px;
            float: right;
            color: #222
        }

        .panel .doctor {
            color: var(--purple);
            font-weight: 700;
            margin-top: 6px
        }

        .list-empty {
            height: 90px;
            background: transparent
        }

        /* center column */
        .center {
            background: linear-gradient(to bottom, var(--accent), #fff 12%);
            padding: 6px;
            border: 6px solid var(--accent);
            box-sizing: border-box
        }

        .center-inner {
            background: #fff;
            padding: 6px
        }

        .center .title {
            text-align: center;
            font-size: 34px;
            font-weight: 800;
            margin: 18px 0 6px
        }

        .counter-box {
            background: var(--orange);
            border: 6px solid rgba(255, 255, 255, 0.09);
            padding: 22px;
            text-align: center;
            color: #fff;
            margin: 6px 12px
        }

        .counter {
            font-size: 94px;
            font-weight: 800;
            letter-spacing: 4px
        }

        .subpanels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            margin: 12px
        }

        .sub {
            background: var(--orange);
            padding: 12px;
            border-top: 6px solid var(--dark-orange);
            box-sizing: border-box
        }

        .sub .label {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            opacity: 0.95
        }

        .sub .number {
            text-align: center;
            font-size: 62px;
            font-weight: 800;
            color: #fff;
            margin-top: 6px
        }

        .logo-area {
            background: #fff;
            border-top: 6px solid var(--dark-orange);
            padding: 26px;
            text-align: center
        }

        /* Right small items list style */
        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 6px;
            border-top: 1px solid #e6e6e6
        }

        .item-left {
            line-height: 1
        }

        .item-id {
            font-weight: 700
        }

        .item-name {
            font-weight: 700;
            color: #222
        }

        .item-doc {
            color: var(--purple);
            font-weight: 700;
            margin-top: 6px
        }

        .item-code {
            font-weight: 700;
            font-size: 40px;
            color: #333
        }

        /* responsive */
        @media (max-width:1100px) {
            .wrap {
                grid-template-columns: 1fr;
                max-width: 760px
            }

            .center {
                order: -1
            }
        }
    </style>

    <style>
        #toast {
            /*fullscreen*/
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            pointer-events: none;
            background: var(--orange);
        }

        /* make flash animation */
        @keyframes flash {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.2;
            }
        }

        /* add flashing animation on #toast-content */
        #toast-content {
            /* make the text flashing */
            animation: flash 1s infinite;
            font-size: 300px;
            color: #fff;
            text-align: center;
            line-height: 80vh;
            pointer-events: none;
        }

        #toast-header {
            text-align: center;
            font-size: 80px;
            color: #fff;
            text-align: center;
        }
    </style>
</head>

<body>
    @csrf
    <div id="toast">
        <h1 id="toast-header">NOMOR ANTRIAN</h1>
        <h1 id="toast-content">000</h1>
    </div>


    <div class="wrap">
        <!-- LEFT COLUMN -->
        <div>
            <div class="panel">
                <h3>RESEP NON RACIKAN</h3>
                <div class="divider"></div>
                <div id="list-a">
                </div>
            </div>

            <div class="panel">
                <h3>RESEP NON RACIKAN BPJS</h3>
                <div class="divider"></div>
                <div id="list-c"></div>
            </div>
        </div>

        <!-- CENTER COLUMN -->
        <div class="center">
            <div class="center-inner">
                <div class="title">ANTRIAN FARMASI</div>

                <div class="counter-box">
                    <div
                        style="background:rgba(0,0,0,0.06);padding:6px 12px;border-radius:2px;font-weight:700;display:inline-block;margin-bottom:12px">
                        ANTRIAN</div>
                    <div class="counter" id="current-call">000</div>
                </div>

                <div class="subpanels">
                    <div class="sub">
                        <div class="label">PROSES NON RACIKAN</div>
                        <div class="number" id="count-non-racikan">0</div>
                    </div>
                    <div class="sub">
                        <div class="label">PROSES RACIKAN</div>
                        <div class="number" id="count-racikan">0</div>
                    </div>
                </div>

                <div class="logo-area">
                    <img src="{{ asset('img/logo.png') }}" style="height: 100px">
                </div>

            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div>
            <div class="panel">
                <h3>RESEP RACIKAN</h3>
                <div class="divider"></div>
                <div id="list-b">
                </div>
            </div>

            <div class="panel">
                <h3>RESEP RACIKAN BPJS</h3>
                <div class="divider"></div>
                <div id="list-d">
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/simrs/farmasi/antrian-farmasi/plasma.js') }}?v={{ time() }}"></script>
</body>

</html>
