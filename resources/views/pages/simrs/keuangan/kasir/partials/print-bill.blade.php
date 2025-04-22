<!DOCTYPE html>
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
        <style>
            .print_function {
                margin: 0;
            }

            .print {
                font-size: 1.1em;
                letter-spacing: 7px;

                .print .kop {
                    overflow: hidden;
                    line-height: 1em;
                }

                .print .kop .hif {
                    padding: 10px;
                    padding-top: 0;
                    font-family: Arial;
                }

                .print .kop .lbr {
                    float: right;
                    font-family: Arial;
                    font-size: .5em;
                    margin: 5px 0;
                }

                .print .kop img {
                    max-width: 100px;
                    float: left;
                    margin-right: 10px;
                }

                .print .kop span {
                    display: block;
                }

                .print .kop span.tit {
                    font-size: .7em;
                    letter-spacing: 1px;
                }

                .print .kop span.hnm {
                    font-size: 1em;
                    letter-spacing: 1px;
                }

                .print .kop span.addr {
                    font-size: .6em;
                    line-height: 1em;
                    margin-left: 20px;
                }

                .print h1 {
                    font-family: Arial;
                    font-size: 0.8em;
                    border-bottom: 3px double #000;
                    margin-top: 0;
                    padding: 5px 0;
                }

                .print h1 span {
                    font-size: .5em;
                }

                .print h1 div {
                    color: #000;
                    font-size: .6em;
                    font-weight: bold;
                    border: 1px solid #000;
                    padding: 0 0 0 5px;
                    background: #eee;
                    margin-top: -7px;
                    float: right;
                }

                .print h1 div span {
                    font-size: 1em;
                    font-weight: normal;
                    border-left: 1px solid #000;
                    display: inline-block;
                    background: #fff;
                    padding: 5px;
                    margin-left: 5px;
                }

                .print .pat {
                    font-family: Arial, sans-serif;
                    font-size: 1em;
                }

                .print .pat span {
                    display: block;
                    font-size: .6em;
                    padding-bottom: 10px;
                    margin-bottom: 5px;
                    border-bottom: 1px solid #ccc;
                    text-transform: uppercase;
                }

                .print .pat span.kls {
                    font-size: .6em;
                    border-bottom: none;
                    padding: 5px 0;
                    color: #666;
                }

                .print .pat .mrn {
                    font-size: .6em;
                    float: right;
                    text-align: right;
                    text-transform: uppercase;
                }

                .print .pat .mrn span {
                    font-size: 1.8em;
                    border-bottom: none;
                    padding-top: 15px;
                }

                .print .cleared td {
                    padding: 2px;
                }

                .print table {
                    border-collapse: collapse;
                }

                .label {
                    border-bottom: none !important;
                }

                .print #list thead {
                    border-top: 3px double #000 !important;
                    border-bottom: 3px double #000 !important;
                }

                .print #list tr {}

                .print #list th {
                    padding: 4px;
                    background: #eee;
                    color: #000;
                    font-size: 0.6em;
                }

                .print #list td {
                    padding: 2px;
                    font-size: 0.6em;
                }

                .print #list td span {
                    font-size: 1em;
                }

                body {
                    background: #fff;
                }
        </style>
        <script type="text/javascript">
            function printPage() {
                document.getElementById('divButtons').style.display = 'none';
                window.print();
                document.getElementById('divButtons').style.display = '';
                return false;
            }

            function show_setting() {
                if (document.getElementById('id_show').value == 'n') {
                    document.getElementById('id_show').value = 'y';
                    document.getElementById('dsetting').style.display = '';
                } else {
                    document.getElementById('id_show').value = 'n';
                    document.getElementById('dsetting').style.display = 'none';
                }
            }
        </script>
        <div class="print_function" id="divButtons">
            <input type="button" value="Print" onclick="printPage();">
            <input type="button" value="Close" onclick="JavaScript:window.close();">
            <input name="id_show" id="id_show" type="hidden" value="n">
            <input id="bsetting" name="bsetting" type="button" value="Show/Hide Settings" onclick="show_setting();">
        </div>
        <div class="setfloat" id="dsetting" style="display:none;">
            <form style="width:30%;" class="cxform" id="frm" name="frm" method="post">
                @csrf
                <div class="tableTitle frm">Print Settings</div>
                <table width="100%">
                    <tbody>
                        <tr>
                            <td class="label">Rekap OK</td>
                            <td>
                                <input name="rekap_ok" type="radio" value="yes" checked="checked"> Ya
                                <input name="rekap_ok" type="radio" value="no"> Tidak
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Rekap VK</td>
                            <td>
                                <input name="rekap_vk" type="radio" value="yes" checked="checked"> Ya
                                <input name="rekap_vk" type="radio" value="no"> Tidak
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Rekap ALL</td>
                            <td>
                                <input name="rekap_all" type="radio" value="yes"> Ya
                                <input name="rekap_all" type="radio" value="no" checked="checked"> Tidak
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input name="submit" type="submit" value="Reload">
            </form>
        </div>
        <div class="print">
            <div class="kop">
                <div class="hif">
                    <span class="hnm" style="letter-spacing:8px"><strong>Rumah Sakit Livasya</strong></span>
                    <span class="addr"><br>Kab. Majalengka Jawa Barat - Indonesia <br>Telepon: 081211151300</span>
                </div>
            </div>
            <h1>
                <div>
                    INV NUMBER
                    <span><strong>{{ $bilingan->invoice_number }}</strong></span>
                </div>
                {{ $bilingan->registration_type == 'rawat-jalan' ? 'INVOICE RAWAT JALAN' : 'INVOICE PEMBAYARAN' }}
                <input name="vprint" id="vprint" type="text" align="right" value="(ASLI)"
                    style="border:none;font-size: 1em" size="6">
            </h1>
            <table width="100%" class="cleared" style="margin-top: 0px; font-size: 0.8em;">
                <tbody>
                    <tr>
                        <td width="17%"><strong>Tanggal Reg</strong></td>
                        <td width="27%">: {{ \Carbon\Carbon::parse($bilingan->registration_date)->format('d M Y') }}
                        </td>
                        <td width="18%"><strong>Nama Pasien</strong></td>
                        <td width="38%">: {{ $bilingan->registration->patient->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Keluar</strong></td>
                        <td>: {{ \Carbon\Carbon::parse($bilingan->discharge_date)->format('d M Y') }}</td>
                        <td><strong>No. RM/No. Registrasi</strong></td>
                        <td>: {{ $bilingan->registration->patient->medical_record_number }} /
                            {{ $bilingan->registration->registration_number }}</td>
                    </tr>
                    <tr>
                        <td><strong>Penjamin</strong></td>
                        <td>: {{ $bilingan->registration->penjamin->nama_perusahaan }}</td>
                        <td><strong>Dokter Penanggung Jawab</strong></td>
                        <td>: {{ $bilingan->registration->doctor->employee->fullname }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ $bilingan->registration->registration_type === 'rawat-jalan' ? 'KLINIK' : 'KELAS' }}</strong>
                        </td>
                        <td>:
                            {{ $bilingan->registration->registration_type === 'rawat-jalan' ? $bilingan->registration->departement->name : $bilingan->registration->kelas_rawat->kelas }}
                        </td>
                        </td>
                        <td><strong></strong></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <table width="100%" id="list" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th colspan="4">KETERANGAN</th>
                        <th width="10%">JML</th>
                        <th width="15%" align="center">HARGA</th>
                        <th width="15%" align="right">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bilingan->tagihan_pasien as $item)
                        <tr style="font-size:1.3em; font-weight: bold;">
                            @if (strpos($item->tagihan, 'Biaya Administrasi') !== false)
                                <td colspan="6">&nbsp;&nbsp;Administrasi</td>
                            @elseif (strpos($item->tagihan, 'Tindakan Medis') !== false)
                                <td colspan="6">&nbsp;&nbsp;Biaya Tindakan Medis</td>
                            @else
                                <td colspan="6">&nbsp;&nbsp;{{ $item->tagihan }}</td>
                            @endif
                            <td align="right"><a style="display:none"></a></td>
                            </td>
                        </tr>
                        <tr font-size:1.3em;">
                            <td colspan="4">
                                <span style="display: block;"> &nbsp; &nbsp;{{ $item->tagihan }}</span>
                            </td>
                            <td align="center" style="letter-spacing: 2px;">{{ $item->quantity }}</td>
                            <td align="right" style="letter-spacing: 2px;">
                                {{ number_format($item->nominal_awal, 0, ',', '.') }}</td>
                            <td align="right" style="letter-spacing: 2px;">
                                {{ number_format($item->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr style="font-size:1.2em; font-weight: bold; text-align:right;">
                            <td colspan="6">Subtotal</td>
                            <td style="letter-spacing: 2px;">{{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="border-top: 3px double #000;">
                        <td colspan="8" style="padding:1px;"></td>
                    </tr>
                    <tr style="font-size:1.2em; font-weight: bold; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2" style="letter-spacing: 2px;">Total Tagihan :</td>
                        <td align="right" style="letter-spacing: 2px;">
                            {{ number_format($bilingan->wajib_bayar, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="font-size:1.2em; font-weight: bold; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">Deposit :</td>
                        <td>
                        </td>
                    </tr>
                    @foreach ($bilingan->down_payment as $deposit)
                        <tr style="text-align: right; font-size: 1.2em; letter-spacing: 4px;">
                            <td colspan="4">&nbsp;</td>
                            <td style="text-align: left;" colspan="2">
                                <span
                                    style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;[{{ \Carbon\Carbon::parse($deposit->created_at)->format('d M Y H:i') }}]</span>
                                {{ $deposit->metode_pembayaran }}
                            </td>
                            <td style="font-weight: bold;">
                                {{ number_format($deposit->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                    <tr style="font-size:1.2em; font-weight: bold; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">Pembayaran :</td>
                        <td></td>
                    </tr>
                    <tr style="text-align: right; font-size: 1.2em; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td style="text-align: left;" colspan="2">
                            <span
                                style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;[{{ \Carbon\Carbon::parse($bilingan->pembayaran_tagihan->created_at)->format('d M Y H:i') }}]</span>
                            Tunai
                            {{-- {{ $bilingan->pembayaran_tagihan->metode_pembayaran }} --}}
                        </td>
                        <td style="font-weight: bold;">
                            {{ number_format($bilingan->pembayaran_tagihan->jumlah_terbayar, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="text-align: right; font-size: 1.2em; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td style="text-align: left;" colspan="2">
                            @if ($bilingan->down_payment->isNotEmpty())
                                <span
                                    style="font-size: 0.9em;">&nbsp;&nbsp;&nbsp;&nbsp;[{{ \Carbon\Carbon::parse($bilingan->down_payment->first()->created_at)->format('d M Y H:i') }}]</span>
                                Dengan DP
                                {{-- {{ $bilingan->down_payment->first()->metode_pembayaran }} --}}
                            @endif
                        </td>
                        <td style="font-weight: bold;">
                            {{ number_format($bilingan->down_payment ? ($bilingan->down_payment->isNotEmpty() ? $bilingan->down_payment->where('tipe', 'Down Payment')->sum('nominal') : 0) : 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr style="font-size:1.2em; font-weight: bold; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">Sisa Tagihan :</td>
                        <td align="right">{{ number_format($bilingan->sisa_tagihan, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="font-size:1.2em; font-weight: bold; letter-spacing: 4px;">
                        <td colspan="4">&nbsp;</td>
                        <td colspan="2">Kembalian :</td>
                        <td align="right">{{ number_format($bilingan->pembayaran_tagihan->kembalian, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="7"></td>
                    </tr>
                    <tr style="border-top: 3px double #000; font-size: 1em;">
                        <td colspan="7" style="font-style: italic;">Dicetak Oleh :
                            {{ auth()->user()->employee->fullname }},
                            {{ \Carbon\Carbon::parse($bilingan->printed_at)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td colspan="7">
                            <table width="100%" style="font-size: 1em;">
                                <tbody>
                                    <tr>
                                        <td align="center">
                                            {{ $bilingan->location }},
                                            {{ \Carbon\Carbon::parse($bilingan->location_date)->format('d M Y') }}
                                            <span style="display: block; height: 40px;"></span>
                                            <span style="white-space: nowrap!important">
                                                <div>({{ auth()->user()->employee->fullname }})</div>
                                            </span>
                                            <div>PETUGAS</div>
                                        </td>
                                        <td align="center">
                                            {{ $bilingan->location }},
                                            {{ \Carbon\Carbon::parse($bilingan->location_date)->format('d M Y') }}
                                            <span style="display: block; height: 40px;"></span>
                                            <span style="white-space: nowrap!important">
                                                <div>({{ $bilingan->registration->patient->name }})</div>
                                            </span>
                                            <div>PASIEN</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <script src="{{ asset('testing/include/styles/ma/js/App.js') }}"></script>
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
