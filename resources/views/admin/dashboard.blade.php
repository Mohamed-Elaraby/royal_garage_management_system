@extends('admin.layouts.app')

@section('title', __('trans.home'))

@section('content')
    @if($branchName)
        <h2 class="text-center">
            إحصائيات فرع [ {{ $branchName }} ]
        </h2>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">احصائيات حالات الفحص خلال اليوم {{ \Carbon\Carbon::today()->format('d/m/Y') }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-wrench"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart">
                                <canvas id="chart_dataInDay" height="300"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>احصائيات</strong>
                            </p>
{{--{{ dd($dataDay) }}--}}
                            @foreach ($dataDay as $value)
                                <div class="progress-group">
                                    <span class="progress-text">{{ $value['check_status_name'] }}</span>
                                    <span class="progress-number"><b>{{$value['checks_count'] }}</b>/{{ $totalCheckCountInDay }}</span>

                                    <div class="progress sm">
                                        <div class="progress-bar" style="width: {{ $value['data_percentage'] }}%; background-color: {{ $value['check_status_color'] }}"></div>
                                    </div>
                                </div>
                            @endforeach
                        <!-- /.progress-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <div class="row">
                        @foreach ($dataDay as $value)
                            <div class="col-md-2 col-xs-4">
                                <div class="description-block">
                                    <span class="description-percentage text-{{ $value['data_percentage'] < 15?'red':'green' }}"><i class="fa fa-caret-{{ $value['data_percentage'] < 15?'down':'up' }}"></i> {{ $value['data_percentage'] }}%</span>
                                    <h5 class="description-header">{{ $value['checks_count'].'/'. $totalCheckCountInDay }}</h5>
                                    <span class="description-text">{{ $value['check_status_name'] }}</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        @endforeach
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">احصائيات حالات الفحص خلال شهر {{ \Carbon\Carbon::now()->monthName }}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-wrench"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <!-- /.box-header -->


                <div class="row">
                    {{--        @php($branchStatistic = request('branch_id')?request('branch_id'):'')--}}
                    @foreach ($dataMonth as $value)
                        @php($myArr = ['check_status_id='.$value['check_status_id']])
                        @php(request('branch_id') ? array_push($myArr, 'branch_id='.request('branch_id')): '')
                        <a href="{{ route('admin.check.index', $myArr) }}" style="text-decoration: none; color: #333">
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <span class="info-box-icon" style="background-color: {{ $value['check_status_color'] }}"><i class="fa fa-car"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">{{ $value['check_status_name'] }}</span>
                                        <span class="info-box-number">{{ $value['checks_count'] }}</span>
                                    </div>
                                    <!-- /.info-box-content -->
                                </div>
                                <!-- /.info-box -->
                            </div>
                        </a>
                        <!-- /.col -->
                    @endforeach

                </div>
                <!-- /.row -->


                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="text-center">
                                <strong style="direction: ltr; display: block;">{{ \Carbon\Carbon::now() -> firstOfMonth() ->format('j M, Y') }} - {{ \Carbon\Carbon::now() -> lastOfMonth() ->format('j M, Y') }}</strong>
                            </p>

                            <div class="chart">
                                <!-- Sales Chart Canvas -->
<!--                                <canvas id="salesChart" style="height: 180px;"></canvas>-->
                                <canvas id="chart_dataInMonth" height="300"></canvas>
                            </div>
                            <!-- /.chart-responsive -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-4">
                            <p class="text-center">
                                <strong>احصائيات</strong>
                            </p>

                            @foreach ($dataMonth as $value)
                                <div class="progress-group">
                                    <span class="progress-text">{{ $value['check_status_name'] }}</span>
                                    <span class="progress-number"><b>{{$value['checks_count'] }}</b>/{{ $totalCheckCountInMonth }}</span>

                                    <div class="progress sm">
                                        <div class="progress-bar" style="width: {{ $value['data_percentage'] }}%; background-color: {{ $value['check_status_color'] }}"></div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- /.progress-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- ./box-body -->
                <div class="box-footer">
                    <div class="row">
                        @foreach ($dataMonth as $value)
                            <div class="col-md-2 col-xs-4">
                                <div class="description-block">
                                    <span class="description-percentage text-{{ $value['data_percentage'] < 15?'red':'green' }}"><i class="fa fa-caret-{{ $value['data_percentage'] < 15?'down':'up' }}"></i> {{ $value['data_percentage'] }}%</span>
                                    <h5 class="description-header">{{ $value['checks_count'].'/'. $totalCheckCountInMonth }}</h5>
                                    <span class="description-text">{{ $value['check_status_name'] }}</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                        @endforeach
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
{{--    <!-- /.row -->--}}

{{--    <!-- Main row -->--}}
    <div class="row">
        <!-- Left col -->
        <div class="">
            <!-- MAP & BOX PANE -->
<!--            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Visitors Report</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                &lt;!&ndash; /.box-header &ndash;&gt;
                <div class="box-body no-padding">
                    <div class="row">
                        <div class="col-md-9 col-sm-8">
                            <div class="pad">
                                &lt;!&ndash; Map will be created here &ndash;&gt;
                                <div id="world-map-markers" style="height: 325px;"></div>
                            </div>
                        </div>
                        &lt;!&ndash; /.col &ndash;&gt;
                        <div class="col-md-3 col-sm-4">
                            <div class="pad box-pane-right bg-green" style="min-height: 280px">
                                <div class="description-block margin-bottom">
                                    <div class="sparkbar pad" data-color="#fff">90,70,90,70,75,80,70</div>
                                    <h5 class="description-header">8390</h5>
                                    <span class="description-text">Visits</span>
                                </div>
                                &lt;!&ndash; /.description-block &ndash;&gt;
                                <div class="description-block margin-bottom">
                                    <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                                    <h5 class="description-header">30%</h5>
                                    <span class="description-text">Referrals</span>
                                </div>
                                &lt;!&ndash; /.description-block &ndash;&gt;
                                <div class="description-block">
                                    <div class="sparkbar pad" data-color="#fff">90,50,90,70,61,83,63</div>
                                    <h5 class="description-header">70%</h5>
                                    <span class="description-text">Organic</span>
                                </div>
                                &lt;!&ndash; /.description-block &ndash;&gt;
                            </div>
                        </div>
                        &lt;!&ndash; /.col &ndash;&gt;
                    </div>
                    &lt;!&ndash; /.row &ndash;&gt;
                </div>
                &lt;!&ndash; /.box-body &ndash;&gt;
            </div>-->
            <!-- /.box -->
<!--            <div class="row">
                <div class="col-md-6">
                    &lt;!&ndash; DIRECT CHAT &ndash;&gt;
                    <div class="box box-warning direct-chat direct-chat-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Direct Chat</h3>

                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" title="3 New Messages" class="badge bg-yellow">3</span>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts"
                                        data-widget="chat-pane-toggle">
                                    <i class="fa fa-comments"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        &lt;!&ndash; /.box-header &ndash;&gt;
                        <div class="box-body">
                            &lt;!&ndash; Conversations are loaded here &ndash;&gt;
                            <div class="direct-chat-messages">
                                &lt;!&ndash; Message. Default to the left &ndash;&gt;
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
                                    </div>
                                    &lt;!&ndash; /.direct-chat-info &ndash;&gt;
                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                                    &lt;!&ndash; /.direct-chat-img &ndash;&gt;
                                    <div class="direct-chat-text">
                                        Is this template really for free? That's unbelievable!
                                    </div>
                                    &lt;!&ndash; /.direct-chat-text &ndash;&gt;
                                </div>
                                &lt;!&ndash; /.direct-chat-msg &ndash;&gt;

                                &lt;!&ndash; Message to the right &ndash;&gt;
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
                                    </div>
                                    &lt;!&ndash; /.direct-chat-info &ndash;&gt;
                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                                    &lt;!&ndash; /.direct-chat-img &ndash;&gt;
                                    <div class="direct-chat-text">
                                        You better believe it!
                                    </div>
                                    &lt;!&ndash; /.direct-chat-text &ndash;&gt;
                                </div>
                                &lt;!&ndash; /.direct-chat-msg &ndash;&gt;

                                &lt;!&ndash; Message. Default to the left &ndash;&gt;
                                <div class="direct-chat-msg">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left">Alexander Pierce</span>
                                        <span class="direct-chat-timestamp pull-right">23 Jan 5:37 pm</span>
                                    </div>
                                    &lt;!&ndash; /.direct-chat-info &ndash;&gt;
                                    <img class="direct-chat-img" src="dist/img/user1-128x128.jpg" alt="message user image">
                                    &lt;!&ndash; /.direct-chat-img &ndash;&gt;
                                    <div class="direct-chat-text">
                                        Working with AdminLTE on a great new app! Wanna join?
                                    </div>
                                    &lt;!&ndash; /.direct-chat-text &ndash;&gt;
                                </div>
                                &lt;!&ndash; /.direct-chat-msg &ndash;&gt;

                                &lt;!&ndash; Message to the right &ndash;&gt;
                                <div class="direct-chat-msg right">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-right">Sarah Bullock</span>
                                        <span class="direct-chat-timestamp pull-left">23 Jan 6:10 pm</span>
                                    </div>
                                    &lt;!&ndash; /.direct-chat-info &ndash;&gt;
                                    <img class="direct-chat-img" src="dist/img/user3-128x128.jpg" alt="message user image">
                                    &lt;!&ndash; /.direct-chat-img &ndash;&gt;
                                    <div class="direct-chat-text">
                                        I would love to.
                                    </div>
                                    &lt;!&ndash; /.direct-chat-text &ndash;&gt;
                                </div>
                                &lt;!&ndash; /.direct-chat-msg &ndash;&gt;

                            </div>
                            &lt;!&ndash;/.direct-chat-messages&ndash;&gt;

                            &lt;!&ndash; Contacts are loaded here &ndash;&gt;
                            <div class="direct-chat-contacts">
                                <ul class="contacts-list">
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user1-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Count Dracula
                                  <small class="contacts-list-date pull-right">2/28/2015</small>
                                </span>
                                                <span class="contacts-list-msg">How have you been? I was...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user7-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Sarah Doe
                                  <small class="contacts-list-date pull-right">2/23/2015</small>
                                </span>
                                                <span class="contacts-list-msg">I will be waiting for...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user3-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nadia Jolie
                                  <small class="contacts-list-date pull-right">2/20/2015</small>
                                </span>
                                                <span class="contacts-list-msg">I'll call you back at...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user5-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Nora S. Vans
                                  <small class="contacts-list-date pull-right">2/10/2015</small>
                                </span>
                                                <span class="contacts-list-msg">Where is your new...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user6-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  John K.
                                  <small class="contacts-list-date pull-right">1/27/2015</small>
                                </span>
                                                <span class="contacts-list-msg">Can I take a look at...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                    <li>
                                        <a href="#">
                                            <img class="contacts-list-img" src="dist/img/user8-128x128.jpg" alt="User Image">

                                            <div class="contacts-list-info">
                                <span class="contacts-list-name">
                                  Kenneth M.
                                  <small class="contacts-list-date pull-right">1/4/2015</small>
                                </span>
                                                <span class="contacts-list-msg">Never mind I found...</span>
                                            </div>
                                            &lt;!&ndash; /.contacts-list-info &ndash;&gt;
                                        </a>
                                    </li>
                                    &lt;!&ndash; End Contact Item &ndash;&gt;
                                </ul>
                                &lt;!&ndash; /.contatcts-list &ndash;&gt;
                            </div>
                            &lt;!&ndash; /.direct-chat-pane &ndash;&gt;
                        </div>
                        &lt;!&ndash; /.box-body &ndash;&gt;
                        <div class="box-footer">
                            <form action="#" method="post">
                                <div class="input-group">
                                    <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                                    <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat">Send</button>
                          </span>
                                </div>
                            </form>
                        </div>
                        &lt;!&ndash; /.box-footer&ndash;&gt;
                    </div>
                    &lt;!&ndash;/.direct-chat &ndash;&gt;
                </div>
                &lt;!&ndash; /.col &ndash;&gt;

            </div>-->
            <!-- /.row -->

        </div>
        <!-- /.col -->
        @if(!request('branch_id') && auth()->user()->hasRole(['super_owner', 'owner', 'general_manager', 'general_observer']))
            <div class="col-md-12">
                <!-- USERS LIST -->
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">العملاء الجدد خلال شهر {{ \Carbon\Carbon::now()->monthName }}</h3>

                        <div class="box-tools pull-right">
                            {{--                        <span class="label label-danger">العملاء الجدد اليوم {{ $allClientsCountAtToday }}</span>--}}
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <ul class="users-list clearfix">
                            @if($latestClientsInMonth)
                                @foreach ($latestClientsInMonth as $client)
                                    <li>
                                        <img src="{{ asset('storage' .DIRECTORY_SEPARATOR. 'default.png') }}" alt="User Image">
                                        <a class="users-list-name" href="#">{{ $client -> name }}</a>
                                        <span class="users-list-date">{{ $client -> created_at ->diffForHumans() }} </span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        <!-- /.users-list -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <a href="{{ route('admin.clients.index', ['month' => \Carbon\Carbon::now()->month]) }}" class="uppercase">عرض الجميع</a>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!--/.box -->
            </div>
        @endif
        <!-- /.col -->
        <div class="col-md-12">
            <!-- TABLE: LATEST ORDERS -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ __('trans.clients visit log') }}</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="table-responsive">
                                    <table class="table no-margin clients_log">
                                        <thead>
                                        <tr>
                                            <th>{{ __('trans.client name') }}</th>
                                            <th>{{ __('trans.client register date') }}</th>
                                            <th>{{ __('trans.phone') }}</th>
                                            <th>{{ __('trans.branch name') }}</th>
                                            <th>{{ __('trans.visit branch date') }}</th>
                                            <th>{{ __('trans.status') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($checksLogAtToday)
                                        @foreach($checksLogAtToday as $data)

                                            @php($clientRegisterDate = $data -> car ? \Carbon\Carbon::parse($data -> car -> client -> created_at)->toDateString() :'')
                                            @php($visitBranchDate = $data -> created_at ? \Carbon\Carbon::parse($data -> created_at)->toDateString() :'')
                                            <tr>
                                                <td>{{ $data -> car ? $data -> car -> client -> name : '' }}</td>
                                                <td>{{ $clientRegisterDate }}</td>
                                                <td>{{ $data -> car ? $data -> car -> client -> phone : '' }}</td>
                                                <td><a href="{{ route('dashboard', ['branch_id' => $data -> branch -> id]) }}">{{ $data -> branch -> name ?? '' }}</a></td>
                                                <td>
                                                    {{ $visitBranchDate }}
                                                </td>
                                                <td>
{{--                                                    {{ $clientRegisterDate == $visitBranchDate ? '<span class="label label-success">'.__('trans.new client').'</span>': '<span class="label label-primary">'.__('trans.old client').'</span>' }}--}}
                                                    @if($clientRegisterDate == $visitBranchDate)
                                                        <span class="label label-success">{{ __('trans.new client') }}</span>
                                                    @else
                                                        <span class="label label-primary">{{ __('trans.old client') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-body -->
{{--                            <div class="box-footer clearfix">--}}
{{--                                <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>--}}
{{--                                <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>--}}
{{--                            </div>--}}
{{--                            <!-- /.box-footer -->--}}
                        </div>
            <!-- /.box -->
        </div>
<!--        <div class="col-md-4">
            &lt;!&ndash; Info Boxes Style 2 &ndash;&gt;
            <div class="info-box bg-yellow">
                <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Inventory</span>
                    <span class="info-box-number">5,200</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">
                    50% Increase in 30 Days
                  </span>
                </div>
                &lt;!&ndash; /.info-box-content &ndash;&gt;
            </div>
            &lt;!&ndash; /.info-box &ndash;&gt;
            <div class="info-box bg-green">
                <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Mentions</span>
                    <span class="info-box-number">92,050</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 20%"></div>
                    </div>
                    <span class="progress-description">
                    20% Increase in 30 Days
                  </span>
                </div>
                &lt;!&ndash; /.info-box-content &ndash;&gt;
            </div>
            &lt;!&ndash; /.info-box &ndash;&gt;
            <div class="info-box bg-red">
                <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Downloads</span>
                    <span class="info-box-number">114,381</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 70%"></div>
                    </div>
                    <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
                </div>
                &lt;!&ndash; /.info-box-content &ndash;&gt;
            </div>
            &lt;!&ndash; /.info-box &ndash;&gt;
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Direct Messages</span>
                    <span class="info-box-number">163,921</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 40%"></div>
                    </div>
                    <span class="progress-description">
                    40% Increase in 30 Days
                  </span>
                </div>
                &lt;!&ndash; /.info-box-content &ndash;&gt;
            </div>
            &lt;!&ndash; /.info-box &ndash;&gt;

            &lt;!&ndash; Browser Usage &ndash;&gt;
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Browser Usage</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                &lt;!&ndash; /.box-header &ndash;&gt;
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="pieChart" height="150"></canvas>
                            </div>
                            &lt;!&ndash; ./chart-responsive &ndash;&gt;
                        </div>
                        &lt;!&ndash; /.col &ndash;&gt;
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                                <li><i class="fa fa-circle-o text-green"></i> IE</li>
                                <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                                <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                                <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                                <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>
                            </ul>
                        </div>
                        &lt;!&ndash; /.col &ndash;&gt;
                    </div>
                    &lt;!&ndash; /.row &ndash;&gt;
                </div>
                &lt;!&ndash; /.box-body &ndash;&gt;
                <div class="box-footer no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#">United States of America
                                <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                        <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                        </li>
                        <li><a href="#">China
                                <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
                    </ul>
                </div>
                &lt;!&ndash; /.footer &ndash;&gt;
            </div>
            &lt;!&ndash; /.box &ndash;&gt;

            &lt;!&ndash; PRODUCT LIST &ndash;&gt;
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Recently Added Products</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                &lt;!&ndash; /.box-header &ndash;&gt;
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-50x50.gif" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Samsung TV
                                    <span class="label label-warning pull-right">$1800</span></a>
                                <span class="product-description">
                          Samsung 32" 1080p 60Hz LED Smart HDTV.
                        </span>
                            </div>
                        </li>
                        &lt;!&ndash; /.item &ndash;&gt;
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-50x50.gif" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Bicycle
                                    <span class="label label-info pull-right">$700</span></a>
                                <span class="product-description">
                          26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                        </span>
                            </div>
                        </li>
                        &lt;!&ndash; /.item &ndash;&gt;
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-50x50.gif" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Xbox One <span
                                        class="label label-danger pull-right">$350</span></a>
                                <span class="product-description">
                          Xbox One Console Bundle with Halo Master Chief Collection.
                        </span>
                            </div>
                        </li>
                        &lt;!&ndash; /.item &ndash;&gt;
                        <li class="item">
                            <div class="product-img">
                                <img src="dist/img/default-50x50.gif" alt="Product Image">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">PlayStation 4
                                    <span class="label label-success pull-right">$399</span></a>
                                <span class="product-description">
                          PlayStation 4 500GB Console (PS4)
                        </span>
                            </div>
                        </li>
                        &lt;!&ndash; /.item &ndash;&gt;
                    </ul>
                </div>
                &lt;!&ndash; /.box-body &ndash;&gt;
                <div class="box-footer text-center">
                    <a href="javascript:void(0)" class="uppercase">View All Products</a>
                </div>
                &lt;!&ndash; /.box-footer &ndash;&gt;
            </div>
            &lt;!&ndash; /.box &ndash;&gt;
        </div>
        &lt;!&ndash; /.col &ndash;&gt;-->
    </div>
    <!-- /.row -->

@endsection
@push('links')
    <meta http-equiv="refresh" content="300">
    <style>
        .clients_log table {
            width: 716px; /* 140px * 5 column + 16px scrollbar width */
            border-spacing: 0;
        }

        .clients_log tbody, .clients_log thead tr { display: block; }

        .clients_log tbody {
            height: 200px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .clients_log tbody td, thead th {
            width: 200px;
        }

        .clients_log thead th:last-child {
            width: 156px; /* 140px + 16px scrollbar width */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
    <script>
        let myDataInMonth = '{!! json_encode($dataMonth) !!}';
        let ctx_month = document.getElementById('chart_dataInMonth').getContext('2d');
        let dataInMonth = {
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [],
            datasets: [{
                label: 'احصائيات',
                data: [],
                backgroundColor: [],
                borderWidth:5
            }],
        };
        $.each(JSON.parse(myDataInMonth), function (index, element) {
            dataInMonth.labels.push(element.check_status_name);
            dataInMonth.datasets[0].data.push(element.data_percentage);
            dataInMonth.datasets[0].backgroundColor.push(element.check_status_color);
        })
        let optionInMonth = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0
                    }
                }]
            }
        };
        new Chart(ctx_month, {
            data: dataInMonth,
            type: 'bar',
            options: optionInMonth
        });

    </script>

    <script>
        let myDataInDay = '{!! json_encode($dataDay) !!}';
        let ctx_day = document.getElementById('chart_dataInDay').getContext('2d');
        let dataInDay = {
            // These labels appear in the legend and in the tooltips when hovering different arcs
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [],
            }],
        };
        $.each(JSON.parse(myDataInDay), function (index, element) {
            dataInDay.labels.push(element.check_status_name);
            dataInDay.datasets[0].data.push(element.data_percentage);
            dataInDay.datasets[0].backgroundColor.push(element.check_status_color);
        })
        let optionInDay = {};
        new Chart(ctx_day, {
            data: dataInDay,
            type: 'polarArea',
        });

    </script>

@endpush
