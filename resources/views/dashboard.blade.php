<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <div class="bg-gray-50 p-6 shadow rounded-lg lg:w-1/2 flex-1">
                            <h3 class="text-2xl font-semibold text-gray-800 mb-4">Transaction Overview</h3>
                            <div class="w-full h-80">
                                <canvas id="creditDebitChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-6 shadow rounded-lg lg:w-1/2 flex-1">
                        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Recent Activity</h3>
                        <div class="text-lg text-gray-700 mb-4">
                            @if ($recentTransactions->isEmpty())
                                <p>No recent transactions found.</p>
                            @else
                                <ul class="space-y-2">
                                    @foreach ($recentTransactions as $transaction)
                                        <li class="flex justify-between">
                                            <span class="text-gray-600">Transaction ID: {{ $transaction->id }}</span>
                                            <span class="text-gray-600">{{ \Carbon\Carbon::parse($transaction->date)->diffForHumans() }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="font-semibold">Credit: </span>
                                            <span class="text-green-500">{{ number_format($transaction->credit, 2) }}</span>
                                        </li>
                                        <li class="flex justify-between">
                                            <span class="font-semibold">Debit: </span>
                                            <span class="text-red-500">{{ number_format($transaction->debit, 2) }}</span>
                                        </li>
                                        <hr class="my-2">
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('creditDebitChart').getContext('2d');


    var creditDebitChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Credit', 'Debit'],
            datasets: [{
                data: [{{ $totals->total_credit ?? 0 }}, {{ $totals->total_debit ?? 0 }}], 
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(255, 99, 132, 0.6)'],
                borderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Percentage Debit and Credit',
                    font: {
                        size: 18,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            var dataset = tooltipItem.chart.data.datasets[tooltipItem.datasetIndex];
                            var total = dataset.data.reduce(function(previousValue, currentValue) {
                                return previousValue + currentValue;
                            });
                            var currentValue = dataset.data[tooltipItem.dataIndex];
                            var percentage = Math.floor(((currentValue / total) * 100) + 0.5);         
                            return tooltipItem.label + ': ' + percentage + '%';
                        }
                    }
                },
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
