@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'tickets'
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
                                    <h3 class="mb-0">Предложения</h3>
                                </div>
                                <div class="col-4 text-right">
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addProposalModal">Добавить</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="ticketTable" class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Заголовок</th>
                                        <th scope="col">Текущее состояние</th>
                                        <th scope="col">Будущее состояние</th>
                                        <th scope="col">Файлы</th>
                                        <th scope="col">Автор</th>
                                        <th scope="col">Дата создания</th>
                                        <th scope="col" style="width: 20%;">Статус</th>
                                        <!-- <th scope="col">Комментарии</th> -->
                                        <th scope="col">Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($proposals as $proposal)
                                        @php
                                            // Определяем текст и класс для каждого статуса
                                            $statusText = [
                                                'new' => 'Новое',
                                                'in_review' => 'Ожидает',
                                                'accepted' => 'Принято',
                                                'rejected' => 'Отклонено',
                                            ];

                                            $rowClass = [
                                                'new' => 'table-primary',
                                                'in_review' => 'table-warning',
                                                'accepted' => 'table-success',
                                                'rejected' => 'table-danger',
                                            ];
                                        @endphp

                                        <tr class="{{ $rowClass[$proposal->status] ?? '' }}">
                                            <td>{{ $proposal->title }}</td>
                                            <td>{{ $proposal->current_state }}</td>
                                            <td>{{ $proposal->future_state }}</td>
                                            <td>
                                                @if($proposal->files->count())
                                                    @foreach($proposal->files as $file)
                                                        <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank">Файл {{ $loop->iteration }}</a><br>
                                                    @endforeach
                                                @else
                                                    Нет файлов
                                                @endif
                                            </td>
                                            <td>{{ $proposal->user->name ?? 'Неизвестный' }}</td>
                                            <td>{{ $proposal->created_at->format('d.m.Y H:i') }}</td>
                                            <td>

                                                    <!-- Выпадающий список для изменения статуса -->
                                                    <select class="form-control status-dropdown" data-id="{{ $proposal->id }}" data-comment="{{ App\Models\Comment::where('proposal_id', $proposal->id)->pluck('comment')->first() }}">
                                                        @foreach($statusText as $statusKey => $statusValue)
                                                            <option value="{{ $statusKey }}" {{ $proposal->status == $statusKey ? 'selected' : '' }}>
                                                                {{ $statusValue }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                <!--@if(auth()->user()->role === 'admin') @else{{ $statusText[$proposal->status] ?? $proposal->status }}@endif-->

                                            </td>
                                            <!--<td>{{ $proposal->comments ?? 'Пусто' }}</td>-->
                                            <td>
                                                <!-- Кнопка для редактирования и удаления -->
                                                <!-- Проверяем, если статус "Отклонено", то делаем кнопку недоступной -->
                                                @if($proposal->status === 'rejected')
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showAlert()" style="cursor: not-allowed; opacity: 0.5;">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                @else
                                                    <!-- Кнопка для редактирования, если статус не "Отклонено" -->
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-toggle="modal" data-target="#editProposalModal"
                                                            data-id="{{ $proposal->id }}"
                                                            data-title="{{ $proposal->title }}"
                                                            data-current_state="{{ $proposal->current_state }}"
                                                            data-future_state="{{ $proposal->future_state }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                @endif
                                                <form action="{{ route('tickets.destroy', $proposal->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
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
    <!-- Модальное окно CREATE -->
    <div class="modal fade" id="addProposalModal" tabindex="-1" role="dialog" aria-labelledby="addProposalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProposalModalLabel">Создать предложение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" id="main-form">
                        @csrf
                        <!-- Поле для заголовка -->
                        <div class="form-group">
                            <label for="title">Заголовок</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                            @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Поле для текущего состояния -->
                        <div class="form-group">
                            <label for="current_state">Текущее состояние</label>
                            <textarea class="form-control @error('current_state') is-invalid @enderror" id="current_state" name="current_state" rows="3" required></textarea>
                            @error('current_state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <!-- Поле для будущего состояния -->
                        <div class="form-group">
                            <label for="future_state">Будущее состояние</label>
                            <textarea class="form-control @error('future_state') is-invalid @enderror" id="future_state" name="future_state" rows="3" required></textarea>
                            @error('future_state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    
                        <!-- Dropzone для загрузки файлов -->
                        <!--
                        <div class="form-group">
                            <label for="files">Прикрепить файлы</label>
                            <div class="dropzone" id="file-dropzone">
                                <div class="dz-message" data-dz-message>
                                    <span>Перетащите файлы сюда или нажмите, чтобы выбрать файлы (максимум 10)</span>
                                </div>
                            </div>
                        </div>
                        -->
                        <!-- Поле для загрузки файлов -->
                        <div class="form-group">
                            <label for="files">Прикрепить файлы (до 10 файлов)</label>
                            <input type="file" class="form-control" id="files" name="files[]" multiple accept=".pdf,.doc,.docx,.jpg,.png">

                            <!-- Список выбранных файлов -->
                            <div id="file-list" class="mt-3">
                                <!-- Здесь будут отображаться выбранные файлы -->
                            </div>

                            <!-- Кнопка для загрузки -->
                            <button type="button" class="btn btn-primary mt-3" id="upload-button" disabled>Загрузить</button>
                        </div>
                        <!-- Кнопки -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно для редактирования предложения -->
    <div class="modal fade" id="editProposalModal" tabindex="-1" role="dialog" aria-labelledby="editProposalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProposalModalLabel">Редактировать предложение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editProposalForm" method="POST" action="" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit-title">Заголовок</label>
                            <input type="text" class="form-control" id="edit-title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="edit-current-state">Текущее состояние</label>
                            <textarea class="form-control" id="edit-current-state" name="current_state" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-future-state">Будущее состояние</label>
                            <textarea class="form-control" id="edit-future-state" name="future_state" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="edit-file">Прикрепить файл</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="edit-file" name="file" accept=".pdf, .doc, .docx, .jpg, .png">
                            @error('file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Модальное окно для комментария при отклонении -->
    <div class="modal fade" id="rejectionModal" tabindex="-1" role="dialog" aria-labelledby="rejectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionModalLabel">Причина отклонения</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="rejectionForm" method="POST" action="">
                        @csrf
                        @method('POST')
                        <div class="form-group">
                            <label for="rejectionReason">Причина отклонения</label>
                            <textarea class="form-control" id="rejectionReason" name="rejectionReason" placeholder="Причина отклонения" required></textarea>
                        </div>
                        <input type="hidden" id="proposalId" name="proposalId">
                        <button type="button" id="submitRejection" class="btn btn-primary">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const fileInput = document.getElementById('files');
        const fileList = document.getElementById('file-list');
        const uploadButton = document.getElementById('upload-button');

        fileInput.addEventListener('change', () => {
            fileList.innerHTML = ''; // Очищаем список перед добавлением новых файлов

            if (fileInput.files.length > 0) {
                Array.from(fileInput.files).forEach((file, index) => {
                    const listItem = document.createElement('div');
                    listItem.classList.add('file-item', 'mb-2');
                    listItem.innerHTML = `
                        <span>${index + 1}. ${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                    `;
                    fileList.appendChild(listItem);
                });

                // Активируем кнопку загрузки
                uploadButton.disabled = false;
            } else {
                // Отключаем кнопку загрузки, если файлов нет
                uploadButton.disabled = true;
            }
        });

        // Действие при нажатии на кнопку загрузки
        uploadButton.addEventListener('click', () => {
            alert('Файлы выбраны и готовы к загрузке');
            // Здесь можно добавить отправку формы или другой функционал
        });
    </script>

    <script>
        Dropzone.autoDiscover = false; // Отключаем автоматическое инициализацию

        const myDropzone = new Dropzone("#file-dropzone", {
            url: "#", // Заглушка URL, так как файлы будут отправляться с основной формой
            autoProcessQueue: false, // Отключаем автоматическую отправку файлов
            paramName: "files[]", // Имя параметра для отправки файлов
            maxFiles: 10, // Максимум 10 файлов
            maxFilesize: 2, // Максимальный размер файла (в МБ)
            acceptedFiles: ".pdf,.doc,.docx,.jpg,.png", // Допустимые форматы
            addRemoveLinks: true, // Кнопка "Удалить"
            dictRemoveFile: "Удалить файл",
            dictDefaultMessage: "Перетащите файлы сюда или нажмите для выбора",
        });

        // Обработка отправки основной формы
        document.getElementById("main-form").addEventListener("submit", function (e) {
            e.preventDefault();

            // Если есть файлы в очереди
            if (myDropzone.getQueuedFiles().length > 0) {
                myDropzone.processQueue(); // Отправляем файлы
            } else {
                // Если файлов нет, отправляем форму
                this.submit();
            }
        });

        // После успешной загрузки всех файлов отправляем форму
        myDropzone.on("queuecomplete", function () {
            document.getElementById("main-form").submit();
        });

        // В случае ошибки загрузки
        myDropzone.on("error", function (file, response) {
            console.error("Ошибка загрузки файла:", response);
        });
    </script>
    <script>
        // Bootstrap 4 и выше поддерживает этот скрипт
        $(document).on('change', '.custom-file-input', function (event) {
            var inputFile = event.currentTarget;
            $(inputFile).parent().find('.custom-file-label').html(inputFile.files[0].name);
        });

    </script>
    <script>
        $(document).ready(function () {
            $('#editProposalModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Кнопка, которая активировала модальное окно
                var id = button.data('id');
                var title = button.data('title');
                var currentState = button.data('current_state');
                var futureState = button.data('future_state');

                var modal = $(this);
                modal.find('#edit-title').val(title);
                modal.find('#edit-current-state').val(currentState);
                modal.find('#edit-future-state').val(futureState);

                // Устанавливаем URL для отправки формы редактирования на маршрут tickets.update
                modal.find('#editProposalForm').attr('action', '{{ route("tickets.update", "") }}/' + id);
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let selectedProposalId = null;

            // Обработчик изменения статуса
            document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
                dropdown.addEventListener('change', function () {
                    selectedProposalId = this.dataset.id;
                    let newStatus = this.value;

                    if (newStatus === 'rejected') {
                        // Если выбран "Отклонено", открываем модальное окно для ввода причины
                        // Устанавливаем значение комментария, если оно существует
                        let existingComment = this.dataset.comment || '';
                        document.getElementById('rejectionReason').value = existingComment;

                        $('#rejectionModal').modal('show');
                    } else {
                        // Если выбран другой статус, отправляем обновление сразу
                        updateProposalStatus(selectedProposalId, newStatus, '');
                    }
                });
            });

            // Отправка причины отклонения
            document.getElementById('submitRejection').addEventListener('click', function () {
                let reason = document.getElementById('rejectionReason').value;
                if (selectedProposalId) {
                    updateProposalStatus(selectedProposalId, 'rejected', reason);
                    $('#rejectionModal').modal('hide');
                    document.getElementById('rejectionReason').value = ''; // Очищаем поле комментария
                }
            });

            // Функция обновления статуса предложения с комментарием
            function updateProposalStatus(proposalId, status, comment) {
                fetch(`/tickets/${proposalId}/update-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status: status,
                        comments: comment
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Ошибка при обновлении статуса');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Перезагрузка страницы после успешного обновления
                        } else {
                            alert('Не удалось обновить статус');
                        }
                    })
                    .catch(error => console.error(error));
            }
        });
    </script>
    <script>
        function showAlert() {
            alert('Изменение невозможно, так как предложение отклонено.');
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#ticketTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: [0, 1, 2, 4, 6] // Индексы столбцов для экспорта (без действий и файла)
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 4, 6] // Индексы столбцов для экспорта (без действий и файла)
                        }
                    }
                ]
            });
        });

    </script>

@endpush
