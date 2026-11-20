<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credencial SIS-COE</title>

    <!-- Google Fonts (opcional, pero queda más profesional) -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif;
            background: #0f172a;
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .container {
            text-align: center;
            width: 100%;
            max-width: 1100px;
        }

        .credencial {
            width: 1000px;
            height: 600px;
            /* Fondo original 100% sin degradado ni opacidad */
            background-image: url("{{ asset('storage/credenciales/' . ($tipo == 'socio' ? 'socio.png' : 'beneficiario.png')) }}");
            background-size: 100% 100%;      /* Cubre todo sin distorsión */
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            border: 6px solid #e30052;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(227, 0, 82, 0.35);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        /* Contenido encima del fondo */
        .content {
            position: relative;
            z-index: 1;
            padding: 40px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 42px;           /* Más grande como pediste */
            font-weight: 900;
            color: #000000;            /* Negro puro */
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 38px;
            font-weight:900;
            color: #000000;            /* Negro puro */
            letter-spacing: 3px;
        }

        .main {
            display: flex;
            gap: 50px;
            flex: 1;
        }

        .foto {
            flex: 0 0 240px;
        }
        .foto img {
            width: 300px;
            height: 320px;
            object-fit: cover;
            border-radius: 16px;
            border: 5px solid #e30052;
            box-shadow: 0 10px 30px rgba(0,0,0,0.7);
        }

        .info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0px;
            font-size: 45px;
            color: #000000;
            line-height: 1;
        }
        .info1 {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 08px;
            font-size: 40px;
            color: #000000;
        }
        .info2 {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 08px;
            font-size: 30px;
            color: #000000;
        }
        .info strong {
            font-size: 48px;
            font-weight:900;
            color: #000000;            /* Negro puro */
            letter-spacing: 0px;
        }

        .extra {
            margin-top: auto;
            font-size: 20px;
            line-height: 1.5;
        }

        .print-btn {
            background: #e30052;
            color: white;
            border: none;
            padding: 16px 48px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 25px rgba(227, 0, 82, 0.4);
        }
        .print-btn:hover {
            background: #c70047;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(227, 0, 82, 0.5);
        }

        @media print {
            body {
                background: white;
            }
            .print-btn {
                display: none;
            }
            .credencial {
                border: none;
                box-shadow: none;
                border-radius: 0;
                width: 100%;
                height: auto;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="credencial">
        <div class="content">
            <div class="header">
                <h1>
                    <br>
                    <br>
                </h1>

            </div>

            <div class="main">
                <div class="foto">
                    <img src="{{ asset('storage/' . ($model->foto ?? 'default-avatar.jpg')) }}" alt="Foto">
                    <div class="info1">CI:{{ $model->ci ?? 'No registrado' }}
                    </div>
                </div>

                <div class="info">


                    
                        @if($tipo == 'socio')
                                                <div>
                        <strong>{{ $model->grado ?? 'S/R' }} {{ $model->arma_especialidad ?? 'S/R' }}</strong><br>
                        {{ $model->paterno ?? '' }} {{ $model->materno ?? '' }}
                        </div>
                        <div>
                            {{ $model->nombres ?? '' }} <br style="margin-bottom: -12px;">
                        </div>
                     
                        
                        <div>
                            <strong></strong> {{ $model->tipo_sangre ?? 'No registrado' }}
                        </div>
                        <div>
                            <strong></strong> {{ $model->boleta_pago ?? 'No registrado' }}
                        </div>

                        <div>
                            <strong></strong> {{ $model->situacion_actual ?? 'No registrada' }}
                        </div>
                           
                        @else
                        <div>
                     
                        {{ $model->paterno ?? '' }} {{ $model->materno ?? '' }}
                        </div>
                        <div>
                            {{ $model->nombres ?? '' }} 
                        </div>
                        <br>
                        <div>
                            <strong>REG. COE:</strong> {{ $model->titular->boleta_pago ?? 'No registrado' }}
                        </div>

                        <div>
                            <strong>TIPO SANGRE:</strong> {{ $model->tipo_sangre ?? 'No registrado' }}
                        </div>

                        <div>
                        <strong>PARENTESCO:</strong> {{ $model->parentesco ?? 'No especificado' }}
                        </div>

                        @endif
                                            <!-- QR real en lugar del texto simulado -->
                        <!-- QR en la posición derecha (abajo a la derecha) -->
                        <div class="qr" style="position: absolute; bottom: 100px; right: 20px; width: 130px; height: 130px;">
                            {!! QrCode::size(130)                // tamaño un poco menor (160px)
                                ->margin(1)                   // margen alrededor para mejor legibilidad
                                ->color(0, 0, 0)              // negro
                                ->backgroundColor(255, 255, 255)  // fondo blanco
                                ->errorCorrection('H')        // corrección alta
                                ->generate($credencial->id)   // contenido: ID de la credencial
                            !!}
                        </div>
                        <br>
                        <br>
                        <div class="info2">
                         {{ \Carbon\Carbon::parse($credencial->fecha_vencimiento)->format('d/m/Y') }}
                       <!--Emitido: {{ \Carbon\Carbon::parse($credencial->fecha_emision)->format('d/m/Y') }}-->
                        </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Botón Imprimir -->
    <button class="print-btn" onclick="window.print()">
        <i class="bi bi-printer-fill me-2"></i> IMPRIMIR CREDENCIAL
    </button>
</div>

</body>
</html>