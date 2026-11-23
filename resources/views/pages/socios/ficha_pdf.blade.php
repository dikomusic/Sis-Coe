<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha del Socio Titular</title>
    <style>
        

    @page {
        margin-left: 3cm;
        margin-right: 1.5cm;
        margin-top: 1.5cm;
        margin-bottom: 2.5cm;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        background: #f8f9fa;
        margin: 0;
        padding: 0;
    }

    .footer {
        position: fixed;
        bottom: -10px;
        left: 0;
        right: 0;
        height: 50px;
        font-size: 11px;
        color: #555;
    }

    .footer-table {
        width: 100%;
    }

    .footer-left {
        text-align: left;
    }

    .footer-right {
        text-align: right;
    }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 5px 25px rgba(0,0,0,0.15);
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        .header {
            background: #0d1b2a;
            color: white;
            padding: 5px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .id-socio {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 13px;
            font-weight: bold;
            background: #0d6efd;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            border: 2px solid white;
        }
                .content {
            padding: 30px;
            display: grid;
            grid-template-columns: 240px 1fr 1fr;
            gap: 30px;
        }
        .foto {
            text-align: center;
        }
        .foto img {
            width: 220px;
            height: 280px;
            object-fit: cover;
            border: 5px solid #007bff;
            border-radius: 12px;
        }
        .badge {
            background: #007bff;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 14px;
            margin-top: 10px;
            display: inline-block;
            text-align: center;
        }
        .info-column {
            font-size: 16px;
        }
        .info-column strong {
            color: #333;
            display: block;
            margin-bottom: 4px;
        }
        .documento {
            margin-top: 20px;
            padding: 5px;
            border: 4px solid #ddd;
            border-radius: 10px;
            text-align: center;
        }
        .documento img {
            max-width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        h2 {
            color: #007bff;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

        <div class="id-socio">
            ID DOC.: {{ $socio->id_titular }}
        </div><br><br>

<div class="container">

    <!-- Header -->
    <div class="header">
        <h1>CÍRCULO DE OFICIALES DEL EJÉRCITO</h1>
    </div>

<div style="width:100%; text-align:center; margin:15px 0;">
    <span class="badge">SOCIO TITULAR</span>
</div>
<table width="100%" cellspacing="0" cellpadding="10" style="margin-top:20px;">
    <tr>

        <!-- COLUMNA 1: FOTO -->
        <td width="25%" align="center" valign="top">
            @if($socio->foto)
                @php
                    $path = storage_path('app/public/' . $socio->foto);
                    if(file_exists($path)){
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $base64 = null;
                    }
                @endphp

                @if($base64)
                    <img src="{{ $base64 }}"
                         style="width:140px; height:180px; object-fit:cover; border:4px solid #0d6efd; border-radius:8px;">
                    <br><br>
                    <strong>Situación</strong><br>
                        <span style="background:#28a745; color:white; padding:4px 10px; border-radius:10px;">
                             {{ $socio->situacion_actual ?? 'ACTIVO' }}
                        </span>

                @else
                    <p>Imagen no encontrada</p>
                @endif
            @else
                <p>Sin foto</p>
            @endif
        </td>

        <!-- COLUMNA 2: DATOS PRINCIPALES -->
        <td width="37%" valign="top">
            
            <strong>ID Titular</strong><br>
            {{ $socio->id_titular }}<br>

            <strong>Código COE</strong><br>
            {{ $socio->codigo_cossmil ?? 'No registrado' }}<br><br>

            <strong>Grado</strong><br>
            {{ $socio->grado ?? 'No registrado' }} {{ $socio->arma_especialidad ?? 'No registrado' }}<br>

            <strong>Nombre Completo</strong><br>
            <span style="font-size:16px; font-weight:bold;">
                {{ $socio->paterno }} {{ $socio->materno }}, {{ $socio->nombres }}
            </span><br>

            <strong>C.I.</strong><br>
            {{ $socio->ci ?? 'No registrado' }}<br>

        </td>

        <!-- COLUMNA 3: DATOS ADICIONALES -->
        <td width="38%" valign="top">

            <strong>Código Cossmil</strong><br>
            {{ $socio->codigo_cossmil ?? 'No registrado' }}<br>

            <strong>Tipo de Sangre</strong><br>
            {{ $socio->tipo_sangre ?? 'No registrado' }}<br><br>

            <strong>Fecha Alta Ejército</strong><br>
            {{ $socio->fecha_alta_ejercito ?? 'No registrado' }}<br>

            <strong>Fecha Alta COE</strong><br>
            {{ $socio->fecha_alta_coe ?? 'No registrado' }}<br>

            <strong>Estado Socio</strong><br>
            {{ $socio->estado_socio ?? 'No registrado' }}
        </td>

    </tr>
</table>

    <!-- Sección Documento Cossmil -->
    <div class="documento">
        <h2>DOCUMENTO </h2>
        
        @if($socio->fotoCossmil && $socio->fotoCossmil->ruta_archivo)
            @php
                $path = storage_path('app/public/' . $socio->fotoCossmil->ruta_archivo);
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = null;
                }
            @endphp

            @if($base64)
                <img src="{{ $base64 }}" style="max-width: 70%; border: 2px solid #ddd;">
            @else
                <p style="color:red;">No se pudo cargar la imagen del documento Cossmil</p>
            @endif
        @else
            <p style="color:gray; font-style:italic;">No hay documento Cossmil registrado para este socio.</p>
        @endif
    </div>

</div>
<div class="footer">
    <table class="footer-table">
        <tr>
            <td class="footer-left">
                Sistema COE | Impreso el: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
            </td>
            <td class="footer-right">
                Página <span class="page"></span> de <span class="topage"></span>
            </td>
        </tr>
    </table>
    </div>
<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(500, 820, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0,0,0));
    }
</script>
</body>
</html>