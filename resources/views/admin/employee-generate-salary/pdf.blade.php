<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid #000;
            padding: 6px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .small {
            font-size: 10px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }
    </style>

</head>

<body>

  <div class="header">

    <img src="{{ public_path(setting('logo')) }}" height="55" style="margin-bottom:5px;">

    <div style="font-size:16px;font-weight:bold;">
        {{ setting('company_name') }}
    </div>

    <div class="small">
        {{ setting('company_address') }}
    </div>

    <div class="small">
        Email : {{ setting('company_email') }}
    </div>

    <div class="small">
        Phone : {{ setting('company_phone') }}
    </div>

    <div style="margin-top:6px;font-size:13px;font-weight:bold;">
        Pay Slip For The Month Of
        {{ \Carbon\Carbon::create()->month($salary->month)->format('F') }}
        {{ $salary->year }}
    </div>

</div>


    <table>

        <tr>
            <td>Ref No</td>
            <td>{{ $salary->employee->code }}</td>

            <td>Employee Name</td>
            <td>{{ $salary->employee->full_name }}</td>
        </tr>

        <tr>
            <td>DOJ</td>
            <td>{{ $salary->employee->joining_date }}</td>

            <td>Father Name</td>
            <td>{{ $salary->employee->father_name }}</td>
        </tr>
        
        <tr>
            <td>PF No</td>
            <td>{{ $salary->employee->pf_number }}</td>

            <td>Branch</td>
            <td>{{ $salary->employee->branch?->branches_name }}</td>
        </tr>

        <tr>
            <td>ESI</td>
            <td>{{ $salary->employee->esi_number }}</td>

            <td>Designation</td>
            <td>{{ $salary->employee->role }}</td>
        </tr>

        <tr>
            <td>UAN</td>
            <td>{{ $salary->employee->uan_no }}</td>

            <td>Department</td>
            <td>{{ $salary->employee->departments?->department_name }}</td>
        </tr>



        <tr>
            <td>A/C No</td>
            <td>{{ $salary->employee->bankDetails?->account_number }}</td>

            <td>IFSC</td>
            <td>{{ $salary->employee->bankDetails?->ifsc_code }}</td>
        </tr>

    </table>


    <br>


    <table>

        <tr class="center">
            <th>Present Days</th>
            <th>Holidays</th>
            <th>Week Off</th>
            <th>Pay Days</th>
        </tr>

        <tr class="center">
            <td>{{ $salary->present_days }}</td>
            <td>{{ $salary->holidays }}</td>
            <td>{{ $salary->weekoff }}</td>
            <td>{{ $salary->pay_days }}</td>
        </tr>

    </table>


    <br>


    <table>

        <tr class="center">

            <th>Particulars</th>
            <th>CTC</th>
            <th>Gross Salary</th>

            <th>Deductions</th>
            <th>Amount</th>

        </tr>


        <tr>
            <td>Basic</td>
            <td class="right">{{ number_format($salary->basic_salary, 2) }}</td>
            <td class="right">{{ number_format($salary->basic_salary, 2) }}</td>

            <td>PF</td>
            <td class="right">{{ number_format($salary->pf_amount, 2) }}</td>
        </tr>


        <tr>
            <td>HRA</td>
            <td class="right">{{ number_format($salary->hra_amount, 2) }}</td>
            <td class="right">{{ number_format($salary->hra_amount, 2) }}</td>

            <td>ESI</td>
            <td class="right">{{ number_format($salary->esi_amount, 2) }}</td>
        </tr>


        <tr>
            <td>Conveyance</td>
            <td class="right">{{ number_format($salary->conveyance_amount, 2) }}</td>
            <td class="right">{{ number_format($salary->conveyance_amount, 2) }}</td>

            <td>TDS</td>
            <td class="right">{{ number_format($salary->tds_amount, 2) }}</td>
        </tr>


        <tr>
            <td>Expenses</td>
            <td class="right">{{ number_format($salary->expense_total, 2) }}</td>
            <td class="right">{{ number_format($salary->expense_total, 2) }}</td>

            <td>Loan Deduction</td>
            <td class="right">{{ number_format($salary->loan_amount_deduction, 2) }}</td>
        </tr>


        <tr>

            <th>Total</th>

            <th class="right">
                {{ number_format(
                    $salary->basic_salary + $salary->hra_amount + $salary->conveyance_amount + $salary->expense_total,
                    2,
                ) }}
            </th>

            <th class="right">
                {{ number_format(
                    $salary->basic_salary + $salary->hra_amount + $salary->conveyance_amount + $salary->expense_total,
                    2,
                ) }}
            </th>

            <th>Total</th>

            <th class="right">

                {{ number_format(
                    $salary->pf_amount + $salary->esi_amount + $salary->tds_amount + $salary->loan_amount_deduction,
                    2,
                ) }}

            </th>

        </tr>

    </table>


    <br>


    <table>

        <tr>

            <td><b>Net Pay (INR)</b></td>

            <td class="right">
                <b>{{ number_format($salary->net_salary, 2) }}</b>
            </td>

        </tr>

        <tr>

            <td colspan="2">

                <b>In Words :</b>
                {{ numberToIndianCurrency($salary->net_salary) }}

            </td>

        </tr>

    </table>


    <br>

    <div class="small">

        Bank :
        {{ $salary->employee->bankDetails?->banks?->name }}

        &nbsp;&nbsp;&nbsp;

        A/C :
        {{ $salary->employee->bankDetails?->account_number }}

    </div>


</body>

</html>
