<?php

namespace App\Imports;

use App\Rules\StrongPassword;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithBatchInserts, WithChunkReading, WithValidation, WithHeadingRow, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    /**
     * @var
     */
    protected $timestamp;

    /**
     * Keep count of inserted rows
     * @var int
     */
    public $importedCount = 0;

    /**
     * UsersImport constructor.
     */
    public function __construct()
    {
        $this->timestamp = now();
    }

    /**
     * @param array $row
     * @return Model|null
     */
    public function model(array $row)
    {
        $this->importedCount++; // increment the inserted rows count
        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'organization_name' => $row['organization_name'] ?? null,
            'job_title' => $row['job_title'] ?? null,
            'email' => $row['email'],
            'password' => Hash::make($row['password']),
            'remember_token' => Str::random(64),
            'email_verified_at' => $this->timestamp
        ]);
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.first_name' => 'required|string|max:255',
            '*.last_name' => 'required|string|max:255',
            '*.organization_name' => 'max:255',
            '*.job_title' => 'max:255',
            '*.email' => 'required|email|max:255|unique:users,email',
            '*.password' => ['required', 'string', new StrongPassword],
        ];
    }
}
