<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BeneficiarioTitular;
use Carbon\Carbon;

class VerificarEdadBeneficiarios extends Command
{
    /**
     * Nombre del comando para llamar por consola o CRON.
     */
    protected $signature = 'beneficiarios:verificar-edad';

    /**
     * Descripción visible al usar "php artisan list".
     */
    protected $description = 'Actualiza automáticamente el estado de beneficiarios hijos que cumplieron 28 años';

    /**
     * Lógica del comando.
     */
    public function handle()
    {
        $hoy = Carbon::now();

        // Solo hijos
        $beneficiarios = BeneficiarioTitular::whereIn('parentesco', [
            'HIJO', 'HIJA', 'HIJASTRO', 'HIJASTRA'
        ])->get();

        $contador = 0;

        foreach ($beneficiarios as $b) {
            
            if (!$b->fecha_nacimiento) {
                continue;
            }

            $edad = Carbon::parse($b->fecha_nacimiento)->age;

            if ($edad >= 28 && $b->estado_beneficiario !== 'BAJA') {
                $b->estado_beneficiario = 'BAJA';
                $b->observacion = 'Baja automática por superar los 28 años.';
                $b->saveQuietly();

                $contador++;
            }
        }

        $this->info("Actualización completada. Beneficiarios dados de baja: {$contador}");
        return Command::SUCCESS;
    }
}
