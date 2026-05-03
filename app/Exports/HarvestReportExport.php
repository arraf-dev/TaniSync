<?php

namespace App\Exports;

use App\Models\HarvestLog;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class HarvestReportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    /**
     * @param  Collection<int, HarvestLog>  $rows
     */
    public function __construct(private readonly Collection $rows)
    {
    }

    /**
     * @return Collection<int, HarvestLog>
     */
    public function collection(): Collection
    {
        return $this->rows;
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Petani',
            'Komoditas',
            'Lokasi',
            'Jumlah',
            'Satuan',
            'Kualitas',
            'Status',
            'Catatan',
        ];
    }

    /**
     * @return array<int, mixed>
     */
    public function map($row): array
    {
        return [
            $row->harvest_date?->toDateString(),
            $row->user?->name,
            $row->commodity?->nama_komoditas,
            $row->location,
            (float) $row->quantity,
            $row->unit,
            $row->quality,
            $row->status,
            $row->note,
        ];
    }
}
