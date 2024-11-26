<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class StatExport implements WithMultipleSheets
{
    use Exportable;

    protected $data;
    protected $posters;
    protected $users;

    public function __construct(array $data, $posters, $users)
    {
        $this->data = $data;
        $this->posters = $posters;
        $this->users = $users;
    }

    public function sheets(): array
    {
        $sheets = [];

        // Добавляем лист со статистикой
        $sheets[] = new StatSheet($this->data);

        // Добавляем лист с данными из модели Poster
        $sheets[] = new PosterSheet($this->posters);

        // Добавляем лист с данными из модели User
        $sheets[] = new UserSheet($this->users);

        return $sheets;
    }
}

class StatSheet implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Параметр',
            'Значение',
        ];
    }
}

class PosterSheet implements FromArray, WithHeadings
{
    protected $posters;

    public function __construct($posters)
    {
        $this->posters = $posters;
    }

    public function array(): array
    {
        return $this->posters->map(function ($poster) {
            return [
                $poster->id,
                $poster->title,
                $poster->views,
                $poster->created_at,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Views',
            'Created At',
        ];
    }
}

class UserSheet implements FromArray, WithHeadings
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function array(): array
    {
        return $this->users->map(function ($user) {
            return [
                $user->id,
                $user->name,
                $user->email,
                $user->created_at,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Created At',
        ];
    }
}