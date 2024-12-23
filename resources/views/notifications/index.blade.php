@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'notifications'
])
@push('styles')
<style>
    p strong {
        display: block; /* Заголовок всегда на своей строке */
        margin-bottom: 5px; /* Отступ после заголовка */
    }
</style>
@endpush


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
                            @if($notifications->isEmpty())
                                <p>У вас нет уведомлений.</p>
                            @else
                                <ul class="list-group">
                                    @foreach($notifications as $notification)
                                        <li class="list-group-item">
                                            <a href="#" 
                                               class="notification-link" 
                                               data-id="{{ $notification->id }}" 
                                               data-proposal="{{ json_encode($notification->proposal) }}"
                                               data-sender="{{ $notification->proposal->user->name ?? 'Неизвестный' }}">
                                                {{ $notification->message }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
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

<!-- Модальное окно -->
<div class="modal fade" id="proposalModal" tabindex="-1" role="dialog" aria-labelledby="proposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proposalModalLabel">Детали предложения</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Отправитель:</strong><br>
                    <span id="notification-sender"></span>
                </p>
                <p><strong>Заголовок:</strong><br>
                    <span id="proposal-title"></span>
                </p>
                <p><strong>Текущее состояние:</strong><br>
                    <span id="proposal-current-state"></span>
                </p>
                <p><strong>Будущее состояние:</strong><br>
                    <span id="proposal-future-state"></span>
                </p>
                <p><strong>Файлы:</strong><br>
                    <span id="proposal-files"></span>
                </p>
            </div>

            @if(auth()->user()->role === "admin")
                <div class="modal-footer">
                    <button class="btn btn-primary" id="status-in-review">Принять</button>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let activeNotificationId;

    // Открытие модального окна с заполнением данных
    document.querySelectorAll('.notification-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            activeNotificationId = this.dataset.id; // Сохраняем ID уведомления
            const proposal = JSON.parse(this.dataset.proposal);
            const sender = this.dataset.sender;

            // Заполняем модальное окно
            document.getElementById('notification-sender').textContent = sender === 'Вы' ? 'Вы' : sender;
            document.getElementById('proposal-title').textContent = proposal.title;
            document.getElementById('proposal-current-state').textContent = proposal.current_state;
            document.getElementById('proposal-future-state').textContent = proposal.future_state;

            let filesHtml = '';
            if (proposal.files && proposal.files.length > 0) {
                proposal.files.forEach(file => {
                    filesHtml += `<a href="/storage/${file.file_path}" target="_blank">Скачать файл</a><br>`;
                });
            } else {
                filesHtml = 'Файлы не прикреплены.';
            }

            document.getElementById('proposal-files').innerHTML = filesHtml;

            // Открываем модальное окно
            $('#proposalModal').modal('show');

            // Помечаем уведомление как прочитанное
            fetch(`/notifications/${activeNotificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            });
        });
    });

    // Обработчик для кнопки "Принять"
    document.getElementById('status-in-review').addEventListener('click', function () {
        fetch(`/notifications/${activeNotificationId}/update-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ status: 'in_review' }),
        }).then(() => {
            $('#proposalModal').modal('hide');
            location.reload(); // Обновляем страницу после изменения статуса
        });
    });
});

</script>
@endpush

