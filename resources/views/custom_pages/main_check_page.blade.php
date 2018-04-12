@extends('custom_pages.home')

@section('checkForm')

    <h1 align="center" class="custom_forms">Введите адрес сайта</h1>
    <hr>
    @include('errors.errors')
    <form action="{{route('checkUrl')}}" method="POST">
        {{csrf_field()}}
        <div class="form-group">
            <label for="inputUrl"><b>URL</b> Введите полный адрес сайта без дополнительных путей. Напрмиер, http://site.com, а не http://site.com/ или http://site.com/users</label>
            <input type="text" class="form-control" id="inputUrl" name="url" placeholder="URL" required>
        </div>
        <button type="submit" class="btn btn-primary">Проверить</button>
    </form>

    @if (session('message'))
        <div class="alert alert-danger" style="margin-top: 20%">
            {{ session('message') }}
        </div>
    @endif

    <hr>

    @if(isset($check_list))
        <div style="padding-bottom: 5%">
        <table border="1" cellpadding="5">

            @if($check_list['no_robots'])
                <tr>
                <td>1</td>
                <td>Проверка наличия файла robots.txt</td>
                <td bgcolor="red">Ошибка</td>
                <td>Состояние: файл robots.txt отсутствует</td>
                <td>Рекомендации (программист): создать файл robots.txt и размеситить его на сайте</td>
                </tr>
                @else
                <tr>
                <td>1</td>
                <td>Проверка наличия файла robots.txt</td>
                <td bgcolor="90ee90">OK</td>
                <td>Состояние: файл robots.txt присутствует</td>
                <td>Рекомендации: доработки не требуются</td>
                </tr>
                @endif

            @if($check_list['statusfail'] && !$check_list['no_robots'])
                <tr>
                    <td>12</td>
                    <td>Проверка ответа кода сервера для файла robots.txt</td>
                    <td bgcolor="red">Ошибка</td>
                    <td>Состояние: при обращении к файлу robots.txt сервер возвращает код ответа {{$check_list['statusfail']}}</td>
                    <td>Рекомендации (программист): файл robots.txt должен отдавать код ответа 200,
                        иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом,
                        чтобы при обращении к robots.txt сервер возвращал ответ 200</td>
                </tr>
                @elseif(!$check_list['statusfail'] && !$check_list['no_robots'])
                <tr>
                <td>12</td>
                <td>Проверка ответа кода сервера для файла robots.txt</td>
                <td bgcolor="#90ee90">OK</td>
                <td>Состояние: При обращении к robots.txt сервер отдает ответ 200</td>
                <td>Рекомендации: доработки не требуются</td>
                </tr>
            @endif

            @if($check_list['hosts_dir'] && !$check_list['statusfail'] && !$check_list['no_robots'])
                    <tr>
                        <td>6</td>
                        <td>Проверка указания директивы Host</td>
                        <td bgcolor="90ee90">OK</td>
                        <td>Состояние: Директива Host указана</td>
                        <td>Рекомендации: доработки не требуются</td>
                    </tr>
                @elseif(!$check_list['hosts_dir'] && !$check_list['statusfail'] && !$check_list['no_robots'])
                    <tr>
                        <td>6</td>
                        <td>Проверка указания директивы Host</td>
                        <td bgcolor="red">Ошибка</td>
                        <td>Состояние: в файле robots.txt не указана директива Host</td>
                        <td>Рекомендации (программист): Для того, чтобы поисковые системы знали,
                        какая версия сайта является основным зеркалом, необходимо
                        прописать адрес основного зеркала в директиве Host. В данный момент это
                        не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host
                        задается в файле 1 раз после всех правил</td>
                    </tr>
            @endif

                @if($check_list['hosts_dir'] && $check_list['hosts_dir'] == 1 && !$check_list['statusfail'] && !$check_list['no_robots'])
                    <tr>
                        <td>8</td>
                        <td>Проверка количества директивы Host прописанной в файле</td>
                        <td bgcolor="90ee90">OK</td>
                        <td>Состояние: В файле прописана одна директива Host</td>
                        <td>Рекомендации: доработки не требуются</td>
                    </tr>
                @elseif($check_list['hosts_dir'] && $check_list['hosts_dir'] > 1 && !$check_list['statusfail'] && !$check_list['no_robots'])
                    <tr>
                        <td>8</td>
                        <td>Проверка количества директив Host прописанных в файле</td>
                        <td bgcolor="red">Ошибка</td>
                        <td>Состояние: в файле прописано несколько директив Host</td>
                        <td>Рекомендации (программист): Директива Host должна быть указана в файле
                        только один раз. Необходимо удалить все дополнительные директивы Host
                        оставив только одну (1) корректную и соответсвующую основному зеркалу сайта.</td>
                    </tr>
                @endif


                @if(!$check_list['statusfail'] && !$check_list['no_robots'] && $check_list['file_size'] < 3200)
                    <tr>
                        <td>10</td>
                        <td>Проверка размера файла robots.txt</td>
                        <td bgcolor="90ee90">OK</td>
                        <td>Состояние: Размер файла robots.txt составляет {{$check_list['file_size']}} байт, что находится в пределах допустимой нормы</td>
                        <td>Рекомендации: доработки не требуются</td>
                    </tr>
                @elseif(!$check_list['statusfail'] && !$check_list['no_robots'] && $check_list['file_size'] > 3200)
                    <tr>
                        <td>10</td>
                        <td>Проверка размера файла robots.txt</td>
                        <td bgcolor="red">Ошибка</td>
                        <td>Состояние: Размер файла robots.txt составляет {{$check_list['file_size']}} байт, что превышает допустимую норму</td>
                        <td>Рекомендации (программист): Максимально допустимый размер файла robots.txt составляет 32кб. Необходимо
                        отредактировать файл rosots.txt таким образом, чтобы его размер не превышал 32кб.</td>
                    </tr>
                @endif

                @if(!$check_list['statusfail'] && !$check_list['no_robots'] && $check_list['sitemap'])
                    <tr>
                        <td>11</td>
                        <td>Проверка указания директивы Sitemap</td>
                        <td bgcolor="90ee90">OK</td>
                        <td>Состояние: директива Sitemap указана</td>
                        <td>Рекомендации: доработки не требуются</td>
                    </tr>
                @elseif(!$check_list['statusfail'] && !$check_list['no_robots'] && !$check_list['sitemap'])
                    <tr>
                        <td>11</td>
                        <td>Проверка указания директивы Sitemap</td>
                        <td bgcolor="red">Ошибка</td>
                        <td>Состояние: в файле robots.txt не указана директива Sitemap</td>
                        <td>Рекомендации (программист): Добавить в файл robots.txt директиву Sitemap</td>
                    </tr>
                @endif

        </table>
            <hr>
            <form action="{{route('saveAudit')}}" method="POST">
                {{csrf_field()}}
                <input type="hidden" name="status_code" value="{{$check_list['status_code']}}">
                <input type="hidden" name="valid_url" value="{{$check_list['valid_url']}}">
                <input type="hidden" name="hosts_dir" value="{{$check_list['hosts_dir']}}">
                <input type="hidden" name="sitemap" value="{{$check_list['sitemap']}}">
                <input type="hidden" name="statusfail" value="{{$check_list['statusfail']}}">
                <input type="hidden" name="no_robots" value="{{$check_list['no_robots']}}">
                <input type="hidden" name="file_size" value="{{$check_list['file_size']}}">

                <button type="submit" class="btn btn-primary">Сохранить в EXEL</button>
            </form>
        </div>
    @endif

    @endsection