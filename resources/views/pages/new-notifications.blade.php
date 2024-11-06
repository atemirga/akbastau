@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'notifications'
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
                                    <h3 class="mb-0">Уведомления</h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="userTable" class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Тема</th>
                                        <th scope="col">Сообщение</th>
                                        <th scope="col">Автор</th>
                                        <th scope="col">Время</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>какая-то тема</td>
                                        <td>какоке-то Сообщение</td>
                                        <td>+Temirgali Abdualiyev</td>
                                        <td>02.11.2024 04:00</td>
                                    </tr>
                                    <tr>
                                        <td>какая-то тема</td>
                                        <td>какоке-то Сообщение</td>
                                        <td>+Temirgali Abdualiyev</td>
                                        <td>02.11.2024 04:00</td>
                                    </tr>
                                    <tr>
                                        <td>какая-то тема</td>
                                        <td>какоке-то Сообщение</td>
                                        <td>+Temirgali Abdualiyev</td>
                                        <td>02.11.2024 04:00</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<?php
