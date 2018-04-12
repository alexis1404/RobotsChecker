<?php

namespace App\Services;

use App\Audit;
use Excel;

class RobotMessageProcessorService
{
    public function form_message($check_status)
    {
        Excel::create('Audit__' . $check_status['valid_url'], function($excel) use ($check_status) {

            $excel->setTitle('Аудит');

            $excel->sheet('Данные аудита', function($sheet) use ($check_status){

                //list options
                $sheet->setSize([
                    'A1' => [
                        'width' => 10,
                        'height' => 30,
                    ],
                    'A3' => [
                        'width' => 10,
                        'height' => 30,
                    ],
                    'A5' => [
                        'width' => 10,
                        'height' => 30,
                    ],
                    'A7' => [
                        'width' => 10,
                        'height' => 30,
                    ],
                    'A9' => [
                        'width' => 10,
                        'height' => 30,
                    ],
                    'A11' => [
                        'width' => 10,
                        'height' => 30,
                    ]
                ]);

                $sheet->setSize([
                    'B1' => [
                        'width' => 50,
                        'height' => 40,
                    ],
                    'B3' => [
                        'width' => 50,
                        'height' => 40,
                    ],
                    'B5' => [
                        'width' => 50,
                        'height' => 40,
                    ],
                    'B7' => [
                        'width' => 50,
                        'height' => 40,
                    ],
                    'B9' => [
                        'width' => 50,
                        'height' => 40,
                    ],
                    'B11' => [
                        'width' => 50,
                        'height' => 40,
                    ]

                ]);

                $sheet->setSize([
                    'D1' => [
                        'width' => 60,
                        'height' => 40,
                    ],
                    'D3' => [
                        'width' => 60,
                        'height' => 40,
                    ],
                    'D5' => [
                        'width' => 60,
                        'height' => 40,
                    ],
                    'D7' => [
                        'width' => 60,
                        'height' => 40,
                    ],
                    'D9' => [
                        'width' => 60,
                        'height' => 40,
                    ],
                    'D11' => [
                        'width' => 60,
                        'height' => 40,
                    ]
                ]);

                $sheet->setSize([
                    'E1' => [
                        'width' => 90,
                        'height' => 40,
                    ],
                    'E3' => [
                        'width' => 90,
                        'height' => 40,
                    ],
                    'E5' => [
                        'width' => 90,
                        'height' => 40,
                    ],
                    'E7' => [
                        'width' => 90,
                        'height' => 40,
                    ],
                    'E9' => [
                        'width' => 90,
                        'height' => 40,
                    ],
                    'E11' => [
                        'width' => 90,
                        'height' => 40,
                    ]
                ]);

                //align for cells
                $sheet->cells('A1:A50', function($cells) {

                    $cells->setValignment('center');

                });

                $sheet->cells('B1:B50', function($cells) {

                    $cells->setValignment('center');

                });

                $sheet->cells('C1:C50', function($cells) {

                    $cells->setAlignment('center');

                });

                $sheet->cells('D1:D50', function($cells) {

                    $cells->setValignment('center');

                });

                $sheet->cells('E1:E50', function($cells) {

                    $cells->setValignment('center');

                });

                //create xls
                if($check_status['no_robots']){

                    $sheet->row(1, [
                        '1',
                        'Проверка наличия файла robots.txt',
                        'Ошибка',
                        'Состояние: файл robots.txt отсутствует',
                        'Рекомендации (программист): создать файл robots.txt и размеситить его на сайте'
                    ]);

                    $sheet->cell('C1', function($cell) {

                        $cell->setBackground('#FF0000');

                    });

                }else{

                    $sheet->row(1, [
                        '1',
                        'Проверка наличия файла robots.txt',
                        'OK',
                        'Состояние: файл robots.txt присутствует',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C1', function($cell) {

                        $cell->setBackground('#90ee90');

                    });

                }

                if($check_status['statusfail'] && !$check_status['no_robots']) {
                    $sheet->row(3, [
                        '12',
                        'Проверка ответа кода сервера для файла robots.txt',
                        'Ошибка',
                        'Состояние: при обращении к файлу robots.txt сервер возвращает код ответа' . $check_status['statusfail'],
                        'Рекомендации (программист): файл robots.txt должен отдавать код ответа 200,
                        иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом,
                        чтобы при обращении к robots.txt сервер возвращал ответ 200'
                    ]);

                    $sheet->cell('C3', function($cell) {

                        $cell->setBackground('#FF0000');

                    });

                }elseif (!$check_status['statusfail'] && !$check_status['no_robots']){

                    $sheet->row(3, [
                        '12',
                        'Проверка ответа кода сервера для файла robots.txt',
                        'OK',
                        'Состояние: При обращении к robots.txt сервер отдает ответ 200',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C3', function($cell) {

                        $cell->setBackground('#90ee90');

                    });
                }

                if($check_status['hosts_dir'] && !$check_status['statusfail'] && !$check_status['no_robots']){

                    $sheet->row(5, [
                        '6',
                        'Проверка указания директивы Host',
                        'OK',
                        'Состояние: Директива Host указана',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C5', function($cell) {

                        $cell->setBackground('#90ee90');

                    });

                }elseif (!$check_status['hosts_dir'] && !$check_status['statusfail'] && !$check_status['no_robots']){

                    $sheet->row(5, [
                        '6',
                        'Проверка указания директивы Host',
                        'Ошибка',
                        'Состояние: в файле robots.txt не указана директива Host',
                        'Рекомендации (программист): Для того, чтобы поисковые системы знали,
                        какая версия сайта является основным зеркалом, необходимо
                        прописать адрес основного зеркала в директиве Host. В данный момент это
                        не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host
                        задается в файле 1 раз после всех правил'
                    ]);

                    $sheet->cell('C5', function($cell) {

                        $cell->setBackground('#FF0000');

                    });
                }

                if($check_status['hosts_dir'] && $check_status['hosts_dir'] == 1 && !$check_status['statusfail'] && !$check_status['no_robots']){

                    $sheet->row(7, [
                        '8',
                        'Проверка количества директивы Host прописанной в файле',
                        'OK',
                        'Состояние: В файле прописана одна директива Host',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C7', function($cell) {

                        $cell->setBackground('#90ee90');

                    });

                }elseif ($check_status['hosts_dir'] && $check_status['hosts_dir'] > 1 && !$check_status['statusfail'] && !$check_status['no_robots']){

                    $sheet->row(7, [
                        '8',
                        'Проверка количества директивы Host прописанной в файле',
                        'Ошибка',
                        'Состояние: в файле прописано несколько директив Host',
                        'Рекомендации (программист): Директива Host должна быть указана в файле
                        только один раз. Необходимо удалить все дополнительные директивы Host
                        оставив только одну (1) корректную и соответствующую основному зеркалу сайта.'
                    ]);

                    $sheet->cell('C7', function($cell) {

                        $cell->setBackground('#FF0000');

                    });
                }

                if(!$check_status['statusfail'] && !$check_status['no_robots'] && $check_status['file_size'] < 3200){

                    $sheet->row(9, [
                        '10',
                        'Проверка размера файла robots.txt',
                        'OK',
                        'Состояние: Размер файла robots.txt составляет ' . $check_status['file_size'] .' байт, что находится в пределах допустимой нормы',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C9', function($cell) {

                        $cell->setBackground('#90ee90');

                    });

                }elseif (!$check_status['statusfail'] && !$check_status['no_robots'] && $check_status['file_size'] > 3200){

                    $sheet->row(9, [
                        '10',
                        'Проверка размера файла robots.txt',
                        'Ошибка',
                        'Состояние: Размер файла robots.txt составляет ' .$check_status['file_size'] .' байт, что превышает допустимую норму',
                        'Рекомендации (программист): Максимально допустимый размер файла robots.txt составляет 32кб. Необходимо
                        отредактировать файл rosots.txt таким образом, чтобы его размер не превышал 32кб.'
                    ]);

                    $sheet->cell('C9', function($cell) {

                        $cell->setBackground('#FF0000');

                    });
                }

                if(!$check_status['statusfail'] && !$check_status['no_robots'] && $check_status['sitemap']){

                    $sheet->row(11, [
                        '11',
                        'Проверка указания директивы Sitemap',
                        'OK',
                        'Состояние: директива Sitemap указана',
                        'Рекомендации: доработки не требуются'
                    ]);

                    $sheet->cell('C11', function($cell) {

                        $cell->setBackground('#90ee90');

                    });

                }elseif (!$check_status['statusfail'] && !$check_status['no_robots'] && !$check_status['sitemap']){

                    $sheet->row(11, [
                        '11',
                        'Проверка указания директивы Sitemap',
                        'Ошибка',
                        'Состояние: в файле robots.txt не указана директива Sitemap',
                        'Рекомендации (программист): Добавить в файл robots.txt директиву Sitemap'
                    ]);

                    $sheet->cell('C11', function($cell) {

                        $cell->setBackground('#FF0000');

                    });
                }

            });

        })->export('xls');
    }
}