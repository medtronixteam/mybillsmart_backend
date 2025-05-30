@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">I Hope You doing well , {{auth()->user()->name}}</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card border-right shadow">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium">{{$totalUsers}}</h2>
                                    </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Users</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card border-right shadow">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium">{{$totalagent}}</h2>
                                           </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Agents</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 col-md-6">
                    <div class="card border-right shadow">
                        <div class="card-body">
                            <div class="d-flex d-lg-flex d-md-block align-items-center">
                                <div>
                                    <div class="d-inline-flex align-items-center">
                                        <h2 class="text-dark mb-1 font-weight-medium">{{$totalgroup}}</h2>
                                           </div>
                                    <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Group Admins</h6>
                                </div>
                                <div class="ml-auto mt-md-3 mt-lg-0">
                                    <span class="opacity-7 text-muted"><i data-feather="user-plus"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end of row --}}
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h3>Most Invoices</h3>
                        </div>
                        <div class="card-body">
                            <table class="table ">
                                <tr>
                                    <th>#</th>
                                    <th>Group Admin</th>
                                    <th>Email Admin</th>
                                    <th>Invoices</th>
                                </tr>
                                @foreach ($topGroups as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $item->groupAdmin->name }}</td>
                                        <td>{{ $item->groupAdmin->email }}</td>
                                        <td>{{ $item->invoice_count }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Total Sales</h4>
                            <div id="campaign-v2" class="mt-2" style="height:283px; width:100%;"></div>
                            <ul class="list-style-none mb-0">
                                <li>
                                    <i class="fas fa-circle text-primary font-10 mr-2"></i>
                                    <span class="text-muted">Starter</span>
                                    <span class="text-dark float-right font-weight-medium">€{{$salesNumbers[0]['Starter']}}</span>
                                </li>
                                <li class="mt-3">
                                    <i class="fas fa-circle text-danger font-10 mr-2"></i>
                                    <span class="text-muted">Pro</span>
                                    <span class="text-dark float-right font-weight-medium">€{{$salesNumbers[0]['Pro']}}</span>
                                </li>
                                <li class="mt-3">
                                    <i class="fas fa-circle text-cyan font-10 mr-2"></i>
                                    <span class="text-muted">Enterprise</span>
                                    <span class="text-dark float-right font-weight-medium">€{{$salesNumbers[0]['Enterprise']}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8     col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class=" card-title">Net Income</h4>
                            <div class="net-income mt-4 position-relative" style="height:294px;"></div>
                            <ul class="list-inline text-center mt-5 mb-2">
                                <li class="list-inline-item text-muted font-italic">Sales for this month</li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <h4 class="card-title mb-0">Earning Statistics</h4>
                                <div class="ml-auto">
                                    <div class="dropdown sub-dropdown">
                                        <button class="btn btn-link text-muted dropdown-toggle" type="button"
                                            id="dd1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i data-feather="more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dd1">
                                            <a class="dropdown-item" href="#">Insert</a>
                                            <a class="dropdown-item" href="#">Update</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="pl-4 mb-5">
                                <div class="stats ct-charts position-relative" style="height: 315px;"></div>
                            </div>
                            <ul class="list-inline text-center mt-4 mb-0">
                                <li class="list-inline-item text-muted font-italic">Earnings for this month</li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
    </div>
@endsection
@push('scripts')
   <script>
    $(function () {

// ==============================================================
// Campaign
// ==============================================================
var salesData = @json($salesNumbers);
var chartData = @json($yearlyChartData);
//yearlyChartData
var sixMonthData = {
        labels: chartData.labels,
        series: chartData.series
    };
    // var data = {
    //     labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    //     series: [
    //         [5, 4, 3, 7, 5, 10]
    //     ]
    // };

//console.log(chartData);
var chart1 = c3.generate({
    bindto: '#campaign-v2',
    data: {
        columns: [
    ['Starter', salesData[1].Starter],
    ['Pro', salesData[1].Pro],
    ['Enterprise', salesData[1].Enterprise]
],

        type: 'donut',
        tooltip: {
            show: true
        }
    },
    donut: {
        label: {
            show: false
        },
        title: 'Sales',
        width: 18
    },

    legend: {
        hide: true
    },
    color: {
        pattern: [

            '#5f76e8',
            '#ff4f70',
            '#01caf1'
        ]
    }
});



//-----------
var data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        series: [
            [5, 4, 3, 7, 5, 10]
        ]
    };

    var options = {
        axisX: {
            showGrid: false
        },
        seriesBarDistance: 1,
        chartPadding: {
            top: 15,
            right: 15,
            bottom: 5,
            left: 0
        },
        plugins: [
            Chartist.plugins.tooltip()
        ],
        width: '100%'
    };

    var responsiveOptions = [
        ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
                labelInterpolationFnc: function (value) {
                    return value[0];
                }
            }
        }]
    ];
    new Chartist.Bar('.net-income', sixMonthData, options, responsiveOptions);

});





   </script>

@endpush
