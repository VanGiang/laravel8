@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div>
  <canvas id="myChart2"></canvas>
</div>

@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script src="/js/chart.js"></script>

<script>

// const labels = [
//   'January',
//   'February',
//   'March',
//   'April',
//   'May',
//   'June',
// ];


const labels = @json($daily);

// console.log(typeof labels);
// console.log(labels);


const prices = @json($prices);

// console.log(prices);
const data = {
  labels: labels,
  datasets: [{
    label: 'My First dataset',
    backgroundColor: 'rgb(255, 99, 132)',
    borderColor: 'rgb(255, 99, 132)',
    // data: [0, 10, 5, 2, 20, 30, 45],
    data: prices,
  }]
};

const config = {
  type: 'line',
  data,
  options: {}
};

var myChart = new Chart(
    document.getElementById('myChart2'),
    config
  );
</script>
@stop
