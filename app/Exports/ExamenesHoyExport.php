<?php

namespace App\Exports;

use App\Models\Examen;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExamenesHoyExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        $hoy = Carbon::today();

        return Examen::with(['postulante', 'postulante.procesoActivo'])
            ->whereDate('fecha', $hoy)
            ->get()
            ->values() // 🔴 CLAVE: reinicia índices (N° empieza en 1)
            ->map(function ($examen, $key) {

                $postulante = $examen->postulante;

                // Apellidos
                $apellidos = explode(' ', $postulante->apellidos ?? '', 2);
                $primerApellido  = $apellidos[0] ?? '';
                $segundoApellido = $apellidos[1] ?? '';

                // Proceso activo
                $proceso = $postulante->procesoActivo;

                // Clase fija
                $clase = 'A';

                // Categoría (A-IIb → IIB)
                $categoria = '';
                if ($proceso && $proceso->tipo_licencia) {
                    $categoria = strtoupper($proceso->tipo_licencia);
                    if (str_starts_with($categoria, 'A-')) {
                        $categoria = substr($categoria, 2);
                    }
                }

                // Trámite
                $tramite = $proceso->tipo_tramite ?? '';

                // Fecha inicial
                $fInicial = $postulante->fecha_psicosomatico
                    ? Carbon::parse($postulante->fecha_psicosomatico)->format('d/m/Y')
                    : '';

                // Días restantes
                $diasParaVencer = $postulante->diasRestantesPsicosomatico();

                return [
                    $key + 1, // ✅ Numeración correcta
                    $primerApellido,
                    $segundoApellido,
                    $postulante->nombres ?? '',
                    $postulante->dni,
                    $clase,
                    $categoria,
                    $tramite,
                    $fInicial,
                    $diasParaVencer,
                    $examen->resultado,
                ];
            });
    }

    public function headings(): array
    {
        // No se usan (se crean manualmente)
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /** ───────── TITULOS ───────── */
                $sheet->insertNewRowBefore(1, 2);

                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'RELACION DE POSTULANTES PARA EL EXAMEN DE MANEJO PRACTICO (HABILIDADES)');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(17)->setName('Arial Black');
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('A2:K2');
                $sheet->setCellValue(
                    'A2',
                    'CIRCUITO DE MANEJO PATALLANI ' . Carbon::today()->format('d/m/Y') . ' HORA 11:00 AM'
                );
                $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(16)->setName('Arial');
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /** ───────── CABECERAS ───────── */
                $sheet->mergeCells('A3:A4');
                $sheet->mergeCells('B3:B4');
                $sheet->mergeCells('C3:C4');
                $sheet->mergeCells('D3:D4');
                $sheet->mergeCells('E3:E4');
                $sheet->mergeCells('F3:H3');
                $sheet->mergeCells('I3:I4');
                $sheet->mergeCells('J3:J4');
                $sheet->mergeCells('K3:K4');

                $sheet->setCellValue('A3', 'N°');
                $sheet->setCellValue('B3', 'PRIMER APELLIDO');
                $sheet->setCellValue('C3', 'SEGUNDO APELLIDO');
                $sheet->setCellValue('D3', 'NOMBRES');
                $sheet->setCellValue('E3', 'DNI');
                $sheet->setCellValue('F3', 'TIPO DE TRAMITE');
                $sheet->setCellValue('I3', 'F. INICIAL');
                $sheet->setCellValue('J3', 'DIAS PARA VENCER');
                $sheet->setCellValue('K3', 'RESULTADO');

                $sheet->setCellValue('F4', 'CLASE');
                $sheet->setCellValue('G4', 'CAT.');
                $sheet->setCellValue('H4', 'TRAMITE');

                $sheet->getStyle('A3:K4')->getFont()->setBold(true);
                $sheet->getStyle('A3:K4')->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A3:K4')->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('C0C0C0');

                /** ───────── TABLA ───────── */
                $sheet->freezePane('A5');

                foreach (range('A', 'K') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $highestRow = $sheet->getHighestRow();

                // Bordes
                $sheet->getStyle("A3:K{$highestRow}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                /** ───────── CENTRADO DE DATOS ───────── */
                $sheet->getStyle("E5:E{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // DNI
                $sheet->getStyle("F5:H{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Tipo trámite
                $sheet->getStyle("I5:I{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // F. Inicial
                $sheet->getStyle("J5:J{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Días
                $sheet->getStyle("K5:K{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Resultado
            }
        ];
    }
}
