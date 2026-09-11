<x-app-layout>
    <x-slot:style>
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}"/>
    </x-slot:style>

    <x-slot:script>
        <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

        <script>
            /**
             * Analytics Dashboard
             */

            'use strict';
            (function () {
                let cardColor, headingColor, labelColor, legendColor, borderColor, shadeColor;

                if (isDarkStyle) {
                    cardColor = config.colors_dark.cardColor;
                    headingColor = config.colors_dark.headingColor;
                    labelColor = config.colors_dark.textMuted;
                    legendColor = config.colors_dark.bodyColor;
                    borderColor = config.colors_dark.borderColor;
                    shadeColor = 'dark';
                } else {
                    cardColor = config.colors.cardColor;
                    headingColor = config.colors.headingColor;
                    labelColor = config.colors.textMuted;
                    legendColor = config.colors.bodyColor;
                    borderColor = config.colors.borderColor;
                    shadeColor = 'light';
                }

                Apex.chart = {
                    fontFamily: 'inherit',
                    locales: [{
                        "name": "fa",
                        "options": {
                            "months": ["ژانویه", "فوریه", "مارس", "آوریل", "می", "ژوئن", "جولای", "آگوست", "سپتامبر", "اکتبر", "نوامبر", "دسامبر"],
                            "shortMonths": ["ژانویه", "فوریه", "مارس", "آوریل", "می", "ژوئن", "جولای", "آگوست", "سپتامبر", "اکتبر", "نوامبر", "دسامبر"],
                            "days": ["یکشنبه", "دوشنبه", "سه‌شنبه", "چهارشنبه", "پنجشنبه", "جمعه", "شنبه"],
                            "shortDays": ["ی", "د", "س", "چ", "پ", "ج", "ش"],
                            "toolbar": {
                                "exportToSVG": "دریافت SVG",
                                "exportToPNG": "دریافت PNG",
                                "menu": "فهرست",
                                "selection": "انتخاب",
                                "selectionZoom": "بزرگنمایی قسمت انتخاب شده",
                                "zoomIn": "بزرگ نمایی",
                                "zoomOut": "کوچک نمایی",
                                "pan": "جا به جایی",
                                "reset": "بازنشانی بزرگ نمایی"
                            }
                        }
                    }],
                    defaultLocale: "fa"
                }

                // Impression - Donut Chart
                // --------------------------------------------------------------------
                const impressionChartConfig = {
                    chart: {
                        height: 185,
                        type: 'donut'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        padding: {
                            bottom: -10
                        }
                    },
                    stroke: {
                        width: 0,
                        lineCap: 'round'
                    },
                    colors: [config.colors.primary, config.colors.warning, config.colors.success],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '90%',
                                labels: {
                                    show: true,
                                    name: {
                                        fontSize: '0.938rem',
                                        offsetY: 22
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '1.625rem',
                                        fontWeight: '500',
                                        color: headingColor,
                                        offsetY: -22,
                                        formatter: function (val) {
                                            return val;
                                        }
                                    },
                                    total: {
                                        show: true,
                                        label: 'مجموع',
                                        color: legendColor,
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce(function (a, b) {
                                                return a + b;
                                            }, 0);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        show: true,
                        position: 'bottom',
                        offsetY: 8,
                        horizontalAlign: 'center',
                        labels: {
                            colors: legendColor,
                            useSeriesColors: false
                        },
                        markers: {
                            width: 10,
                            height: 10,
                            offsetX: -3
                        }
                    }
                };

                // ticket chart
                const ticketImpressionEle = document.querySelector('#ticket-impression');
                const ticketImpressionConfig = impressionChartConfig

                impressionChartConfig.series = [
                    {{ \App\Facades\TicketRepositoryFacade::count(ticketStatus: \App\Enums\Ticket\TicketStatus::PENDING->value) }},
                    {{ \App\Facades\TicketRepositoryFacade::count(ticketStatus: \App\Enums\Ticket\TicketStatus::WAITING->value) }},
                    {{ \App\Facades\TicketRepositoryFacade::count(ticketStatus: \App\Enums\Ticket\TicketStatus::CLOSED->value) }},
                ]

                impressionChartConfig.labels = [
                    '{{ \App\Enums\Ticket\TicketStatus::PENDING->value }}',
                    '{{ \App\Enums\Ticket\TicketStatus::WAITING->value }}',
                    '{{ \App\Enums\Ticket\TicketStatus::CLOSED->value}}'
                ]

                if (typeof ticketImpressionEle !== undefined && ticketImpressionEle !== null) {
                    const ticketImpressionChart = new ApexCharts(ticketImpressionEle, ticketImpressionConfig);
                    ticketImpressionChart.render();
                }

                // task chart
                const taskImpressionEle = document.querySelector('#task-impression');
                const taskImpressionConfig = impressionChartConfig

                impressionChartConfig.series = [
                    {{ \App\Facades\TaskRepositoryFacade::count(taskStatus: \App\Enums\Task\TaskStatus::PENDING->value) }},
                    {{ \App\Facades\TaskRepositoryFacade::count(taskStatus: \App\Enums\Task\TaskStatus::SENT->value) }},
                    {{ \App\Facades\TaskRepositoryFacade::count(taskStatus: \App\Enums\Task\TaskStatus::CLOSED->value) }},
                ]

                impressionChartConfig.labels = [
                    '{{ \App\Enums\Task\TaskStatus::PENDING->value }}',
                    '{{ \App\Enums\Task\TaskStatus::SENT->value }}',
                    '{{ \App\Enums\Task\TaskStatus::CLOSED->value}}'
                ]

                if (typeof taskImpressionEle !== undefined && taskImpressionEle !== null) {
                    const taskImpressionChart = new ApexCharts(taskImpressionEle, taskImpressionConfig);
                    taskImpressionChart.render();
                }
            })();

        </script>

    </x-slot:script>
    <div class="card">
        <div class="card-body d-flex justify-content-around text-center" style="position: relative;">
        @can('viewAny', \App\Models\Ticket::class)
            <!-- Ticket -->
                <div>
                    <h4>تیکت ها</h4>
                    <div id="ticket-impression" class="mt-2"></div>
                </div>
        @endcan

        @can('viewAny', \App\Models\Task::class)
            <!-- Task -->
                <div>
                    <h4>وظایف</h4>
                    <div id="task-impression"></div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
