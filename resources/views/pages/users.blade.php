@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <div class="content">
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <h3 class="mb-0">Пользователи</h3>
                            </div>
                            <div class="col-4 text-right">
                                <a href="#" class="btn btn-sm btn-primary">Добавить</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="userTable" class="table align-items-center table-flush">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">ФИО</th>
                                    <th scope="col">Почта</th>
                                    <th scope="col">Телефон</th>
                                    <th scope="col">Отдел</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Temirgali Abdualiyev</td>
                                    <td>
                                        <a href="mailto:admin@paper.com">atemirga@gmail.com</a>
                                    </td>
                                    <td>+77074794042</td>
                                    <td>IT</td>
                                </tr>
                                <tr>
                                    <td>Ерке Есмахан</td>
                                    <td>
                                        <a href="mailto:admin@paper.com">yesma@gmail.com</a>
                                    </td>
                                    <td>+77772234567</td>
                                    <td>Бурение</td>
                                </tr>
                                <tr>
                                    <td>Асылбек Енсепов</td>
                                    <td>
                                        <a href="mailto:admin@paper.com">asyl@gmail.com</a>
                                    </td>
                                    <td>+77772234567</td>
                                    <td>Транспорт</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer py-4">
                        <nav class="d-flex justify-content-end" aria-label="...">

                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
