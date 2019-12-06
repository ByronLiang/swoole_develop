<?php

namespace App\Exports;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class BaseExport implements FromCollection, Responsable, ShouldAutoSize, WithHeadings
{
    use Exportable;

    /**
     * @var array
     */
    private $headings;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $collection;

    /**
     * @var string
     */
    private $fileName;

    public function __construct(\Illuminate\Support\Collection $collection, string $fileName, array $headings = [])
    {
        $this->collection = $collection;
        $this->fileName = $fileName;
        $this->headings = $headings;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        if (!empty($this->headings)) {
            return $this->headings;
        }

        $firstRow = $this->collection->first();

        if ($firstRow instanceof Arrayable || \is_object($firstRow)) {
            return array_keys(Sheet::mapArraybleRow($firstRow));
        }

        return $this->collection->collapse()->keys()->all();
    }
}
