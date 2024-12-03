@extends('DashBoard.header')

@section('content')
<div class="container">
    <h1>Analytics Dashboard</h1>
        <canvas id="analyticsChart"></canvas>
    </div>



<script>
    const ctx = document.getElementById('analyticsChart').getContext('2d');

    const analyticsChart = new Chart(ctx, {
        type: 'line', // You can also use 'bar', 'pie', etc.
        data: {
            labels: @json($labels), // Pass the labels array from the controller
            datasets: [
                {
                    label: 'Daily Posts',
                    data: @json($postsData), // Pass post counts
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                },
                {
                    label: 'Daily Registered Users',
                    data: @json($usersData), // Pass user counts
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 2,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Daily Analytics (Last 7 Days)' }
            },
            scales: {
                x: { title: { display: true, text: 'Date' } },
                y: { title: { display: true, text: 'Count' }, beginAtZero: true }
            }
        }
    });
</script>
@endsection
