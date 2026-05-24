<table>
    <thead>
        <tr>
            <th>Employee Name</th>
            <th>Month</th>
            <th>Present</th>
            <th>Leave</th>
            <th>Weekly Off</th>
            <th>Half Day</th>
            <th>Holiday</th>
            <th>Net Salary</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($salaryList as $item)
            <tr>
                <td>{{ $item->employee->name ?? '-' }}</td>
                <td>{{ date('F', mktime(0, 0, 0, $item->month, 1)) }}</td>
                <td>{{ $item->present_days }}</td>
                <td>{{ $item->absent_days }}</td>
                <td>{{ $item->weekly_off }}</td>
                <td>{{ $item->half_day }}</td>
                <td>{{ $item->holiday }}</td>
                <td>{{ $item->net_salary }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
