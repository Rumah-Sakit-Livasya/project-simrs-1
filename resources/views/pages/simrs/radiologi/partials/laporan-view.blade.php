<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="msapplication-TileColor" content="#00bcd4">
    <title>@medic Information System</title>

    <link type="text/css" rel="stylesheet" href="http://192.168.1.253/testing/include/styles/ma/bootstrap.css">
    <link type="text/css" rel="stylesheet"
        href="http://192.168.1.253/testing/include/js/jqgrid5/css/ui.jqgrid-bootstrap.css">
    <link rel="stylesheet" href="http://192.168.1.253/testing/include/js/jqwidgets3.6/jqwidgets/styles/jqx.base.css"
        type="text/css">
    <link type="text/css" rel="stylesheet" href="http://192.168.1.253/testing/include/styles/ma/materialadmin.css">
    <link href="http://192.168.1.253/testing/include/font/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="http://192.168.1.253/testing/include/styles/ma/materialdesignicons.min.css" media="all"
        rel="stylesheet" type="text/css">
    <link type="text/css" rel="stylesheet"
        href="http://192.168.1.253/testing/include/styles/ma/plugins/node-waves/waves.min.css">
    <link
        href="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-datepicker/bootstrap-datetimepicker.min.css"
        rel="stylesheet">
    <link type="text/css" rel="stylesheet"
        href="http://192.168.1.253/testing/include/styles/ma/plugins/select2/select2.css">
    <link type="text/css" rel="stylesheet"
        href="http://192.168.1.253/testing/include/styles/ma/plugins/perfect-scrollbar-1.5.5/perfect-scrollbar.css">
    <link type="text/css" rel="stylesheet"
        href="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css">
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/swal2/sweetalert2.all.min.js?2"></script>
    <style>
        .swal2-popup.swal2-toast {
            box-sizing: border-box;
            grid-column: 1/4 !important;
            grid-row: 1/4 !important;
            grid-template-columns: 1fr 99fr 1fr;
            padding: 1em;
            overflow-y: hidden;
            background: #fff;
            box-shadow: 0 0 1px hsla(0deg, 0%, 0%, .075), 0 1px 2px hsla(0deg, 0%, 0%, .075), 1px 2px 4px hsla(0deg, 0%, 0%, .075), 1px 3px 8px hsla(0deg, 0%, 0%, .075), 2px 4px 16px hsla(0deg, 0%, 0%, .075);
            pointer-events: all
        }

        .swal2-popup.swal2-toast>* {
            grid-column: 2
        }

        .swal2-popup.swal2-toast .swal2-title {
            margin: .5em 1em;
            padding: 0;
            font-size: 1em;
            text-align: initial
        }

        .swal2-popup.swal2-toast .swal2-loading {
            justify-content: center
        }

        .swal2-popup.swal2-toast .swal2-input {
            height: 2em;
            margin: .5em;
            font-size: 1em
        }

        .swal2-popup.swal2-toast .swal2-validation-message {
            font-size: 1em
        }

        .swal2-popup.swal2-toast .swal2-footer {
            margin: .5em 0 0;
            padding: .5em 0 0;
            font-size: .8em
        }

        .swal2-popup.swal2-toast .swal2-close {
            grid-column: 3/3;
            grid-row: 1/99;
            align-self: center;
            width: .8em;
            height: .8em;
            margin: 0;
            font-size: 2em
        }

        .swal2-popup.swal2-toast .swal2-html-container {
            margin: .5em 1em;
            padding: 0;
            overflow: initial;
            font-size: 1em;
            text-align: initial
        }

        .swal2-popup.swal2-toast .swal2-html-container:empty {
            padding: 0
        }

        .swal2-popup.swal2-toast .swal2-loader {
            grid-column: 1;
            grid-row: 1/99;
            align-self: center;
            width: 2em;
            height: 2em;
            margin: .25em
        }

        .swal2-popup.swal2-toast .swal2-icon {
            grid-column: 1;
            grid-row: 1/99;
            align-self: center;
            width: 2em;
            min-width: 2em;
            height: 2em;
            margin: 0 .5em 0 0
        }

        .swal2-popup.swal2-toast .swal2-icon .swal2-icon-content {
            display: flex;
            align-items: center;
            font-size: 1.8em;
            font-weight: 700
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-success .swal2-success-ring {
            width: 2em;
            height: 2em
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
            top: .875em;
            width: 1.375em
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=left] {
            left: .3125em
        }

        .swal2-popup.swal2-toast .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=right] {
            right: .3125em
        }

        .swal2-popup.swal2-toast .swal2-actions {
            justify-content: flex-start;
            height: auto;
            margin: 0;
            margin-top: .5em;
            padding: 0 .5em
        }

        .swal2-popup.swal2-toast .swal2-styled {
            margin: .25em .5em;
            padding: .4em .6em;
            font-size: 1em
        }

        .swal2-popup.swal2-toast .swal2-success {
            border-color: #a5dc86
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line] {
            position: absolute;
            width: 1.6em;
            height: 3em;
            transform: rotate(45deg);
            border-radius: 50%
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=left] {
            top: -.8em;
            left: -.5em;
            transform: rotate(-45deg);
            transform-origin: 2em 2em;
            border-radius: 4em 0 0 4em
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-circular-line][class$=right] {
            top: -.25em;
            left: .9375em;
            transform-origin: 0 1.5em;
            border-radius: 0 4em 4em 0
        }

        .swal2-popup.swal2-toast .swal2-success .swal2-success-ring {
            width: 2em;
            height: 2em
        }

        .swal2-popup.swal2-toast .swal2-success .swal2-success-fix {
            top: 0;
            left: .4375em;
            width: .4375em;
            height: 2.6875em
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line] {
            height: .3125em
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=tip] {
            top: 1.125em;
            left: .1875em;
            width: .75em
        }

        .swal2-popup.swal2-toast .swal2-success [class^=swal2-success-line][class$=long] {
            top: .9375em;
            right: .1875em;
            width: 1.375em
        }

        .swal2-popup.swal2-toast .swal2-success.swal2-icon-show .swal2-success-line-tip {
            -webkit-animation: swal2-toast-animate-success-line-tip .75s;
            animation: swal2-toast-animate-success-line-tip .75s
        }

        .swal2-popup.swal2-toast .swal2-success.swal2-icon-show .swal2-success-line-long {
            -webkit-animation: swal2-toast-animate-success-line-long .75s;
            animation: swal2-toast-animate-success-line-long .75s
        }

        .swal2-popup.swal2-toast.swal2-show {
            -webkit-animation: swal2-toast-show .5s;
            animation: swal2-toast-show .5s
        }

        .swal2-popup.swal2-toast.swal2-hide {
            -webkit-animation: swal2-toast-hide .1s forwards;
            animation: swal2-toast-hide .1s forwards
        }

        .swal2-container {
            display: grid;
            position: fixed;
            z-index: 1060;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            box-sizing: border-box;
            grid-template-areas: "top-start     top            top-end" "center-start  center         center-end" "bottom-start  bottom-center  bottom-end";
            grid-template-rows: minmax(-webkit-min-content, auto) minmax(-webkit-min-content, auto) minmax(-webkit-min-content, auto);
            grid-template-rows: minmax(min-content, auto) minmax(min-content, auto) minmax(min-content, auto);
            height: 100%;
            padding: .625em;
            overflow-x: hidden;
            transition: background-color .1s;
            -webkit-overflow-scrolling: touch
        }

        .swal2-container.swal2-backdrop-show,
        .swal2-container.swal2-noanimation {
            background: rgba(0, 0, 0, .4)
        }

        .swal2-container.swal2-backdrop-hide {
            background: 0 0 !important
        }

        .swal2-container.swal2-bottom-start,
        .swal2-container.swal2-center-start,
        .swal2-container.swal2-top-start {
            grid-template-columns: minmax(0, 1fr) auto auto
        }

        .swal2-container.swal2-bottom,
        .swal2-container.swal2-center,
        .swal2-container.swal2-top {
            grid-template-columns: auto minmax(0, 1fr) auto
        }

        .swal2-container.swal2-bottom-end,
        .swal2-container.swal2-center-end,
        .swal2-container.swal2-top-end {
            grid-template-columns: auto auto minmax(0, 1fr)
        }

        .swal2-container.swal2-top-start>.swal2-popup {
            align-self: start
        }

        .swal2-container.swal2-top>.swal2-popup {
            grid-column: 2;
            align-self: start;
            justify-self: center
        }

        .swal2-container.swal2-top-end>.swal2-popup,
        .swal2-container.swal2-top-right>.swal2-popup {
            grid-column: 3;
            align-self: start;
            justify-self: end
        }

        .swal2-container.swal2-center-left>.swal2-popup,
        .swal2-container.swal2-center-start>.swal2-popup {
            grid-row: 2;
            align-self: center
        }

        .swal2-container.swal2-center>.swal2-popup {
            grid-column: 2;
            grid-row: 2;
            align-self: center;
            justify-self: center
        }

        .swal2-container.swal2-center-end>.swal2-popup,
        .swal2-container.swal2-center-right>.swal2-popup {
            grid-column: 3;
            grid-row: 2;
            align-self: center;
            justify-self: end
        }

        .swal2-container.swal2-bottom-left>.swal2-popup,
        .swal2-container.swal2-bottom-start>.swal2-popup {
            grid-column: 1;
            grid-row: 3;
            align-self: end
        }

        .swal2-container.swal2-bottom>.swal2-popup {
            grid-column: 2;
            grid-row: 3;
            justify-self: center;
            align-self: end
        }

        .swal2-container.swal2-bottom-end>.swal2-popup,
        .swal2-container.swal2-bottom-right>.swal2-popup {
            grid-column: 3;
            grid-row: 3;
            align-self: end;
            justify-self: end
        }

        .swal2-container.swal2-grow-fullscreen>.swal2-popup,
        .swal2-container.swal2-grow-row>.swal2-popup {
            grid-column: 1/4;
            width: 100%
        }

        .swal2-container.swal2-grow-column>.swal2-popup,
        .swal2-container.swal2-grow-fullscreen>.swal2-popup {
            grid-row: 1/4;
            align-self: stretch
        }

        .swal2-container.swal2-no-transition {
            transition: none !important
        }

        .swal2-popup {
            display: none;
            position: relative;
            box-sizing: border-box;
            grid-template-columns: minmax(0, 100%);
            width: 32em;
            max-width: 100%;
            padding: 0 0 1.25em;
            border: none;
            border-radius: 5px;
            background: #fff;
            color: #545454;
            font-family: inherit;
            font-size: 1rem
        }

        .swal2-popup:focus {
            outline: 0
        }

        .swal2-popup.swal2-loading {
            overflow-y: hidden
        }

        .swal2-title {
            position: relative;
            max-width: 100%;
            margin: 0;
            padding: .8em 1em 0;
            color: inherit;
            font-size: 1.875em;
            font-weight: 600;
            text-align: center;
            text-transform: none;
            word-wrap: break-word
        }

        .swal2-actions {
            display: flex;
            z-index: 1;
            box-sizing: border-box;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            width: auto;
            margin: 1.25em auto 0;
            padding: 0
        }

        .swal2-actions:not(.swal2-loading) .swal2-styled[disabled] {
            opacity: .4
        }

        .swal2-actions:not(.swal2-loading) .swal2-styled:hover {
            background-image: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1))
        }

        .swal2-actions:not(.swal2-loading) .swal2-styled:active {
            background-image: linear-gradient(rgba(0, 0, 0, .2), rgba(0, 0, 0, .2))
        }

        .swal2-loader {
            display: none;
            align-items: center;
            justify-content: center;
            width: 2.2em;
            height: 2.2em;
            margin: 0 1.875em;
            -webkit-animation: swal2-rotate-loading 1.5s linear 0s infinite normal;
            animation: swal2-rotate-loading 1.5s linear 0s infinite normal;
            border-width: .25em;
            border-style: solid;
            border-radius: 100%;
            border-color: #2778c4 transparent #2778c4 transparent
        }

        .swal2-styled {
            margin: .3125em;
            padding: .625em 1.1em;
            transition: box-shadow .1s;
            box-shadow: 0 0 0 3px transparent;
            font-weight: 500
        }

        .swal2-styled:not([disabled]) {
            cursor: pointer
        }

        .swal2-styled.swal2-confirm {
            border: 0;
            border-radius: .25em;
            background: initial;
            background-color: #7066e0;
            color: #fff;
            font-size: 1em
        }

        .swal2-styled.swal2-confirm:focus {
            box-shadow: 0 0 0 3px rgba(112, 102, 224, .5)
        }

        .swal2-styled.swal2-deny {
            border: 0;
            border-radius: .25em;
            background: initial;
            background-color: #dc3741;
            color: #fff;
            font-size: 1em
        }

        .swal2-styled.swal2-deny:focus {
            box-shadow: 0 0 0 3px rgba(220, 55, 65, .5)
        }

        .swal2-styled.swal2-cancel {
            border: 0;
            border-radius: .25em;
            background: initial;
            background-color: #6e7881;
            color: #fff;
            font-size: 1em
        }

        .swal2-styled.swal2-cancel:focus {
            box-shadow: 0 0 0 3px rgba(110, 120, 129, .5)
        }

        .swal2-styled.swal2-default-outline:focus {
            box-shadow: 0 0 0 3px rgba(100, 150, 200, .5)
        }

        .swal2-styled:focus {
            outline: 0
        }

        .swal2-styled::-moz-focus-inner {
            border: 0
        }

        .swal2-footer {
            justify-content: center;
            margin: 1em 0 0;
            padding: 1em 1em 0;
            border-top: 1px solid #eee;
            color: inherit;
            font-size: 1em
        }

        .swal2-timer-progress-bar-container {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            grid-column: auto !important;
            overflow: hidden;
            border-bottom-right-radius: 5px;
            border-bottom-left-radius: 5px
        }

        .swal2-timer-progress-bar {
            width: 100%;
            height: .25em;
            background: rgba(0, 0, 0, .2)
        }

        .swal2-image {
            max-width: 100%;
            margin: 2em auto 1em
        }

        .swal2-close {
            z-index: 2;
            align-items: center;
            justify-content: center;
            width: 1.2em;
            height: 1.2em;
            margin-top: 0;
            margin-right: 0;
            margin-bottom: -1.2em;
            padding: 0;
            overflow: hidden;
            transition: color .1s, box-shadow .1s;
            border: none;
            border-radius: 5px;
            background: 0 0;
            color: #ccc;
            font-family: serif;
            font-family: monospace;
            font-size: 2.5em;
            cursor: pointer;
            justify-self: end
        }

        .swal2-close:hover {
            transform: none;
            background: 0 0;
            color: #f27474
        }

        .swal2-close:focus {
            outline: 0;
            box-shadow: inset 0 0 0 3px rgba(100, 150, 200, .5)
        }

        .swal2-close::-moz-focus-inner {
            border: 0
        }

        .swal2-html-container {
            z-index: 1;
            justify-content: center;
            margin: 1em 1.6em .3em;
            padding: 0;
            overflow: auto;
            color: inherit;
            font-size: 1.125em;
            font-weight: 400;
            line-height: normal;
            text-align: center;
            word-wrap: break-word;
            word-break: break-word
        }

        .swal2-checkbox,
        .swal2-file,
        .swal2-input,
        .swal2-radio,
        .swal2-select,
        .swal2-textarea {
            margin: 1em 2em 3px
        }

        .swal2-file,
        .swal2-input,
        .swal2-textarea {
            box-sizing: border-box;
            width: auto;
            transition: border-color .1s, box-shadow .1s;
            border: 1px solid #d9d9d9;
            border-radius: .1875em;
            background: 0 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .06), 0 0 0 3px transparent;
            color: inherit;
            font-size: 1.125em
        }

        .swal2-file.swal2-inputerror,
        .swal2-input.swal2-inputerror,
        .swal2-textarea.swal2-inputerror {
            border-color: #f27474 !important;
            box-shadow: 0 0 2px #f27474 !important
        }

        .swal2-file:focus,
        .swal2-input:focus,
        .swal2-textarea:focus {
            border: 1px solid #b4dbed;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .06), 0 0 0 3px rgba(100, 150, 200, .5)
        }

        .swal2-file::-moz-placeholder,
        .swal2-input::-moz-placeholder,
        .swal2-textarea::-moz-placeholder {
            color: #ccc
        }

        .swal2-file::placeholder,
        .swal2-input::placeholder,
        .swal2-textarea::placeholder {
            color: #ccc
        }

        .swal2-range {
            margin: 1em 2em 3px;
            background: #fff
        }

        .swal2-range input {
            width: 80%
        }

        .swal2-range output {
            width: 20%;
            color: inherit;
            font-weight: 600;
            text-align: center
        }

        .swal2-range input,
        .swal2-range output {
            height: 2.625em;
            padding: 0;
            font-size: 1.125em;
            line-height: 2.625em
        }

        .swal2-input {
            height: 2.625em;
            padding: 0 .75em
        }

        .swal2-file {
            width: 75%;
            margin-right: auto;
            margin-left: auto;
            background: 0 0;
            font-size: 1.125em
        }

        .swal2-textarea {
            height: 6.75em;
            padding: .75em
        }

        .swal2-select {
            min-width: 50%;
            max-width: 100%;
            padding: .375em .625em;
            background: 0 0;
            color: inherit;
            font-size: 1.125em
        }

        .swal2-checkbox,
        .swal2-radio {
            align-items: center;
            justify-content: center;
            background: #fff;
            color: inherit
        }

        .swal2-checkbox label,
        .swal2-radio label {
            margin: 0 .6em;
            font-size: 1.125em
        }

        .swal2-checkbox input,
        .swal2-radio input {
            flex-shrink: 0;
            margin: 0 .4em
        }

        .swal2-input-label {
            display: flex;
            justify-content: center;
            margin: 1em auto 0
        }

        .swal2-validation-message {
            align-items: center;
            justify-content: center;
            margin: 1em 0 0;
            padding: .625em;
            overflow: hidden;
            background: #f0f0f0;
            color: #666;
            font-size: 1em;
            font-weight: 300
        }

        .swal2-validation-message::before {
            content: "!";
            display: inline-block;
            width: 1.5em;
            min-width: 1.5em;
            height: 1.5em;
            margin: 0 .625em;
            border-radius: 50%;
            background-color: #f27474;
            color: #fff;
            font-weight: 600;
            line-height: 1.5em;
            text-align: center
        }

        .swal2-icon {
            position: relative;
            box-sizing: content-box;
            justify-content: center;
            width: 5em;
            height: 5em;
            margin: 2.5em auto .6em;
            border: .25em solid transparent;
            border-radius: 50%;
            border-color: #000;
            font-family: inherit;
            line-height: 5em;
            cursor: default;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none
        }

        .swal2-icon .swal2-icon-content {
            display: flex;
            align-items: center;
            font-size: 3.75em
        }

        .swal2-icon.swal2-error {
            border-color: #f27474;
            color: #f27474
        }

        .swal2-icon.swal2-error .swal2-x-mark {
            position: relative;
            flex-grow: 1
        }

        .swal2-icon.swal2-error [class^=swal2-x-mark-line] {
            display: block;
            position: absolute;
            top: 2.3125em;
            width: 2.9375em;
            height: .3125em;
            border-radius: .125em;
            background-color: #f27474
        }

        .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=left] {
            left: 1.0625em;
            transform: rotate(45deg)
        }

        .swal2-icon.swal2-error [class^=swal2-x-mark-line][class$=right] {
            right: 1em;
            transform: rotate(-45deg)
        }

        .swal2-icon.swal2-error.swal2-icon-show {
            -webkit-animation: swal2-animate-error-icon .5s;
            animation: swal2-animate-error-icon .5s
        }

        .swal2-icon.swal2-error.swal2-icon-show .swal2-x-mark {
            -webkit-animation: swal2-animate-error-x-mark .5s;
            animation: swal2-animate-error-x-mark .5s
        }

        .swal2-icon.swal2-warning {
            border-color: #facea8;
            color: #f8bb86
        }

        .swal2-icon.swal2-warning.swal2-icon-show {
            -webkit-animation: swal2-animate-error-icon .5s;
            animation: swal2-animate-error-icon .5s
        }

        .swal2-icon.swal2-warning.swal2-icon-show .swal2-icon-content {
            -webkit-animation: swal2-animate-i-mark .5s;
            animation: swal2-animate-i-mark .5s
        }

        .swal2-icon.swal2-info {
            border-color: #9de0f6;
            color: #3fc3ee
        }

        .swal2-icon.swal2-info.swal2-icon-show {
            -webkit-animation: swal2-animate-error-icon .5s;
            animation: swal2-animate-error-icon .5s
        }

        .swal2-icon.swal2-info.swal2-icon-show .swal2-icon-content {
            -webkit-animation: swal2-animate-i-mark .8s;
            animation: swal2-animate-i-mark .8s
        }

        .swal2-icon.swal2-question {
            border-color: #c9dae1;
            color: #87adbd
        }

        .swal2-icon.swal2-question.swal2-icon-show {
            -webkit-animation: swal2-animate-error-icon .5s;
            animation: swal2-animate-error-icon .5s
        }

        .swal2-icon.swal2-question.swal2-icon-show .swal2-icon-content {
            -webkit-animation: swal2-animate-question-mark .8s;
            animation: swal2-animate-question-mark .8s
        }

        .swal2-icon.swal2-success {
            border-color: #a5dc86;
            color: #a5dc86
        }

        .swal2-icon.swal2-success [class^=swal2-success-circular-line] {
            position: absolute;
            width: 3.75em;
            height: 7.5em;
            transform: rotate(45deg);
            border-radius: 50%
        }

        .swal2-icon.swal2-success [class^=swal2-success-circular-line][class$=left] {
            top: -.4375em;
            left: -2.0635em;
            transform: rotate(-45deg);
            transform-origin: 3.75em 3.75em;
            border-radius: 7.5em 0 0 7.5em
        }

        .swal2-icon.swal2-success [class^=swal2-success-circular-line][class$=right] {
            top: -.6875em;
            left: 1.875em;
            transform: rotate(-45deg);
            transform-origin: 0 3.75em;
            border-radius: 0 7.5em 7.5em 0
        }

        .swal2-icon.swal2-success .swal2-success-ring {
            position: absolute;
            z-index: 2;
            top: -.25em;
            left: -.25em;
            box-sizing: content-box;
            width: 100%;
            height: 100%;
            border: .25em solid rgba(165, 220, 134, .3);
            border-radius: 50%
        }

        .swal2-icon.swal2-success .swal2-success-fix {
            position: absolute;
            z-index: 1;
            top: .5em;
            left: 1.625em;
            width: .4375em;
            height: 5.625em;
            transform: rotate(-45deg)
        }

        .swal2-icon.swal2-success [class^=swal2-success-line] {
            display: block;
            position: absolute;
            z-index: 2;
            height: .3125em;
            border-radius: .125em;
            background-color: #a5dc86
        }

        .swal2-icon.swal2-success [class^=swal2-success-line][class$=tip] {
            top: 2.875em;
            left: .8125em;
            width: 1.5625em;
            transform: rotate(45deg)
        }

        .swal2-icon.swal2-success [class^=swal2-success-line][class$=long] {
            top: 2.375em;
            right: .5em;
            width: 2.9375em;
            transform: rotate(-45deg)
        }

        .swal2-icon.swal2-success.swal2-icon-show .swal2-success-line-tip {
            -webkit-animation: swal2-animate-success-line-tip .75s;
            animation: swal2-animate-success-line-tip .75s
        }

        .swal2-icon.swal2-success.swal2-icon-show .swal2-success-line-long {
            -webkit-animation: swal2-animate-success-line-long .75s;
            animation: swal2-animate-success-line-long .75s
        }

        .swal2-icon.swal2-success.swal2-icon-show .swal2-success-circular-line-right {
            -webkit-animation: swal2-rotate-success-circular-line 4.25s ease-in;
            animation: swal2-rotate-success-circular-line 4.25s ease-in
        }

        .swal2-progress-steps {
            flex-wrap: wrap;
            align-items: center;
            max-width: 100%;
            margin: 1.25em auto;
            padding: 0;
            background: 0 0;
            font-weight: 600
        }

        .swal2-progress-steps li {
            display: inline-block;
            position: relative
        }

        .swal2-progress-steps .swal2-progress-step {
            z-index: 20;
            flex-shrink: 0;
            width: 2em;
            height: 2em;
            border-radius: 2em;
            background: #2778c4;
            color: #fff;
            line-height: 2em;
            text-align: center
        }

        .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step {
            background: #2778c4
        }

        .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step {
            background: #add8e6;
            color: #fff
        }

        .swal2-progress-steps .swal2-progress-step.swal2-active-progress-step~.swal2-progress-step-line {
            background: #add8e6
        }

        .swal2-progress-steps .swal2-progress-step-line {
            z-index: 10;
            flex-shrink: 0;
            width: 2.5em;
            height: .4em;
            margin: 0 -1px;
            background: #2778c4
        }

        [class^=swal2] {
            -webkit-tap-highlight-color: transparent
        }

        .swal2-show {
            -webkit-animation: swal2-show .3s;
            animation: swal2-show .3s
        }

        .swal2-hide {
            -webkit-animation: swal2-hide .15s forwards;
            animation: swal2-hide .15s forwards
        }

        .swal2-noanimation {
            transition: none
        }

        .swal2-scrollbar-measure {
            position: absolute;
            top: -9999px;
            width: 50px;
            height: 50px;
            overflow: scroll
        }

        .swal2-rtl .swal2-close {
            margin-right: initial;
            margin-left: 0
        }

        .swal2-rtl .swal2-timer-progress-bar {
            right: 0;
            left: auto
        }

        .leave-russia-now-and-apply-your-skills-to-the-world {
            display: flex;
            position: fixed;
            z-index: 1939;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 0 20px;
            background: #20232a;
            color: #fff;
            text-align: center
        }

        .leave-russia-now-and-apply-your-skills-to-the-world div {
            max-width: 560px;
            margin: 10px;
            line-height: 146%
        }

        .leave-russia-now-and-apply-your-skills-to-the-world iframe {
            max-width: 100%;
            max-height: 55.5555555556vmin;
            margin: 16px auto
        }

        .leave-russia-now-and-apply-your-skills-to-the-world strong {
            border-bottom: 2px dashed #fff
        }

        .leave-russia-now-and-apply-your-skills-to-the-world button {
            display: flex;
            position: fixed;
            z-index: 1940;
            top: 0;
            right: 0;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            margin-right: 10px;
            margin-bottom: -10px;
            border: none;
            background: 0 0;
            color: #aaa;
            font-size: 48px;
            font-weight: 700;
            cursor: pointer
        }

        .leave-russia-now-and-apply-your-skills-to-the-world button:hover {
            color: #fff
        }

        @-webkit-keyframes swal2-toast-show {
            0% {
                transform: translateY(-.625em) rotateZ(2deg)
            }

            33% {
                transform: translateY(0) rotateZ(-2deg)
            }

            66% {
                transform: translateY(.3125em) rotateZ(2deg)
            }

            100% {
                transform: translateY(0) rotateZ(0)
            }
        }

        @keyframes swal2-toast-show {
            0% {
                transform: translateY(-.625em) rotateZ(2deg)
            }

            33% {
                transform: translateY(0) rotateZ(-2deg)
            }

            66% {
                transform: translateY(.3125em) rotateZ(2deg)
            }

            100% {
                transform: translateY(0) rotateZ(0)
            }
        }

        @-webkit-keyframes swal2-toast-hide {
            100% {
                transform: rotateZ(1deg);
                opacity: 0
            }
        }

        @keyframes swal2-toast-hide {
            100% {
                transform: rotateZ(1deg);
                opacity: 0
            }
        }

        @-webkit-keyframes swal2-toast-animate-success-line-tip {
            0% {
                top: .5625em;
                left: .0625em;
                width: 0
            }

            54% {
                top: .125em;
                left: .125em;
                width: 0
            }

            70% {
                top: .625em;
                left: -.25em;
                width: 1.625em
            }

            84% {
                top: 1.0625em;
                left: .75em;
                width: .5em
            }

            100% {
                top: 1.125em;
                left: .1875em;
                width: .75em
            }
        }

        @keyframes swal2-toast-animate-success-line-tip {
            0% {
                top: .5625em;
                left: .0625em;
                width: 0
            }

            54% {
                top: .125em;
                left: .125em;
                width: 0
            }

            70% {
                top: .625em;
                left: -.25em;
                width: 1.625em
            }

            84% {
                top: 1.0625em;
                left: .75em;
                width: .5em
            }

            100% {
                top: 1.125em;
                left: .1875em;
                width: .75em
            }
        }

        @-webkit-keyframes swal2-toast-animate-success-line-long {
            0% {
                top: 1.625em;
                right: 1.375em;
                width: 0
            }

            65% {
                top: 1.25em;
                right: .9375em;
                width: 0
            }

            84% {
                top: .9375em;
                right: 0;
                width: 1.125em
            }

            100% {
                top: .9375em;
                right: .1875em;
                width: 1.375em
            }
        }

        @keyframes swal2-toast-animate-success-line-long {
            0% {
                top: 1.625em;
                right: 1.375em;
                width: 0
            }

            65% {
                top: 1.25em;
                right: .9375em;
                width: 0
            }

            84% {
                top: .9375em;
                right: 0;
                width: 1.125em
            }

            100% {
                top: .9375em;
                right: .1875em;
                width: 1.375em
            }
        }

        @-webkit-keyframes swal2-show {
            0% {
                transform: scale(.7)
            }

            45% {
                transform: scale(1.05)
            }

            80% {
                transform: scale(.95)
            }

            100% {
                transform: scale(1)
            }
        }

        @keyframes swal2-show {
            0% {
                transform: scale(.7)
            }

            45% {
                transform: scale(1.05)
            }

            80% {
                transform: scale(.95)
            }

            100% {
                transform: scale(1)
            }
        }

        @-webkit-keyframes swal2-hide {
            0% {
                transform: scale(1);
                opacity: 1
            }

            100% {
                transform: scale(.5);
                opacity: 0
            }
        }

        @keyframes swal2-hide {
            0% {
                transform: scale(1);
                opacity: 1
            }

            100% {
                transform: scale(.5);
                opacity: 0
            }
        }

        @-webkit-keyframes swal2-animate-success-line-tip {
            0% {
                top: 1.1875em;
                left: .0625em;
                width: 0
            }

            54% {
                top: 1.0625em;
                left: .125em;
                width: 0
            }

            70% {
                top: 2.1875em;
                left: -.375em;
                width: 3.125em
            }

            84% {
                top: 3em;
                left: 1.3125em;
                width: 1.0625em
            }

            100% {
                top: 2.8125em;
                left: .8125em;
                width: 1.5625em
            }
        }

        @keyframes swal2-animate-success-line-tip {
            0% {
                top: 1.1875em;
                left: .0625em;
                width: 0
            }

            54% {
                top: 1.0625em;
                left: .125em;
                width: 0
            }

            70% {
                top: 2.1875em;
                left: -.375em;
                width: 3.125em
            }

            84% {
                top: 3em;
                left: 1.3125em;
                width: 1.0625em
            }

            100% {
                top: 2.8125em;
                left: .8125em;
                width: 1.5625em
            }
        }

        @-webkit-keyframes swal2-animate-success-line-long {
            0% {
                top: 3.375em;
                right: 2.875em;
                width: 0
            }

            65% {
                top: 3.375em;
                right: 2.875em;
                width: 0
            }

            84% {
                top: 2.1875em;
                right: 0;
                width: 3.4375em
            }

            100% {
                top: 2.375em;
                right: .5em;
                width: 2.9375em
            }
        }

        @keyframes swal2-animate-success-line-long {
            0% {
                top: 3.375em;
                right: 2.875em;
                width: 0
            }

            65% {
                top: 3.375em;
                right: 2.875em;
                width: 0
            }

            84% {
                top: 2.1875em;
                right: 0;
                width: 3.4375em
            }

            100% {
                top: 2.375em;
                right: .5em;
                width: 2.9375em
            }
        }

        @-webkit-keyframes swal2-rotate-success-circular-line {
            0% {
                transform: rotate(-45deg)
            }

            5% {
                transform: rotate(-45deg)
            }

            12% {
                transform: rotate(-405deg)
            }

            100% {
                transform: rotate(-405deg)
            }
        }

        @keyframes swal2-rotate-success-circular-line {
            0% {
                transform: rotate(-45deg)
            }

            5% {
                transform: rotate(-45deg)
            }

            12% {
                transform: rotate(-405deg)
            }

            100% {
                transform: rotate(-405deg)
            }
        }

        @-webkit-keyframes swal2-animate-error-x-mark {
            0% {
                margin-top: 1.625em;
                transform: scale(.4);
                opacity: 0
            }

            50% {
                margin-top: 1.625em;
                transform: scale(.4);
                opacity: 0
            }

            80% {
                margin-top: -.375em;
                transform: scale(1.15)
            }

            100% {
                margin-top: 0;
                transform: scale(1);
                opacity: 1
            }
        }

        @keyframes swal2-animate-error-x-mark {
            0% {
                margin-top: 1.625em;
                transform: scale(.4);
                opacity: 0
            }

            50% {
                margin-top: 1.625em;
                transform: scale(.4);
                opacity: 0
            }

            80% {
                margin-top: -.375em;
                transform: scale(1.15)
            }

            100% {
                margin-top: 0;
                transform: scale(1);
                opacity: 1
            }
        }

        @-webkit-keyframes swal2-animate-error-icon {
            0% {
                transform: rotateX(100deg);
                opacity: 0
            }

            100% {
                transform: rotateX(0);
                opacity: 1
            }
        }

        @keyframes swal2-animate-error-icon {
            0% {
                transform: rotateX(100deg);
                opacity: 0
            }

            100% {
                transform: rotateX(0);
                opacity: 1
            }
        }

        @-webkit-keyframes swal2-rotate-loading {
            0% {
                transform: rotate(0)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        @keyframes swal2-rotate-loading {
            0% {
                transform: rotate(0)
            }

            100% {
                transform: rotate(360deg)
            }
        }

        @-webkit-keyframes swal2-animate-question-mark {
            0% {
                transform: rotateY(-360deg)
            }

            100% {
                transform: rotateY(0)
            }
        }

        @keyframes swal2-animate-question-mark {
            0% {
                transform: rotateY(-360deg)
            }

            100% {
                transform: rotateY(0)
            }
        }

        @-webkit-keyframes swal2-animate-i-mark {
            0% {
                transform: rotateZ(45deg);
                opacity: 0
            }

            25% {
                transform: rotateZ(-25deg);
                opacity: .4
            }

            50% {
                transform: rotateZ(15deg);
                opacity: .8
            }

            75% {
                transform: rotateZ(-5deg);
                opacity: 1
            }

            100% {
                transform: rotateX(0);
                opacity: 1
            }
        }

        @keyframes swal2-animate-i-mark {
            0% {
                transform: rotateZ(45deg);
                opacity: 0
            }

            25% {
                transform: rotateZ(-25deg);
                opacity: .4
            }

            50% {
                transform: rotateZ(15deg);
                opacity: .8
            }

            75% {
                transform: rotateZ(-5deg);
                opacity: 1
            }

            100% {
                transform: rotateX(0);
                opacity: 1
            }
        }

        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow: hidden
        }

        body.swal2-height-auto {
            height: auto !important
        }

        body.swal2-no-backdrop .swal2-container {
            background-color: transparent !important;
            pointer-events: none
        }

        body.swal2-no-backdrop .swal2-container .swal2-popup {
            pointer-events: all
        }

        body.swal2-no-backdrop .swal2-container .swal2-modal {
            box-shadow: 0 0 10px rgba(0, 0, 0, .4)
        }

        @media print {
            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
                overflow-y: scroll !important
            }

            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown)>[aria-hidden=true] {
                display: none
            }

            body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) .swal2-container {
                position: static !important
            }
        }

        body.swal2-toast-shown .swal2-container {
            box-sizing: border-box;
            width: 360px;
            max-width: 100%;
            background-color: transparent;
            pointer-events: none
        }

        body.swal2-toast-shown .swal2-container.swal2-top {
            top: 0;
            right: auto;
            bottom: auto;
            left: 50%;
            transform: translateX(-50%)
        }

        body.swal2-toast-shown .swal2-container.swal2-top-end,
        body.swal2-toast-shown .swal2-container.swal2-top-right {
            top: 0;
            right: 0;
            bottom: auto;
            left: auto
        }

        body.swal2-toast-shown .swal2-container.swal2-top-left,
        body.swal2-toast-shown .swal2-container.swal2-top-start {
            top: 0;
            right: auto;
            bottom: auto;
            left: 0
        }

        body.swal2-toast-shown .swal2-container.swal2-center-left,
        body.swal2-toast-shown .swal2-container.swal2-center-start {
            top: 50%;
            right: auto;
            bottom: auto;
            left: 0;
            transform: translateY(-50%)
        }

        body.swal2-toast-shown .swal2-container.swal2-center {
            top: 50%;
            right: auto;
            bottom: auto;
            left: 50%;
            transform: translate(-50%, -50%)
        }

        body.swal2-toast-shown .swal2-container.swal2-center-end,
        body.swal2-toast-shown .swal2-container.swal2-center-right {
            top: 50%;
            right: 0;
            bottom: auto;
            left: auto;
            transform: translateY(-50%)
        }

        body.swal2-toast-shown .swal2-container.swal2-bottom-left,
        body.swal2-toast-shown .swal2-container.swal2-bottom-start {
            top: auto;
            right: auto;
            bottom: 0;
            left: 0
        }

        body.swal2-toast-shown .swal2-container.swal2-bottom {
            top: auto;
            right: auto;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%)
        }

        body.swal2-toast-shown .swal2-container.swal2-bottom-end,
        body.swal2-toast-shown .swal2-container.swal2-bottom-right {
            top: auto;
            right: 0;
            bottom: 0;
            left: auto
        }
    </style>
    <link type="text/css" rel="stylesheet" href="http://192.168.1.253/testing/include/styles/ma/custom_style.css?7">

    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/jqgrid5/js/i18n/grid.locale-id.js"></script>
    <script src="http://192.168.1.253/testing/include/js/jqgrid5/js/jquery.jqGrid.min.js?v2"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/jqwidgets3.6/jqwidgets/jqxcore.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/jqwidgets3.6/jqwidgets/jqx-all.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/node-waves/waves.min.js">
    </script>
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-datepicker/moment.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-datepicker/id.js">
    </script>
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-datepicker/bootstrap-datetimepicker.min.js">
    </script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/select2/select2.full.min.js">
    </script>
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/perfect-scrollbar-1.5.5/perfect-scrollbar.min.js">
    </script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/spin.js/spin.min.js">
    </script>
    <style type="text/css"></style>
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js">
    </script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/toastr/toastr.min.js">
    </script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/styles/ma/plugins/bootbox.min.js"></script>
    <script type="text/javascript"
        src="http://192.168.1.253/testing/include/styles/ma/plugins/jquery.inputmask.bundle.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://192.168.1.253/testing/include/js/standard_lib.js"></script>
    <script type="text/javascript">
        function base_url() {
            return 'http://192.168.1.253/testing/';
        }
    </script>
</head>

<body class="loaded">
    <div id="loader-wrapper">
        <div id="loader"></div>
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>
    <div class="modal fade" id="popupcx" role="dialog">
        <div class="modal-dialog">
            <div class="modal-body" id="bodycx"></div>
        </div>
    </div>
    <div id="popupcx2" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" id="bodycx2"></div>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <!-- start print_pr.html-->
        <script type="text/javascript">
            function printPage() {
                document.getElementById('divButtons').style.display = 'none';
                window.print();
                document.getElementById('divButtons').style.display = '';
                return false;
            }
        </script>


        <div class="print">
            <div class="print_function" id="divButtons">
                <input type="button" value="Print" onclick="printPage();">
                <input type="button" value="XLS"
                    onclick="window.location.replace('http://192.168.1.253/testing/rad_report/print_pasien_per_pemeriksaan/01-03-2025/18-03-2025/-/-/-/-/yes');">
                <input type="button" value="Close" onclick="JavaScript:window.close();">
            </div>
            <h1>
                LAPORAN PASIEN PER PEMERIKSAAN
                <span>PERIODE : {{ $startDate }} - {{ $endDate }} </span>

            </h1>
            <table width="100%">
                <tbody>
                    <tr>
                        <td>
                            <table width="100%" class="bordered" style="margin-top:-10px">
                                <thead>
                                    <tr>
                                        <th rowspan="2">No </th>
                                        <th rowspan="2" width="3%">No Reg</th>
                                        <th rowspan="2" width="4%">Tgl Reg</th>
                                        <th colspan="2">No RM</th>
                                        <th rowspan="2">Pasien</th>
                                        <th rowspan="2">Jenis Kelamin</th>
                                        <th rowspan="2">Umur</th>
                                        <th rowspan="2">Alamat</th>
                                        <th rowspan="2">No Telp</th>
                                        <th rowspan="2">Ruang</th>
                                        <th rowspan="2">Penjamin</th>
                                        <th rowspan="2">Perujuk</th>
                                        <th rowspan="2">No Order</th>
                                        <th rowspan="2">Tgl Pemeriksaan</th>
                                        <th rowspan="2">Tgl Penyerahan</th>
                                        <th rowspan="2">Tgl Expertise</th>
                                        <th rowspan="2">Dokter</th>
                                        <th rowspan="2">Radiografer</th>
                                        <th rowspan="2">Parameter</th>
                                        <th rowspan="2">Tarif</th>
                                        <th rowspan="2">Keterangan</th>
                                    </tr>
                                    <tr>
                                        <th>No RM Baru</th>
                                        <th>No RM Lama</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $orderParameter)
                                        <tr>
                                            <td align="center">{{ $loop->iteration }}</td>
                                            {{-- @php
                                                dd($orderParameter);
                                            @endphp --}}
                                            @if($orderParameter->order_radiologi->registration_otc)
                                                <td align="center">{{ $orderParameter->order_radiologi->registration_otc->registration_number }}</td>
                                                <td align="center">{{ $orderParameter->order_radiologi->registration_otc->order_date }}</td>
                                                <td align="center">OTC</td>
                                                <td align="center"></td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->nama_pasien }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->jenis_kelamin }}</td>
                                                <td> {{ displayAge($orderParameter->order_radiologi->registration_otc->date_of_birth) }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->alamat}} </td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->no_telp }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->poly_ruang }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration_otc->penjamin->nama_perusahaan }}</td>
                                                {{-- @php
                                                    dd($orderParameter->registration_otc->doctor);
                                                @endphp --}}
                                                <td>{{ $orderParameter->registration_otc->doctor->employee->fullname }}</td>
                                            @else
                                                <td align="center">{{ $orderParameter->order_radiologi->registration->registration_number }}</td>
                                                <td align="center">{{ $orderParameter->order_radiologi->registration->date }}</td>
                                                <td align="center"></td>
                                                <td align="center">{{ $orderParameter->order_radiologi->registration->patient->medical_record_number }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->patient->name }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->patient->gender }}</td>
                                                <td> {{ displayAge($orderParameter->order_radiologi->registration->patient->date_of_birth) }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->patient->address }} </td>
                                                <td>{{ $orderParameter->order_radiologi->registration->patient->mobile_phone_number }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->poliklinik }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->penjamin->nama_perusahaan }}</td>
                                                <td>{{ $orderParameter->order_radiologi->registration->doctor->employee->fullname }}</td>
                                            @endif

                                            <td align="center">{{ $orderParameter->order_radiologi->no_order }}</td>
                                            <td align="center">{{ $orderParameter->order_radiologi->inspection_date }}</td>
                                            <td align="center">{{ $orderParameter->order_radiologi->pickup_date }}</td>
                                            <td align="center">{{ $orderParameter->verifikasi_date }}</td>
                                            <td>{{ $orderParameter->order_radiologi->doctor->employee->fullname }}</td>
                                            <td>{{ $orderParameter->radiografer ? $orderParameter->radiografer->fullname : '' }}</td>
                                            <td>{{ $orderParameter->parameter_radiologi->parameter }}</td>
                                            <td align="right">{{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($orderParameter->nominal_rupiah, 'IDR') }}</td>
                                            <td><span style="color:#f00; font-style:italic;">{{ $orderParameter->verifikasi_date ? 'Unconfirmed' : '' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tbody>
                                    <tr>

                                        <td colspan="20"><b>Total</b></td>

                                        <td align="right"><b>{{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($orders->sum('nominal_rupiah'), 'IDR') }}</b></td>

                                        <td>&nbsp;</td>

                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- end print_pr.html -->

    </div>
    <script>
        (function($) {
            "use strict";

            var App = function() {
                var o = this; // Create reference to this instance
                $(document).ready(function() {
                    o.initialize();
                }); // Initialize app when document is ready

            };
            var p = App.prototype;

            // =========================================================================
            // MEMBERS
            // =========================================================================

            // Constant
            App.SCREEN_XS = 480;
            App.SCREEN_SM = 768;
            App.SCREEN_MD = 992;
            App.SCREEN_LG = 1200;

            // Private
            p._callFunctions = null;
            p._resizeTimer = null;

            // =========================================================================
            // INIT
            // =========================================================================

            p.initialize = function() {
                // Init events
                this._enableEvents();

                // Init base
                this._initBreakpoints();

                // Init components
                this._initInk();

                // Init accordion
                this._initAccordion();
            };

            // =========================================================================
            // EVENTS
            // =========================================================================

            // events
            p._enableEvents = function() {
                var o = this;

                // Window events
                $(window).on('resize', function(e) {
                    clearTimeout(o._resizeTimer);
                    o._resizeTimer = setTimeout(function() {
                        o._handleFunctionCalls(e);
                    }, 300);
                });
            };

            // =========================================================================
            // JQUERY-KNOB
            // =========================================================================

            p.getKnobStyle = function(knob) {
                var holder = knob.closest('.knob');
                var options = {
                    width: Math.floor(holder.outerWidth()),
                    height: Math.floor(holder.outerHeight()),
                    fgColor: holder.css('color'),
                    bgColor: holder.css('border-top-color'),
                    draw: function() {
                        if (knob.data('percentage')) {
                            $(this.i).val(this.cv + '%');
                        }
                    }
                };
                return options;
            };

            // =========================================================================
            // ACCORDION
            // =========================================================================

            p._initAccordion = function() {
                $('.panel-group .card .in').each(function() {
                    var card = $(this).parent();
                    card.addClass('expanded');
                });


                $('.panel-group').on('hide.bs.collapse', function(e) {
                    var content = $(e.target);
                    var card = content.parent();
                    card.removeClass('expanded');
                });

                $('.panel-group').on('show.bs.collapse', function(e) {
                    var content = $(e.target);
                    var card = content.parent();
                    var group = card.closest('.panel-group');

                    group.find('.card.expanded').removeClass('expanded');
                    card.addClass('expanded');
                });
            };

            // =========================================================================
            // INK EFFECT
            // =========================================================================

            p._initInk = function() {
                var o = this;

                $('.ink-reaction').on('click', function(e) {
                    var bound = $(this).get(0).getBoundingClientRect();
                    var x = e.clientX - bound.left;
                    var y = e.clientY - bound.top;

                    var color = o.getBackground($(this));
                    var inverse = (o.getLuma(color) > 183) ? ' inverse' : '';

                    var ink = $('<div class="ink' + inverse + '"></div>');
                    var btnOffset = $(this).offset();
                    var xPos = e.pageX - btnOffset.left;
                    var yPos = e.pageY - btnOffset.top;

                    ink.css({
                        top: yPos,
                        left: xPos
                    }).appendTo($(this));

                    window.setTimeout(function() {
                        ink.remove();
                    }, 1500);
                });
            };

            p.getBackground = function(item) {
                // Is current element's background color set?
                var color = item.css("background-color");
                var alpha = parseFloat(color.split(',')[3], 10);

                if ((isNaN(alpha) || alpha > 0.8) && color !== 'transparent') {
                    // if so then return that color if it isn't transparent
                    return color;
                }

                // if not: are you at the body element?
                if (item.is("body")) {
                    // return known 'false' value
                    return false;
                } else {
                    // call getBackground with parent item
                    return this.getBackground(item.parent());
                }
            };

            p.getLuma = function(color) {
                var rgba = color.substring(4, color.length - 1).split(',');
                var r = rgba[0];
                var g = rgba[1];
                var b = rgba[2];
                var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b; // per ITU-R BT.709
                return luma;
            };

            // =========================================================================
            // DETECT BREAKPOINTS
            // =========================================================================

            p._initBreakpoints = function(alias) {
                var html = '';
                html += '<div id="device-breakpoints">';
                html += '<div class="device-xs visible-xs" data-breakpoint="xs"></div>';
                html += '<div class="device-sm visible-sm" data-breakpoint="sm"></div>';
                html += '<div class="device-md visible-md" data-breakpoint="md"></div>';
                html += '<div class="device-lg visible-lg" data-breakpoint="lg"></div>';
                html += '</div>';
                $('body').append(html);
            };

            p.isBreakpoint = function(alias) {
                return $('.device-' + alias).is(':visible');
            };
            p.minBreakpoint = function(alias) {
                var breakpoints = ['xs', 'sm', 'md', 'lg'];
                var breakpoint = $('#device-breakpoints div:visible').data('breakpoint');
                return $.inArray(alias, breakpoints) < $.inArray(breakpoint, breakpoints);
            };

            // =========================================================================
            // UTILS
            // =========================================================================

            p.callOnResize = function(func) {
                if (this._callFunctions === null) {
                    this._callFunctions = [];
                }
                this._callFunctions.push(func);
                func.call();
            };

            p._handleFunctionCalls = function(e) {
                if (this._callFunctions === null) {
                    return;
                }
                for (var i = 0; i < this._callFunctions.length; i++) {
                    this._callFunctions[i].call();
                }
            };

            // =========================================================================
            // DEFINE NAMESPACE
            // =========================================================================

            window.materialadmin = window.materialadmin || {};
            window.materialadmin.App = new App;
        }(jQuery)); // pass in (jQuery):


        (function(namespace, $) {
            "use strict";

            var AppOffcanvas = function() {
                // Create reference to this instance
                var o = this;
                // Initialize app when document is ready
                $(document).ready(function() {
                    o.initialize();
                });

            };
            var p = AppOffcanvas.prototype;
            // =========================================================================
            // MEMBERS
            // =========================================================================

            p._timer = null;
            p._useBackdrop = null;

            // =========================================================================
            // INIT
            // =========================================================================

            p.initialize = function() {
                this._enableEvents();
            };

            // =========================================================================
            // EVENTS
            // =========================================================================

            p._enableEvents = function() {
                var o = this;

                // Window events
                $(window).on('resize', function(e) {
                    o._handleScreenSize(e);
                });

                // Offcanvas events
                $('.offcanvas').on('refresh', function(e) {
                    o.evalScrollbar(e);
                });
                $('[data-toggle="offcanvas"]').on('click', function(e) {
                    e.preventDefault();
                    o._handleOffcanvasOpen($(e.currentTarget));
                });
                $('[data-dismiss="offcanvas"]').on('click', function(e) {
                    o._handleOffcanvasClose();
                });
                $('#base').on('click', '> .backdrop', function(e) {
                    o._handleOffcanvasClose();
                });

                // Open active offcanvas buttons
                $('[data-toggle="offcanvas-left"].active').each(function() {
                    o._handleOffcanvasOpen($(this));
                });
                $('[data-toggle="offcanvas-right"].active').each(function() {
                    o._handleOffcanvasOpen($(this));
                });
            };

            // handlers
            p._handleScreenSize = function(e) {
                this.evalScrollbar(e);
            };

            // =========================================================================
            // HANDLERS
            // =========================================================================

            p._handleOffcanvasOpen = function(btn) {
                // When the button is active, the off-canvas is already open and sould be closed
                if (btn.hasClass('active')) {
                    this._handleOffcanvasClose();
                    return;
                }

                var id = btn.attr('href');

                // Set data variables
                this._useBackdrop = (btn.data('backdrop') === undefined) ? true : btn.data('backdrop');

                // Open off-canvas
                this.openOffcanvas(id);
                this.invalidate();
            };

            p._handleOffcanvasClose = function(e) {
                this.closeOffcanvas();
                this.invalidate();
            };

            // =========================================================================
            // OPEN OFFCANVAS
            // =========================================================================

            p.openOffcanvas = function(id) {
                // First close all offcanvas panes
                this.closeOffcanvas();

                // Activate selected offcanvas pane
                $(id).addClass('active');

                // Check if the offcanvas is on the left
                var leftOffcanvas = ($(id).closest('.offcanvas:first').length > 0);

                // Remove offcanvas-expanded to enable body scrollbar
                if (this._useBackdrop)
                    $('body').addClass('offcanvas-expanded');

                // Define the width
                var width = $(id).width();
                if (width > $(document).width()) {
                    width = $(document).width() - 8;
                    $(id + '.active').css({
                        'width': width
                    });
                }
                width = (leftOffcanvas) ? width : '-' + width;

                // Translate position offcanvas pane
                var translate = 'translate(' + width + 'px, 0)';
                $(id + '.active').css({
                    '-webkit-transform': translate,
                    '-ms-transform': translate,
                    '-o-transform': translate,
                    'transform': translate
                });
            };

            // =========================================================================
            // CLOSE OFFCANVAS
            // =========================================================================

            p.closeOffcanvas = function() {
                // Remove expanded on all offcanvas buttons
                $('[data-toggle="offcanvas"]').removeClass('expanded');

                // Remove offcanvas active state
                $('.offcanvas-pane').removeClass('active'); //.removeAttr('style');
                $('.offcanvas-pane').css({
                    '-webkit-transform': '',
                    '-ms-transform': '',
                    '-o-transform': '',
                    'transform': ''
                });
            };

            // =========================================================================
            // OFFCANVAS BUTTONS
            // =========================================================================

            p.toggleButtonState = function() {
                // Activate the active offcanvas pane
                var id = $('.offcanvas-pane.active').attr('id');
                $('[data-toggle="offcanvas"]').removeClass('active');
                $('[href="#' + id + '"]').addClass('active');
            };

            // =========================================================================
            // BACKDROP
            // =========================================================================

            p.toggleBackdropState = function() {
                // Clear the timer that removes the keyword
                if ($('.offcanvas-pane.active').length > 0 && this._useBackdrop) {
                    this._addBackdrop();
                } else {
                    this._removeBackdrop();
                }
            };

            p._addBackdrop = function() {
                if ($('#base > .backdrop').length === 0 && $('#base').data('backdrop') !== 'hidden') {
                    $('<div class="backdrop"></div>').hide().appendTo('#base').fadeIn();
                }
            };

            p._removeBackdrop = function() {
                $('#base > .backdrop').fadeOut(function() {
                    $(this).remove();
                });
            };

            // =========================================================================
            // BODY SCROLLING
            // =========================================================================

            p.toggleBodyScrolling = function() {
                clearTimeout(this._timer);
                if ($('.offcanvas-pane.active').length > 0 && this._useBackdrop) {
                    // Add body padding to prevent visual jumping
                    var scrollbarWidth = this.measureScrollbar();
                    var bodyPad = parseInt(($('body').css('padding-right') || 0), 10);
                    if (scrollbarWidth !== bodyPad) {
                        $('body').css('padding-right', bodyPad + scrollbarWidth);
                        $('.headerbar').css('padding-right', bodyPad + scrollbarWidth);
                    }
                } else {
                    this._timer = setTimeout(function() {
                        // Remove offcanvas-expanded to enable body scrollbar
                        $('body').removeClass('offcanvas-expanded');
                        $('body').css('padding-right', '');
                        $('.headerbar').removeClass('offcanvas-expanded');
                        $('.headerbar').css('padding-right', '');
                    }, 330);
                }
            };

            // =========================================================================
            // INVALIDATE
            // =========================================================================

            p.invalidate = function() {
                this.toggleButtonState();
                this.toggleBackdropState();
                this.toggleBodyScrolling();
                this.evalScrollbar();
            };

            // =========================================================================
            // SCROLLBAR
            // =========================================================================

            p.evalScrollbar = function() {
                if (!$.isFunction($.fn.nanoScroller)) {
                    return;
                }

                // Check if there is a menu
                var menu = $('.offcanvas-pane.active');
                if (menu.length === 0)
                    return;

                // Get scrollbar elements
                var menuScroller = $('.offcanvas-pane.active .offcanvas-body');
                var parent = menuScroller.parent();

                // Add the scroller wrapper
                if (parent.hasClass('nano-content') === false) {
                    menuScroller.wrap('<div class="nano"><div class="nano-content"></div></div>');
                }

                // Set the correct height
                var height = $(window).height() - menu.find('.nano').position().top;
                var scroller = menuScroller.closest('.nano');
                scroller.css({
                    height: height
                });

                // Add the nanoscroller
                scroller.nanoScroller({
                    preventPageScrolling: true
                });
            };

            // =========================================================================
            // UTILS
            // =========================================================================

            p.measureScrollbar = function() {
                var scrollDiv = document.createElement('div');
                scrollDiv.className = 'modal-scrollbar-measure';
                $('body').append(scrollDiv);
                var scrollbarWidth = scrollDiv.offsetWidth - scrollDiv.clientWidth;
                $('body')[0].removeChild(scrollDiv);
                return scrollbarWidth;
            };

            // =========================================================================
            // DEFINE NAMESPACE
            // =========================================================================

            window.materialadmin.AppOffcanvas = new AppOffcanvas;
        }(this.materialadmin, jQuery)); // pass in (namespace, jQuery):


        (function(namespace, $) {
            "use strict";

            var AppCard = function() {
                // Create reference to this instance
                var o = this;
                // Initialize app when document is ready
                $(document).ready(function() {
                    o.initialize();
                });

            };
            var p = AppCard.prototype;

            // =========================================================================
            // INIT
            // =========================================================================

            p.initialize = function() {};

            // =========================================================================
            // CARD LOADER
            // =========================================================================

            p.addCardLoader = function(card) {

                card = (typeof card === 'object') ? card : $('body');

                var container = $('<div class="card-loader"></div>').appendTo(card);
                container.hide().fadeIn();
                var opts = {
                    lines: 20, // The number of lines to draw
                    length: 2, // The length of each line
                    width: 5, // The line thickness
                    radius: 16, // The radius of the inner circle
                    corners: 1, // Corner roundness (0..1)
                    rotate: 13, // The rotation offset
                    direction: 1, // 1: clockwise, -1: counterclockwise
                    color: '#673ab7', // #rgb or #rrggbb or array of colors
                    speed: 2, // Rounds per second
                    trail: 76, // Afterglow percentage
                    shadow: false, // Whether to render a shadow
                    hwaccel: false, // Whether to use hardware acceleration
                    className: 'spinner', // The CSS class to assign to the spinner
                    zIndex: 2e9, // The z-index (defaults to 2000000000)
                };
                var spinner = new Spinner(opts).spin(container.get(0));
                $(card).data('card-spinner', spinner);
            };

            p.removeCardLoader = function(card) {

                card = (typeof card === 'object') ? card : $('body');

                var spinner = $(card).data('card-spinner');
                var loader = $(card).find('.card-loader');
                loader.fadeOut(function() {
                    spinner.stop();
                    loader.remove();
                });
            };

            // =========================================================================
            // CARD COLLAPSE
            // =========================================================================

            p.toggleCardCollapse = function(card, duration) {
                duration = typeof duration !== 'undefined' ? duration : 400;
                var dispatched = false;
                card.find('.nano').slideToggle(duration);
                card.find('.card-body').slideToggle(duration, function() {
                    if (dispatched === false) {
                        $('#COLLAPSER').triggerHandler('card.bb.collapse', [!$(this).is(":visible")]);
                        dispatched = true;
                    }
                });
                card.toggleClass('card-collapsed');
            };

            // =========================================================================
            // CARD REMOVE
            // =========================================================================

            p.removeCard = function(card) {
                card.fadeOut(function() {
                    card.remove();
                });
            };

            // =========================================================================
            // DEFINE NAMESPACE
            // =========================================================================

            window.materialadmin.AppCard = new AppCard;
        }(this.materialadmin, jQuery)); // pass in (namespace, jQuery):


        (function(namespace, $) {
            "use strict";

            var AppVendor = function() {
                // Create reference to this instance
                var o = this;
                // Initialize app when document is ready
                $(document).ready(function() {
                    o.initialize();
                });

            };
            var p = AppVendor.prototype;

            // =========================================================================
            // INIT
            // =========================================================================

            p.initialize = function() {
                this._initScroller();
                this._initTabs();
                this._initTooltips();
                this._initPopover();
                this._initSortables();
            };

            // =========================================================================
            // SCROLLER
            // =========================================================================

            p._initScroller = function() {
                if (!$.isFunction($.fn.nanoScroller)) {
                    return;
                }

                $.each($('.scroll'), function(e) {
                    var holder = $(this);
                    materialadmin.AppVendor.addScroller(holder);
                });

                materialadmin.App.callOnResize(function() {
                    $.each($('.scroll-xs'), function(e) {
                        var holder = $(this);
                        if (!holder.is(":visible")) return;

                        if (materialadmin.App.minBreakpoint('xs')) {
                            materialadmin.AppVendor.removeScroller(holder);
                        } else {
                            materialadmin.AppVendor.addScroller(holder);
                        }
                    });

                    $.each($('.scroll-sm'), function(e) {
                        var holder = $(this);
                        if (!holder.is(":visible")) return;

                        if (materialadmin.App.minBreakpoint('sm')) {
                            materialadmin.AppVendor.removeScroller(holder);
                        } else {
                            materialadmin.AppVendor.addScroller(holder);
                        }
                    });

                    $.each($('.scroll-md'), function(e) {
                        var holder = $(this);
                        if (!holder.is(":visible")) return;

                        if (materialadmin.App.minBreakpoint('md')) {
                            materialadmin.AppVendor.removeScroller(holder);
                        } else {
                            materialadmin.AppVendor.addScroller(holder);
                        }
                    });

                    $.each($('.scroll-lg'), function(e) {
                        var holder = $(this);
                        if (!holder.is(":visible")) return;

                        if (materialadmin.App.minBreakpoint('lg')) {
                            materialadmin.AppVendor.removeScroller(holder);
                        } else {
                            materialadmin.AppVendor.addScroller(holder);
                        }
                    });
                });
            };

            p.addScroller = function(holder) {
                holder.wrap('<div class="nano"><div class="nano-content"></div></div>');

                var scroller = holder.closest('.nano');
                scroller.css({
                    height: holder.outerHeight()
                });
                scroller.nanoScroller();

                holder.css({
                    height: 'auto'
                });
            };

            p.removeScroller = function(holder) {
                if (holder.parent().parent().hasClass('nano') === false) {
                    return;
                }

                holder.parent().parent().nanoScroller({
                    destroy: true
                });

                holder.parent('.nano-content').replaceWith(holder);
                holder.parent('.nano').replaceWith(holder);
                holder.attr('style', '');
            };

            // =========================================================================
            // SORTABLE
            // =========================================================================

            p._initSortables = function() {
                if (!$.isFunction($.fn.sortable)) {
                    return;
                }

                $('[data-sortable="true"]').sortable({
                    placeholder: "ui-state-highlight",
                    delay: 100,
                    start: function(e, ui) {
                        ui.placeholder.height(ui.item.outerHeight() - 1);
                    }
                });

            };

            // =========================================================================
            // TABS
            // =========================================================================

            p._initTabs = function() {
                if (!$.isFunction($.fn.tab)) {
                    return;
                }
                $('[data-toggle="tabs"] a').click(function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
            };

            // =========================================================================
            // TOOLTIPS
            // =========================================================================

            p._initTooltips = function() {
                if (!$.isFunction($.fn.tooltip)) {
                    return;
                }
                $('[data-toggle="tooltip"]').tooltip({
                    container: 'body'
                });
            };

            // =========================================================================
            // POPOVER
            // =========================================================================

            p._initPopover = function() {
                if (!$.isFunction($.fn.popover)) {
                    return;
                }
                $('[data-toggle="popover"]').popover({
                    container: 'body'
                });
            };

            // =========================================================================
            // DEFINE NAMESPACE
            // =========================================================================

            window.materialadmin.AppVendor = new AppVendor;
        }(this.materialadmin, jQuery)); // pass in (namespace, jQuery):


        (function(namespace, $) {
            "use strict";

            var AppForm = function() {
                // Create reference to this instance
                var o = this;
                // Initialize app when document is ready
                $(document).ready(function() {
                    o.initialize();
                });

            };
            var p = AppForm.prototype;

            // =========================================================================
            // INIT
            // =========================================================================

            p.initialize = function() {
                // Init events
                this._enableEvents();

                this._initRadioAndCheckbox();
                this._initFloatingLabels();
                this._initValidation();
                this._initLabels();

            };

            // =========================================================================
            // EVENTS
            // =========================================================================

            // events
            p._enableEvents = function() {
                var o = this;

                // Link submit function
                $('[data-submit="form"]').on('click', function(e) {
                    e.preventDefault();
                    var formId = $(e.currentTarget).attr('href');
                    $(formId).submit();
                });

                // Init textarea autosize
                $('textarea.autosize').on('focus', function() {
                    $(this).autosize({
                        append: ''
                    });
                });
            };

            // =========================================================================
            // RADIO AND CHECKBOX LISTENERS
            // =========================================================================

            p._initRadioAndCheckbox = function() {
                // Add a span class the styled checkboxes and radio buttons for correct styling
                $('.checkbox-styled input, .radio-styled input').each(function() {
                    if ($(this).next('span').length === 0) {
                        $(this).after('<span></span>');
                    }
                });
            };

            // =========================================================================
            // NORMAL LABELS cx 15 mei 2018
            // =========================================================================

            p._initLabels = function() {
                $('.form-control').on('focus', function() {
                    $(this).parents('.form-group').children('label').addClass('mycolor');
                }).on('focusout', function() {
                    $(this).parents('.form-group').children('label').removeClass('mycolor');
                });

                $('.form-group').on('mouseover', function() {
                    if ($(this).find('.form-control')) {
                        $(this).children('label').addClass('mycolor');
                    }
                }).on('mouseout', function() {
                    if ($(this).find('.form-control')) {
                        $(this).children('label').removeClass('mycolor');
                    }
                });
            };

            // =========================================================================
            // FLOATING LABELS
            // =========================================================================

            p._initFloatingLabels = function() {
                var o = this;

                $('.floating-label .form-control').on('keyup change', function(e) {
                    var input = $(e.currentTarget);

                    if ($.trim(input.val()) !== '') {
                        input.addClass('dirty').removeClass('static');
                    } else {
                        input.removeClass('dirty').removeClass('static');
                    }
                });

                $('.floating-label .form-control').each(function() {
                    var input = $(this);

                    if ($.trim(input.val()) !== '') {
                        input.addClass('static').addClass('dirty');
                    }
                });

                $('.form-horizontal .form-control').each(function() {
                    $(this).after('<div class="form-control-line"></div>');
                });
            };

            // =========================================================================
            // VALIDATION
            // =========================================================================

            p._initValidation = function() {
                if (!$.isFunction($.fn.validate)) {
                    return;
                }
                $.validator.setDefaults({
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
                    unhighlight: function(element) {
                        $(element).closest('.form-group').removeClass('has-error');
                    },
                    errorElement: 'span',
                    errorClass: 'help-block',
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.parent('label').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                $('.form-validate').each(function() {
                    var validator = $(this).validate();
                    $(this).data('validator', validator);
                });
            };

            p.loadPostData = function(url, data, target, card) {
                //card = (card.length)?card:target;

                materialadmin.AppCard.addCardLoader(card);
                $(target).load(url, data, function(response, status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);
                    materialadmin.AppCard.removeCardLoader(card);
                });
            }

            // =========================================================================
            // DEFINE NAMESPACE
            // =========================================================================

            window.materialadmin.AppForm = new AppForm;
        }(this.materialadmin, jQuery)); // pass in (namespace, jQuery):


        (function($) {
            "use strict";

            var App = function() {
                var o = this; // Create reference to this instance
                $(document).ready(function() {
                    o.initialize();
                }); // Initialize app when document is ready

            };
            var p = App.prototype;

            p.initialize = function() {};


            p.loadData = function(Options) {

                var Settings = {
                    url: location.href,
                    type: "POST",
                    data: {},
                    target: document.getElementsByTagName("BODY")[0],
                    area: document.getElementsByTagName("BODY")[0]
                };

                Options = Options || {};

                $.extend(Settings, Options);

                materialadmin.AppCard.addCardLoader(Settings.area);
                $(Settings.target).load(Settings.url, Settings.data, function(response, status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                    materialadmin.AppCard.removeCardLoader(Settings.area);
                });
            }

            p.popup = function(Options) {

                let Settings = {
                    url: location.href,
                    type: "POST",
                    data: {},
                    modal: $('#popupcx'),
                    target: $('#bodycx'),
                    popup_type: 1,
                    mode: '',
                    title: 'Popup window',
                    area: document.getElementsByTagName("BODY")[0],
                };

                Options = Options || {};

                $.extend(Settings, Options);

                if (Settings.popup_type == 2 && Settings.modal.attr('id') == 'popupcx') {
                    Settings.modal = $('#popupcx2');
                    Settings.target = $('#bodycx2');
                }

                Settings.modal.find('.modal-title').text(Settings.title);

                if (Settings.mode == 'md' && (Settings.modal.attr('id') == 'popupcx' || Settings.modal.attr('id') ==
                        'popupcx2'))
                    Settings.modal.children('.modal-dialog').removeClass('modal-lg modal-md').addClass('modal-md');
                else if (Settings.mode == 'lg' && (Settings.modal.attr('id') == 'popupcx' || Settings.modal.attr(
                        'id') == 'popupcx2'))
                    Settings.modal.children('.modal-dialog').removeClass('modal-lg modal-md').addClass('modal-lg');
                else if (Settings.popup_type == 2 && Settings.modal.attr('id') == 'popupcx2')
                    Settings.modal.children('.modal-dialog').removeClass('modal-lg modal-md').addClass('modal-lg');
                else if (Settings.popup_type == 2 && Settings.modal.attr('id') == 'popupcx')
                    Settings.modal.children('.modal-dialog').removeClass('modal-lg modal-md');

                materialadmin.AppCard.addCardLoader(Settings.area);
                $(Settings.target).load(Settings.url, Settings.data, function(response, status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                    $(Settings.modal).modal({
                        show: true
                    });

                    materialadmin.AppCard.removeCardLoader(Settings.area);
                });
            }

            p.saveCustomForm = function(Options) {

                let Settings = {
                    url: base_url() + 'custom_form/save_form',
                    status: '1'
                };

                Options = Options || {};
                //console.log($('#form-builder'));
                let formdata = new FormData(document.getElementById('form-builder'));

                materialadmin.AppCard.addCardLoader();
                $.ajax({
                    type: "POST",
                    url: base_url() + 'pengkajian/save_form/' + Settings.status,
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        bootbox.alert(data);
                        p.reloadListPengkajian($('#form-builder #pregid').val());
                    },
                    async: false,
                }).fail(function() {
                    alert('Error');
                }).always(function() {
                    materialadmin.AppCard.removeCardLoader();
                });
            }

            p.getDataPengkajian = function(pkid, pregid, histori = false, myurl = '') {
                materialadmin.AppCard.addCardLoader();
                let url = (pkid != '') ? base_url() + 'pengkajian/get_data_form/' : base_url() +
                    'pengkajian/get_data_awal/';

                url = (histori) ? base_url() + 'pengkajian/get_hist_with_pkid/' : url;

                url = (myurl != '') ? myurl : url;

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        pkid: pkid,
                        pregid: pregid
                    },
                    success: function(data) {
                        if (typeof data !== 'undefined') {
                            let mydata = data;
                            materialadmin.AppForm.initialize();
                            let input_type = ['text', 'hidden', 'number', 'email', 'date'];

                            $.each(mydata, function(idx, val) { //console.log(idx + ' = ' + val);
                                if (jQuery.isArray(val)) {
                                    $.each(val, function(idx2, val2) {
                                        $('[name="' + idx + '[]"][value="' + val2 +
                                            '"]').prop('checked', true).trigger(
                                            'change');
                                    });
                                } else {

                                    if (val != '' && val !== null) {
                                        val = val.replace(/<br\s*\/?>/mg, "\n");
                                        var obj = $('[name="' + idx + '"]');

                                        if (obj.prop("tagName") == 'INPUT') {
                                            if (obj.attr('type').toLowerCase() == 'radio' || obj
                                                .attr('type').toLowerCase() == 'checkbox') {
                                                $('[name="' + idx + '"][value="' + val + '"]')
                                                    .prop('checked', true).trigger('change');

                                                //show element lain jika checked
                                                if ($('[name="' + idx + '"][value="' + val +
                                                        '"]').data('show') !== undefined && $(
                                                        '[name="' + idx + '"][value="' + val +
                                                        '"]').is(':checked')) {
                                                    $($('[name="' + idx + '"][value="' + val +
                                                        '"]').data('show')).show();
                                                }

                                            } else if (input_type.includes(obj.attr('type')
                                                    .toLowerCase())) {
                                                $('[name="' + idx + '"]').val(val);
                                                if (typeof $('[name="' + idx + '"]').data(
                                                        'imgview') !== 'undefined') {
                                                    if (val.match(
                                                            /data:image\/png;base64|data:image\/jpeg;base64/
                                                        )) {
                                                        $('#' + $('[name="' + idx + '"]').data(
                                                            'imgview')).attr('src', val);
                                                    } else {
                                                        $('#' + $('[name="' + idx + '"]').data(
                                                            'imgview')).attr('src',
                                                            base_url() + val);
                                                    }
                                                }
                                                //if(typeof $('[name="'+idx+'"]').data('imgview') !== 'undefined')
                                                //	$('#'+$('[name="'+idx+'"]').data('imgview')).attr('src',val);
                                            }
                                        } else if (obj.prop("tagName") == 'TEXTAREA')
                                            $('[name="' + idx + '"]').val(val);
                                        else if (obj.prop("tagName") == 'SELECT') {
                                            $('[name="' + idx + '"]').val(val);
                                            $('[name="' + idx + '"]').trigger('change');
                                        }

                                    }
                                }
                            });
                        }

                    },
                    dataType: 'json',
                    async: false,
                }).fail(function() {
                    alert('Error load data pengkajian');
                }).always(function() {
                    materialadmin.AppCard.removeCardLoader();
                });
            }

            p.getDataLink = function(url, pregid, id = '') {
                materialadmin.AppCard.addCardLoader();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        id: id,
                        pregid: pregid
                    },
                    success: function(data) {
                        if (typeof data !== 'undefined') {
                            let mydata = data;
                            let input_type = ['text', 'hidden', 'number', 'email', 'date'];
                            materialadmin.AppForm.initialize();
                            $.each(mydata, function(idx, val) { //console.log(idx + ' = ' + val);
                                if (jQuery.isArray(val)) {
                                    $.each(val, function(idx2, val2) {
                                        $('[name="' + idx + '[]"][value="' + val2 +
                                            '"]').prop('checked', true).trigger(
                                            'change');
                                    });
                                } else {

                                    if (val != '' && val !== null) {
                                        val = val.replace(/<br\s*\/?>/mg, "\n");
                                        var obj = $('[name="' + idx + '"]');

                                        if (obj.prop("tagName") == 'INPUT') {
                                            if (obj.attr('type').toLowerCase() == 'radio' || obj
                                                .attr('type').toLowerCase() == 'checkbox')
                                                $('[name="' + idx + '"][value="' + val + '"]')
                                                .prop('checked', true).trigger('change');
                                            else if (input_type.includes(obj.attr('type')
                                                    .toLowerCase()))
                                                $('[name="' + idx + '"]').val(val);
                                        } else if (obj.prop("tagName") == 'TEXTAREA')
                                            $('[name="' + idx + '"]').val(val);
                                        else if (obj.prop("tagName") == 'SELECT') {
                                            $('[name="' + idx + '"]').val(val);
                                            $('[name="' + idx + '"]').trigger('change');
                                        }
                                    }
                                }
                            });
                        }

                    },
                    dataType: 'json',
                    async: false,
                }).fail(function() {
                    alert('Error load otomatis data');
                }).always(function() {
                    materialadmin.AppCard.removeCardLoader();
                });
            }

            p.getPrevPengkajian = function(pkid) {
                materialadmin.AppCard.addCardLoader($('#popupcx2'));
                $.ajax({
                    type: "POST",
                    url: base_url() + 'pengkajian/get_data_preview/',
                    data: {
                        pkid: pkid
                    },
                    success: function(data) {
                        if (typeof data !== 'undefined') {
                            let mydata = data;
                            let input_type = ['text', 'hidden', 'number', 'email', 'date'];
                            materialadmin.AppForm.initialize();
                            $.each(mydata, function(idx, val) { //console.log(idx + ' = ' + val);
                                if (jQuery.isArray(val)) {
                                    $.each(val, function(idx2, val2) {
                                        $('#form_preview_' + pkid + ' [view="' + idx +
                                            '[]"][value="' + val2 + '"]').prop(
                                            'checked', true).trigger('change');
                                    });
                                } else {

                                    if (val != '') {
                                        val = val.replace(/<br\s*\/?>/mg, "\n");
                                        var obj = $('#form_preview_' + pkid + ' [view="' + idx +
                                            '"]');

                                        if (obj.prop("tagName") == 'INPUT') {
                                            if (obj.attr('type').toLowerCase() == 'radio' || obj
                                                .attr('type').toLowerCase() == 'checkbox')
                                                $('#form_preview_' + pkid + ' [view="' + idx +
                                                    '"][value="' + val + '"]').prop('checked',
                                                    true).trigger('change');
                                            else if (input_type.includes(obj.attr('type')
                                                    .toLowerCase())) {
                                                $('#form_preview_' + pkid + ' [view="' + idx +
                                                    '"]').val(val);
                                                if (typeof $('[view="' + idx + '"]').data(
                                                        'imgview') !== 'undefined')
                                                    $('#' + $('[view="' + idx + '"]').data(
                                                        'imgview')).attr('src', val);
                                            }
                                        } else if (obj.prop("tagName") == 'TEXTAREA')
                                            $('#form_preview_' + pkid + ' [view="' + idx + '"]')
                                            .val(val);
                                        else if (obj.prop("tagName") == 'SELECT') {
                                            $('#form_preview_' + pkid + ' [view="' + idx + '"]')
                                                .val(val);
                                            $('#form_preview_' + pkid + ' [view="' + idx + '"]')
                                                .trigger('change');
                                        }
                                    }
                                }
                            });
                        }

                    },
                    dataType: 'json',
                    async: false,
                }).fail(function() {
                    alert('Error load otomatis data');
                }).always(function() {
                    materialadmin.AppCard.removeCardLoader($('#popupcx2'));
                });
            }

            p.popupPengkajian = function(ftid, pkid, title, pregid) {
                atmedic.App.popup({
                    url: base_url() + 'pengkajian/load_form/',
                    data: {
                        ftid: ftid,
                        pkid: pkid,
                        pregid: pregid,
                    },
                    mode: 'lg',
                    title: title,
                    popup_type: 2,
                });
            }

            p.reloadListPengkajian = function(pregid) {
                p.loadData({
                    url: base_url() + 'pengkajian/list_pengkajian_pasien/',
                    target: document.getElementById('load_list_pengkajian'),
                    data: {
                        pregid: pregid
                    }
                });
            }

            p.hapusPengkajian = function(pkid, pregid) {
                materialadmin.AppCard.addCardLoader();
                $.ajax({
                    type: "POST",
                    url: base_url() + 'pengkajian/hapus_pengkajian/',
                    data: {
                        pkid: pkid
                    },
                    success: function(data) {
                        bootbox.alert(data);
                        p.reloadListPengkajian(pregid);
                    },
                    async: false,
                }).fail(function() {
                    alert('Error');
                }).always(function() {
                    materialadmin.AppCard.removeCardLoader();
                });
            }

            p.loadCpptRmedis = function(pregid, element) {
                materialadmin.AppCard.addCardLoader();
                $("#list_soap").load(base_url() + "pengkajian/load_soap_pasien_rekam_medis/" + pregid, "", function(
                    response, status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                    materialadmin.AppCard.removeCardLoader();
                });
            };


            p.loadCppt = function(pregid, element) {

                materialadmin.AppCard.addCardLoader();
                $('#list_soap').load(base_url() + 'pengkajian/load_soap_pasien/' + pregid, '', function(response,
                    status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                    materialadmin.AppCard.removeCardLoader();
                });
            }

            //p.loadCpptRanap = function(pregid,element){

            //	materialadmin.AppCard.addCardLoader();
            //  $('#list_soap').load(base_url()+'pengkajian/load_soap_pasien_ranap/'+pregid,'',function(response, status, xhr){

            //    if ( status == "error" )  console.log(xhr.status + " " + xhr.statusText );

            //  materialadmin.AppCard.removeCardLoader();
            // });
            // }

            p.loadCpptRanap = function(pregid, page = 1) {

                materialadmin.AppCard.addCardLoader();
                $('#list_soap').load(base_url() + 'pengkajian/load_soap_pasien_ranap/' + pregid + '/?page=' + page,
                    '',
                    function(response, status, xhr) {

                        if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                        materialadmin.AppCard.removeCardLoader();
                    });
            }

            // Taufiq 21 Feb 23
            p.loadCpptRanapGizi = function(pregid, element) {

                materialadmin.AppCard.addCardLoader();
                $('#list_soap').load(base_url() + 'pengkajian/load_soap_pasien_ranap_gizi/' + pregid, '', function(
                    response, status, xhr) {

                    if (status == "error") console.log(xhr.status + " " + xhr.statusText);

                    materialadmin.AppCard.removeCardLoader();
                });
            }

            p.getInfoBill = function(pregid) {
                $('#get_info_bill').addClass('fa-spin');
                $.ajax({
                    type: "POST",
                    url: base_url() + 'pengkajian/get_info_billing/',
                    data: {
                        pregid
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        $('#info_billing').text(data.total_all).prop('title',
                            `Billing: ${data.billing}, Proses Order: ${data.order}`);
                    },
                }).fail(function() {
                    console.log('get info bill gagal')
                }).always(function() {
                    $('#get_info_bill').removeClass('fa-spin');
                });
            }

            //tambahan rizal
            p.getInfoAlergy = function(pid) {
                // $('#get_info_bill').addClass('fa-spin');
                $.ajax({
                    type: "POST",
                    url: base_url() + 'pengkajian/get_info_alergy/',
                    data: {
                        pid
                    },
                    success: function(data) {
                        data = JSON.parse(data);
                        if (data.alergy != '') {
                            var status =
                                '<p style="color:red; animation: blinker 0.8s linear infinite;">Ada Alergi</p>';
                            $('.detail-alergi').html(status);
                        } else {
                            var status = 'Tidak ada alergi';
                            $('.detail-alergi').text(status);
                        }
                        // $('.detail-alergi').text(status);
                    },
                    // }).fail(function (){console.log('get info alergi gagal')}).always(function(){$('#get_info_bill').removeClass('fa-spin');});
                }).fail(function() {
                    console.log('get info alergi gagal')
                });
            }

            window.atmedic = window.atmedic || {};
            window.atmedic.App = new App;

        }(jQuery));

        (function() {

            $(window).load(function() {
                setTimeout(function() {
                    $("body").addClass("loaded")
                }, 200)
            });

            if (typeof(Waves) !== 'undefined') {
                Waves.attach('.btn:not(.btn-icon):not(.btn-float)');
                Waves.attach('.btn-icon, .btn-float', ['waves-circle', 'waves-float']);
                Waves.init();
            }

            $('.cx-wrap').on('click', '.toggle-sidebarcx, #cxsidebar-overlay', function() {
                $(".cx-wrap").toggleClass("minibar");
                $('.toggle-sidebarcx > i').toggleClass('mdi-flip-h');
            });

            /*$('#popupcx').on('hidden.bs.modal', function (e) {
                $('#bodycx').html('');
            });

            $('#popupcx2').on('hidden.bs.modal', function (e) {
                $('#bodycx2').html('');
                $('.modal-title').html('');
            });*/
        })();
    </script>


    <div style="width: 100%; height: 2px; z-index: 9999; top: 0px; float: left; position: fixed;">
        <div
            style="background-color: rgb(123, 31, 162); width: 0px; height: 100%; clear: both; transition: height 0.3s; float: left;">
        </div>
    </div>
    <div id="device-breakpoints">
        <div class="device-xs visible-xs" data-breakpoint="xs"></div>
        <div class="device-sm visible-sm" data-breakpoint="sm"></div>
        <div class="device-md visible-md" data-breakpoint="md"></div>
        <div class="device-lg visible-lg" data-breakpoint="lg"></div>
    </div>
</body>

</html>
