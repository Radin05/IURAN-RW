@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DynaPuff:wght@400..700&display=swap" rel="stylesheet">

    <style>
        @import url(https://fonts.googleapis.com/css?family=Oswald:400, 700);

        a.btn {
            position: relative;
            z-index: 10;
        }

        .here,
        .i {
            height: 100%;
            width: 100%;
            margin: 0;
            padding: 0;
            background: #242424;
            font-family: 'Oswald', sans-serif;
            background: -webkit-canvas(noise);
            background: -moz-element(#canvas);
            overflow: hidden;
        }

        .here::after {
            content: '';
            background: radial-gradient(circle, rgba(114, 111, 111, 0), rgba(0, 0, 0, 1));
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        .center {
            height: 400px;
            width: 500px;
            position: absolute;
            top: calc(50% - 200px);
            left: calc(50% - 250px);
            text-align: center;
        }

        h1,
        h3,
        p {
            font-family: "DynaPuff", system-ui;
            font-optical-sizing: auto;
            font-weight: <weight>;
            font-style: normal;
            font-variation-settings: "wdth" 100;
            margin: 0;
            padding: 0;
            -webkit-animation: funnytext 4s ease-in-out infinite;
            animation: funnytext 4s ease-in-out infinite;
        }

        h1 {
            font-size: 16rem;
            color: rgb(250, 8, 8);
            -webkit-filter: blur(3px);
            filter: blur(3px);
        }

        h3  {
            font-size: 3rem;
        }

        p {
            font-family: "DynaPuff", system-ui;
            font-optical-sizing: auto;
            font-size: 1.8rem;
            margin-bottom: 30px;
            color: rgb(240, 27, 27);
        }

        .i::after,
        .i::before {
            content: ' ';
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            top: -4px;
            height: 4px;
            -webkit-animation: scanline 8s linear infinite;
            animation: scanline 8s linear infinite;
            opacity: 0.33;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.5) 90%, rgba(0, 0, 0, 0)), -webkit-canvas(noise);
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.5) 90%, rgba(0, 0, 0, 0)), -moz-element(#canvas);
        }

        .i::before {
            -webkit-animation-delay: 4s;
            animation-delay: 4s;
        }

        @-webkit-keyframes scanline {
            0% {
                top: -5px;
            }

            100% {
                top: 100%;
            }
        }

        @keyframes scanline {
            0% {
                top: -5px;
            }

            100% {
                top: 100%;
            }
        }

        @-webkit-keyframes funnytext {
            0% {
                color: rgba(0, 0, 0, 0.3);
                -webkit-filter: blur(3px);
            }

            30% {
                color: rgba(0, 0, 0, 0.5);
                -webkit-filter: blur(1px);
            }

            65% {
                color: rgba(0, 0, 0, 0.2);
                -webkit-filter: blur(5px);
            }

            100% {
                color: rgba(0, 0, 0, 0.3);
                -webkit-filter: blur(3px);
            }
        }

        @keyframes funnytext {
            0% {
                color: rgba(0, 0, 0, 0.3);
                filter: blur(3px);
            }

            30% {
                color: rgba(0, 0, 0, 0.5);
                filter: blur(1px);
            }

            65% {
                color: rgba(0, 0, 0, 0.2);
                filter: blur(5px);
            }

            100% {
                color: rgba(0, 0, 0, 0.3);
                filter: blur(3px);
            }
        }
    </style>

    <div class="here">
        <div class="i">
            <canvas id="canvas" hidden></canvas>
            <div class="center">
                <h1>403</h1>

                <h3>
                    Forbidden
                </h3>

                <p>
                    Tidak Ada Akses Pada Halaman Ini.
                </p>

                @if (Auth::user()->role === 'superadmin')
                    <a href="{{ url('/superadmin') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                @elseif(Auth::user()->role === 'admin')
                    <a href="{{ url('/admin') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                @elseif(Auth::user()->role === 'user')
                    <a href="{{ url('/user') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                @endif

            </div>
        </div>
    </div>



    <script>
        var canvas = document.getElementById('canvas'),
            context = canvas.getContext('2d'),
            height = canvas.height = 256,
            width = canvas.width = height,
            bcontext = 'getCSSCanvasContext' in document ? document.getCSSCanvasContext('2d', 'noise', width, height) :
            context;
        noise();

        function noise() {
            requestAnimationFrame(noise);
            var idata = context.getImageData(0, 0, width, height);
            for (var i = 0; i < idata.data.length; i += 4) {
                idata.data[i] = idata.data[i + 1] = idata.data[i + 2] = Math.floor(Math.random() * 255);
                idata.data[i + 3] = 255;
            }
            bcontext.putImageData(idata, 0, 0);
        }
    </script>

@endsection
