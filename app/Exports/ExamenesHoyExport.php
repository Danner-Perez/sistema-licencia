<?php

namespace App\Exports;

use App\Models\Examen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class ExamenesHoyExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $hoy = Carbon::today();

        return Examen::with([
                'postulante',
                'postulante.procesoActivo',
                'postulante.ultimaVerificacion'
            ])
            ->whereDate('fecha', $hoy)
            ->get()
            ->map(function ($examen) {
                return [
                    'DNI'            => $examen->postulante->dni,
                    'Nombre'         => "{$examen->postulante->nombres} {$examen->postulante->apellidos}",
                    'Licencia'       => optional($examen->postulante->procesoActivo)->tipo_licencia ?? '—',
                    'Placa'          => optional($examen->postulante->ultimaVerificacion)->placa ?? '—',
                    'Resultado'      => $examen->resultado,
                    'Fecha Examen'   => $examen->fecha->format('d/m/Y'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'DNI',
            'Nombre',
            'Licencia',
            'Placa',
            'Resultado',
            'Fecha Examen',
        ];
    }
}
