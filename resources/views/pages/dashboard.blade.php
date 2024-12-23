@extends('layouts.app', [
    'class' => '',
    'elementActive' => 'dashboard'
])

@section('content')
    <div class="content">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-bulb-63 text-warning"></i> <!-- Изменённая иконка на "lightbulb" -->
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Предложения</p>
                                    <p class="card-title">
                                        @if(auth()->user()->role === 'admin') 
                                            {{ App\Models\Proposal::all()->count() }}
                                        @else 
                                            {{ App\Models\Proposal::where('user_id', auth()->id())->count() }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Обновить сейчас
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-success">
                                    <i class="nc-icon nc-check-2 text-success"></i> <!-- Иконка для обозначения обработанных предложений -->
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Одобрено</p>
                                    <p class="card-title">
                                        @if(auth()->user()->role === 'admin') 
                                            {{ App\Models\Proposal::where('status', 'accepted')->count() }}
                                        @else 
                                            {{ App\Models\Proposal::where('user_id', auth()->id())->where('status', 'accepted')->count() }}
                                        @endif
                                    </p>
                                    <!-- Обновлено значение в блоке -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Обновить сейчас
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-warning">
                                    <i class="nc-icon nc-time-alarm text-danger"></i> <!-- Иконка для обозначения "На рассмотрении" -->
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Ожидает</p>
                                    <p class="card-title">
                                        @if(auth()->user()->role === 'admin') 
                                            {{ App\Models\Proposal::where('status', 'in_review')->count() }}
                                        @else 
                                            {{ App\Models\Proposal::where('user_id', auth()->id())->where('status', 'in_review')->count() }}
                                        @endif
                                    </p>
                                    <!-- Количество "на рассмотрении" -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Обновить сейчас
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="card card-stats">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-5 col-md-4">
                                <div class="icon-big text-center icon-danger">
                                    <i class="nc-icon nc-simple-remove text-danger"></i> <!-- Иконка для отображения "Отклонено" -->
                                </div>
                            </div>
                            <div class="col-7 col-md-8">
                                <div class="numbers">
                                    <p class="card-category">Отклонено</p>
                                    <p class="card-title">
                                        @if(auth()->user()->role === 'admin') 
                                            {{ App\Models\Proposal::where('status', 'rejected')->count() }}
                                        @else 
                                            {{ App\Models\Proposal::where('user_id', auth()->id())->where('status', 'rejected')->count() }}
                                        @endif
                                    </p>
                                    <!-- Количество отклонённых предложений -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <hr>
                        <div class="stats">
                            <i class="fa fa-refresh"></i> Обновить сейчас
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-chart">
                    <div class="card-header">
                        <h5 class="card-title">NASDAQ: AAPL</h5>
                        <p class="card-category">Line Chart with Points</p>
                    </div>
                    <div class="card-body">
                        <canvas id="speedChart" width="400" height="100"></canvas>
                    </div>
                    <div class="card-footer">
                        <div class="chart-legend">
                            <i class="fa fa-circle text-info"></i> Tesla Model S
                            <i class="fa fa-circle text-warning"></i> BMW 5 Series
                        </div>
                        <hr />
                        <div class="card-stats">
                            <i class="fa fa-check"></i> Data information certified
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
            demo.initChartsPages();
        });
    </script>
@endpush
