<?php

namespace App\Http\Controllers;

use App\Models\Poster;
use App\Models\Comment;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportWord()
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Добавляем статистику
        $section->addText('Общее количество постов: ' . Poster::count());
        $section->addText('Общее количество комментариев: ' . Comment::count());
        $section->addText('Общее количество пользователей: ' . User::count());
        $section->addText('Общее количество просмотров: ' . Poster::sum('views'));
        $section->addText('Средняя оценка: ' . Rating::avg('rank'));

        // Добавляем таблицу с данными из модели Poster
        $section->addTextBreak(1);
        $section->addText('Таблица постов');
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText('ID');
        $table->addCell(2000)->addText('Title');
        $table->addCell(2000)->addText('Views');
        $table->addCell(2000)->addText('Created At');

        $posters = Poster::all();
        foreach ($posters as $poster) {
            $table->addRow();
            $table->addCell(2000)->addText($poster->id);
            $table->addCell(2000)->addText($poster->title);
            $table->addCell(2000)->addText($poster->views);
            $table->addCell(2000)->addText($poster->created_at);
        }

        // Добавляем таблицу с данными из модели User
        $section->addTextBreak(1);
        $section->addText('Таблица пользователей');
        $table = $section->addTable();
        $table->addRow();
        $table->addCell(2000)->addText('ID');
        $table->addCell(2000)->addText('Name');
        $table->addCell(2000)->addText('Email');
        $table->addCell(2000)->addText('Created At');

        $users = User::all();
        foreach ($users as $user) {
            $table->addRow();
            $table->addCell(2000)->addText($user->id);
            $table->addCell(2000)->addText($user->name);
            $table->addCell(2000)->addText($user->email);
            $table->addCell(2000)->addText($user->created_at);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('stat.docx');

        return response()->download(public_path('stat.docx'))->deleteFileAfterSend(true);
    }

    public function exportExcel()
    {
        $posters = Poster::all();
        $users = User::all();

        $data = [
            ['Общее количество постов', Poster::count()],
            ['Общее количество комментариев', Comment::count()],
            ['Общее количество пользователей', User::count()],
            ['Общее количество просмотров', Poster::sum('views')],
            ['Средняя оценка', Rating::avg('rank')],
        ];

        return Excel::download(new \App\Exports\StatExport($data, $posters, $users), 'stat.xlsx');
    }
}