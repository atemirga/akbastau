@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'users'
])

@section('content')
    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
            <!-- Сообщение об ошибках валидации -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Ошибки:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
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
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUserModal">Добавить</button>
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
                                    <th scope="col">Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                        <td>{{ $user->phone ?? 'Не указан' }}</td>
                                        <td>{{$user->department->name ?? 'Не указан' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal"
                                                    data-target="#editUserModal"
                                                    onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->phone }}', {{ $user->current_team_id ?? 'null' }}, '{{ $user->role }}')">
                                                Изменить
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger"
                                                    onclick="deleteUser({{ $user->id }})">
                                                Удалить
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
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
    <!-- Modal для добавления пользователя -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Добавить пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">ФИО</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Почта</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="text" class="form-control" id="phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="department_id">Отдел</label>
                            <select class="form-control" id="department_id" name="department_id" required>
                                <option value="">Выберите отдел</option>
                                @foreach(\App\Models\Department::all() as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="role">Роль</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Админ</option>
                                <option value="coordinator">Координатор</option>
                                <option value="employee">Рядовой пользователь</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal для редактирования пользователя -->
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Изменить пользователя</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- Динамическое обновление маршрута с ID -->
                <form method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">ФИО</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Почта</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_phone">Телефон</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_department">Отдел</label>
                            <select class="form-control" id="edit_department" name="department_id" required>
                                <option value="">Выберите отдел</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_role">Роль</label>
                            <select class="form-control" id="edit_role" name="role" required>
                                <option value="admin">Админ</option>
                                <option value="coordinator">Координатор</option>
                                <option value="employee">Рядовой пользователь</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>

        function editUser(id, name, email, phone, departmentId, role) {
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone').value = phone;

            // Устанавливаем текущий отдел как выбранный в выпадающем списке
            let departmentSelect = document.getElementById('edit_department');
            departmentSelect.value = departmentId;

            // Устанавливаем текущую роль как выбранную в выпадающем списке
            let roleSelect = document.getElementById('edit_role');
            roleSelect.value = role;

            document.getElementById('editUserForm').action = `/users/${id}`;
        }

        function deleteUser(userId) {
            if (confirm('Вы уверены, что хотите удалить пользователя?')) {
                // AJAX-запрос для удаления пользователя
                fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            alert('Пользователь успешно удалён');
                            // Обновляем страницу или удаляем строку из таблицы
                            location.reload();
                        } else {
                            alert('Ошибка при удалении пользователя');
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                        alert('Ошибка при удалении пользователя');
                    });
            }
        }

    </script>

@endpush
