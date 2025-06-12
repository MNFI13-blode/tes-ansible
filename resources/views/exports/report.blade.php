<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export Report</title>
</head>

<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="text-align: center; vertical-align: middle;">Category</th>
                </tr>
                <tr>
                    @foreach ($groupedTransactions as $key => $value)
                        <th style="text-align: center; vertical-align: middle;">{{ $key }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                <?php $totalIncomes = []; ?>
                @foreach ($categories[\App\Enums\CategoryType::INCOME->value] as $item)
                    <tr>
                        <td style="background-color: greenyellow">{{ $item }}</td>
                        @foreach ($groupedTransactions as $key => $value)
                            <td style="background-color: greenyellow">
                                @if ($value->has($item))
                                    <?php isset($totalIncomes[$key]) ? ($totalIncomes[$key] += (int) $value[$item]) : ($totalIncomes[$key] = (int) $value[$item]); ?>
                                    {{ $value[$item] }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <?php $netIncome = []; ?>
                <tr>
                    <td style="background-color: green">Total Income</td>
                    @foreach ($groupedTransactions as $key => $value)
                        <td style="background-color: green">
                            @if (isset($totalIncomes[$key]))
                                <?php $netIncome[$key] = $totalIncomes[$key]; ?>
                                {{ $totalIncomes[$key] }}
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>

                <?php $totalExpenses = []; ?>
                @foreach ($categories[\App\Enums\CategoryType::EXPENSE->value] as $item)
                    <tr>
                        <td style="background-color: salmon">{{ $item }}</td>
                        @foreach ($groupedTransactions as $key => $value)
                            <td style="background-color: salmon">
                                @if ($value->has($item))
                                    <?php isset($totalExpenses[$key]) ? ($totalExpenses[$key] += (int) $value[$item]) : ($totalExpenses[$key] = (int) $value[$item]); ?>
                                    {{ $value[$item] }}
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach

                <tr>
                    <td style="background-color: lightcoral">Total Expense</td>
                    @foreach ($groupedTransactions as $key => $value)
                        <td style="background-color: lightcoral">
                            @if (isset($totalExpenses[$key]))
                                <?php isset($netIncome[$key]) ? $netIncome[$key] -= $totalExpenses[$key] : $netIncome[$key] = -$totalExpenses[$key]?>
                                {{ $totalExpenses[$key] }}
                            @else
                                {{ '-' }}
                            @endif
                        </td>
                    @endforeach
                </tr>

                <tr>
                    <td>Net Income</td>
                    @foreach ($groupedTransactions as $key => $value)
                        <td>{{ isset($netIncome[$key]) ? $netIncome[$key] : '-' }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
