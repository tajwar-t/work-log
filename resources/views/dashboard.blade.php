@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Dashboard</h1>

<!-- Stats cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-500 text-sm font-semibold">Users</h2>
        <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-500 text-sm font-semibold">Posts</h2>
        <p class="text-2xl font-bold">{{ \App\Models\Post::count() ?? 0 }}</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-500 text-sm font-semibold">Reports</h2>
        <p class="text-2xl font-bold">12</p>
    </div>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-500 text-sm font-semibold">Revenue</h2>
        <p class="text-2xl font-bold">$24,000</p>
    </div>
</div>

<!-- Charts section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Line chart -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-700 font-semibold mb-4">Monthly Users</h2>
        <canvas id="usersChart" height="200"></canvas>
    </div>

    <!-- Pie chart -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-700 font-semibold mb-4">Posts by Category</h2>
        <canvas id="postsChart" height="200"></canvas>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    const usersChart = new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Users',
                data: [12, 19, 14, 20, 28, 35],
                borderColor: 'rgb(34,197,94)',
                backgroundColor: 'rgba(34,197,94,0.2)',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            }
        }
    });

    const postsChart = new Chart(document.getElementById('postsChart'), {
        type: 'pie',
        data: {
            labels: ['Tech', 'Business', 'Lifestyle', 'Other'],
            datasets: [{
                label: 'Posts',
                data: [10, 15, 7, 3],
                backgroundColor: [
                    'rgb(59,130,246)',
                    'rgb(16,185,129)',
                    'rgb(234,179,8)',
                    'rgb(239,68,68)'
                ]
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection
