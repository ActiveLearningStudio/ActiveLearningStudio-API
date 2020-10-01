<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements FromArray, WithHeadings
{
    use Exportable;

    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $headings;

    /**
     * ArrayExport constructor.
     * @param $data
     * @param $headings
     */
    public function __construct($data, $headings)
    {
        $this->data = $data;     //Inject data
        $this->headings = $headings;     //custom headings
    }

    /**
     * @return array
     */
    public function array(): array // this was query() before
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }
}
