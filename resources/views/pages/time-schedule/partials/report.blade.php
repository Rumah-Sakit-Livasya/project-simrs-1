@extends('inc.layout')
@section('title', 'Time Schedule')
@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="report-container">
            <h2>Time Schedule Report</h2>

            <div class="charts-container" style="display: flex; justify-content: space-between;">
                <div style="flex: 1; margin-right: 10px;">
                    <canvas id="onlineOfflineMeetingsChart"></canvas>
                </div>
                <div style="flex: 1; margin-right: 10px;">
                    <canvas id="onlineOfflineActivitiesChart"></canvas>
                </div>
                <div style="flex: 1;">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <div class="meeting-details">
                <h3>Meeting Details</h3>
                <table id="meetingDetailsTable" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Total Participants</th>
                            <th>Participants Present</th>
                            <th>Participants Absent</th>
                            <th>Absent Employees</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($statistics['meeting_details'] as $detail)
                            <tr>
                                <td>{{ $detail['title'] }}</td>
                                <td>{{ $detail['total_participants'] }}</td>
                                <td>{{ $detail['participants_present'] }}</td>
                                <td>{{ $detail['participants_absent'] }}</td>
                                <td>
                                    @if (!empty($detail['absent_employee_names']))
                                        <ul>
                                            @foreach ($detail['absent_employee_names'] as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        No absent employees
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="employee-statistics">
                <h3>Employee Attendance Statistics</h3>
                <h4>Employees Who Always Attend:</h4>
                <ul>
                    @if (!empty($statistics['always_present_employees']))
                        @foreach ($statistics['always_present_employees'] as $employee)
                            <li>{{ $employee }}</li>
                        @endforeach
                    @else
                        <li>No employees always present</li>
                    @endif
                </ul>

                <h4>Employees Who Never Attend:</h4>
                <ul>
                    @if (!empty($statistics['always_absent_employees']))
                        @foreach ($statistics['always_absent_employees'] as $employee)
                            <li>{{ $employee }}</li>
                        @endforeach
                    @else
                        <li>No employees never absent</li>
                    @endif
                </ul>
            </div>
        </div>
    </main>
@endsection
@section('plugin')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <script>
        $(document).ready(function() {
            $('#meetingDetailsTable').DataTable();
        });

        // Chart for Online vs Offline Meetings
        const onlineOfflineMeetingsCtx = document.getElementById('onlineOfflineMeetingsChart').getContext('2d');
        const onlineOfflineMeetingsChart = new Chart(onlineOfflineMeetingsCtx, {
            type: 'bar',
            data: {
                labels: ['Online Meetings', 'Offline Meetings'],
                datasets: [{
                    label: 'Meetings',
                    data: [
                        {{ $statistics['online_meetings'] }},
                        {{ $statistics['offline_meetings'] }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart for Online vs Offline Activities
        const onlineOfflineActivitiesCtx = document.getElementById('onlineOfflineActivitiesChart').getContext('2d');
        const onlineOfflineActivitiesChart = new Chart(onlineOfflineActivitiesCtx, {
            type: 'bar',
            data: {
                labels: ['Online Activities', 'Offline Activities'],
                datasets: [{
                    label: 'Activities',
                    data: [
                        {{ $statistics['online_activities'] }},
                        {{ $statistics['offline_activities'] }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Chart for Attendance
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: ['Participants Present', 'Participants Absent'],
                datasets: [{
                    label: 'Attendance',
                    data: [
                        {{ $statistics['participants_present'] }},
                        {{ $statistics['participants_absent'] }}
                    ],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(255, 99, 132, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
