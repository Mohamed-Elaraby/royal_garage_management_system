<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @php($profile_picture_path = Auth::user() -> image_name == 'default.png' ? 'storage' .DIRECTORY_SEPARATOR. 'default.png' : Auth::user() -> profile_picture_path)
                <img src="{{ asset($profile_picture_path)}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i>Online</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        {{--<form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
            </div>
        </form>--}}
        <!-- /.search form -->





        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">

<!--            <li class="treeview">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active"><a href="index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                    <li><a href="index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
                </ul>
            </li>-->



            <li class="header">{{ __('trans.main pages') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <!-- Home Page Route -->
            <li class="active">
                <a href="{{ route('dashboard') }}">
                    <i class="fa fa-home"></i>
                    <span>@lang('trans.home')</span>
                </a>
            </li>

            <!-- HR Route -->
            @if(auth()->user()->hasPermission('read-hr'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-handshake-o"></i> <span>{{ __('trans.hr') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Employees Route -->
                        <li>
                            <a href="{{ route('employee.employees.index') }}">
                                <i class="fa fa-users"></i>
                                <span>@lang('trans.all employee')</span>
                            </a>
                        </li>

                        <!-- Nationality Route -->
                        <li>
                            <a href="{{ route('employee.nationalities.index') }}">
                                <i class="fa fa-flag-checkered"></i>
                                <span>@lang('trans.all nationalities')</span>
                            </a>
                        </li>

                        <!-- Salary Route -->
                        <li>
                            <a href="{{ route('employee.salaries.index') }}">
                                <i class="fa fa-money"></i>
                                <span>@lang('trans.all salaries')</span>
                            </a>
                        </li>

                        <!-- Advance Route -->
                        <li>
                            <a href="{{ route('employee.advances.index') }}">
                                <i class="fa fa-strikethrough"></i>
                                <span>@lang('trans.all advances')</span>
                            </a>
                        </li>

                        <!-- Reward Route -->
                        <li>
                            <a href="{{ route('employee.rewards.index') }}">
                                <i class="fa fa-gift"></i>
                                <span>@lang('trans.all rewards')</span>
                            </a>
                        </li>

                        <!-- Discount Route -->
                        <li>
                            <a href="{{ route('employee.discounts.index') }}">
                                <i class="fa fa-strikethrough"></i>
                                <span>@lang('trans.all discounts')</span>
                            </a>
                        </li>

                        <!-- Vacation Route -->
                        <li>
                            <a href="{{ route('employee.vacations.index') }}">
                                <i class="fa fa-sun-o"></i>
                                <span>@lang('trans.all vacations')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <!-- Reports Route -->
            @if(auth()->user()->hasPermission('read-reports'))
                <li>
                    <a href="{{ route('admin.reports') }}">
                        <i class="fa fa-edit"></i>
                        <span>@lang('trans.reports')</span>
                    </a>
                </li>
            @endif

            <!-- Check Route -->
            <li>
                <a href="{{ route('admin.check.index') }}">
                    <i class="fa fa-wrench"></i>
                    <span>@lang('trans.all check')</span>
                </a>
            </li>

            <!-- Orders Routes -->
            @if(auth()->user()->hasPermission('read-saleOrders') || auth()->user()->hasPermission('read-purchaseOrders'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-address-card-o"></i> <span>{{ __('trans.invoices') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Sale Orders Route -->
                        @if(auth()->user()->hasPermission('read-saleOrders'))
                            <li>
                                <a href="{{ route('admin.saleOrders.index', ['status' => 'close']) }}">
                                    <i class="fa fa-file-word-o"></i>
                                    <span>@lang('trans.all sale orders')</span>
                                </a>
                            </li>
                        @endif

                        <!-- Purchase Orders Route -->
                        @if(auth()->user()->hasPermission('read-purchaseOrders'))
                            <li>
                                <a href="{{ route('admin.purchaseOrders.index', ['status' => 'close']) }}">
                                    <i class="fa fa-file-word-o"></i>
                                    <span>@lang('trans.all purchase orders')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <!-- Open Orders Routes -->
            @if(auth()->user()->hasPermission('read-openSaleOrders') || auth()->user()->hasPermission('read-openPurchaseOrders'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-envelope-open-o"></i> <span>{{ __('trans.open invoices') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- open Sale Orders Route -->
                        @if(auth()->user()->hasPermission('read-openSaleOrders'))
                            <li>
                                <a href="{{ route('admin.saleOrders.index', ['status' => 'open']) }}">
                                    <i class="fa fa-folder-open"></i>
                                    <span>@lang('trans.all open sale orders')</span>
                                </a>
                            </li>
                        @endif

                        <!-- open Purchase Orders Route -->
                        @if(auth()->user()->hasPermission('read-openPurchaseOrders'))
                            <li>
                                <a href="{{ route('admin.purchaseOrders.index', ['status' => 'open']) }}">
                                    <i class="fa fa-folder-open"></i>
                                    <span>@lang('trans.all open purchase orders')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <!-- Order Returns Routes -->
            @if(auth()->user()->hasPermission('read-saleOrderReturns') || auth()->user()->hasPermission('read-purchaseOrderReturns'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-reply"></i> <span>{{ __('trans.returns invoices') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Sale Orders Route -->
                        @if(auth()->user()->hasPermission('read-saleOrderReturns'))
                            <li>
                                <a href="{{ route('admin.saleOrderReturns.index') }}">
                                    <i class="fa fa-file-word-o"></i>
                                    <span>@lang('trans.all sale order returns')</span>
                                </a>
                            </li>
                        @endif

                        <!-- Purchase Orders Route -->
                        @if(auth()->user()->hasPermission('read-purchaseOrderReturns'))
                            <li>
                                <a href="{{ route('admin.purchaseOrderReturns.index') }}">
                                    <i class="fa fa-file-word-o"></i>
                                    <span>@lang('trans.all purchase order returns')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- Price List Routes -->
            @if(auth()->user()->hasPermission('read-priceList'))
                <li>
                    <a href="{{ route('admin.priceList.index') }}">
                        <i class="fa fa-edit"></i>
                        <span>{{ __('trans.all price lists') }}</span>
                    </a>
                </li>
            @endif


            <!-- Expenses Routes -->
            @if(auth()->user()->hasPermission('read-expenses') || auth()->user()->hasPermission('read-expensesTypes'))
                <li class="treeview">
                <a href="#">
                    <i class="fa fa-money"></i> <span>{{ __('trans.all expenses') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <!-- Expenses Type Route -->
                    @if(auth()->user()->hasPermission('read-expensesTypes'))
                        <li>
                            <a href="{{ route('admin.expensesTypes.index') }}">
                                <i class="fa fa-money"></i>
                                <span>@lang('trans.all expenses type')</span>
                            </a>
                        </li>
                    @endif

                    <!-- Expenses Route -->
                    @if(auth()->user()->hasPermission('read-expenses'))
                        <li>
                            <a href="{{ route('admin.expenses.index') }}">
                                <i class="fa fa-money"></i>
                                <span>@lang('trans.all expenses')</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            @endif

            <!-- Clients Route -->
            @if(auth()->user()->hasPermission('read-clients'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-user-secret"></i> <span>{{ __('trans.clients') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.clients.index') }}">
                                <i class="fa fa-user-secret"></i>
                                <span>@lang('trans.all client')</span>
                            </a>
                        </li>

                        <!-- client payments Codes Route -->
                        @if(auth()->user()->hasPermission('read-clientPayments'))
                            <li>
                                <a href="{{ route('admin.clientPayments.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span>@lang('trans.all client payments')</span>
                                </a>
                            </li>
                        @endif

                        <!-- client collecting Codes Route -->
                        @if(auth()->user()->hasPermission('read-clientCollecting'))
                            <li>
                                <a href="{{ route('admin.clientCollecting.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span>@lang('trans.all client collecting')</span>
                                </a>
                            </li>
                        @endif

                        <!-- Client Cars Route -->
                        <li>
                            <a href="{{ route('admin.cars.index') }}">
                                <i class="fa fa-car"></i>
                                <span>@lang('trans.all clients cars')</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Suppliers Route -->
            @if(auth()->user()->hasPermission('read-suppliers'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-user-times"></i> <span>{{ __('trans.suppliers') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('admin.suppliers.index') }}">
                                <i class="fa fa-user-secret"></i>
                                <span>@lang('trans.all supplier')</span>
                            </a>
                        </li>

                        <!-- supplier payments Codes Route -->
                        @if(auth()->user()->hasPermission('read-supplierPayments'))
                            <li>
                                <a href="{{ route('admin.supplierPayments.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span>@lang('trans.all supplier payments')</span>
                                </a>
                            </li>
                        @endif

                    <!-- supplier collecting Codes Route -->
                        @if(auth()->user()->hasPermission('read-supplierCollecting'))
                            <li>
                                <a href="{{ route('admin.supplierCollecting.index') }}">
                                    <i class="fa fa-money"></i>
                                    <span>@lang('trans.all supplier collecting')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- Categories Route -->
            @if(auth()->user()->hasPermission('read-categories') || auth()->user()->hasPermission('read-subCategories'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list"></i> <span>{{ __('trans.categories') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Categories Route -->
                        @if(auth()->user()->hasPermission('read-categories'))
                            <li>
                                <a href="{{ route('admin.categories.index') }}">
                                    <i class="fa fa-list-alt"></i>
                                    <span>@lang('trans.all categories')</span>
                                </a>
                            </li>
                        @endif

                    <!-- sub Categories Route -->
                        @if(auth()->user()->hasPermission('read-subCategories'))
                            <li>
                                <a href="{{ route('admin.subCategories.index') }}">
                                    <i class="fa fa-list-alt"></i>
                                    <span>@lang('trans.all sub categories')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- Products Route -->
            @if(auth()->user()->hasPermission('read-products') || auth()->user()->hasPermission('read-productCodes'))
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-cubes"></i> <span>{{ __('trans.all products') }}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <!-- Products Route -->
                        @if(auth()->user()->hasPermission('read-products'))
                            <li>
                                <a href="{{ route('admin.products.index') }}">
                                    <i class="fa fa-cart-arrow-down"></i>
                                    <span>@lang('trans.all products')</span>
                                </a>
                            </li>
                        @endif

                    <!-- Products Codes Route -->
                        @if(auth()->user()->hasPermission('read-productCodes'))
                            <li>
                                <a href="{{ route('admin.productCodes.index') }}">
                                    <i class="fa fa-list-ol"></i>
                                    <span>@lang('trans.all products codes')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <!-- Internal Transfer Route -->
            @if (Auth::user()->hasRole(['super_owner']))
                <li>
                    <a href="{{ route('admin.product.transfer.index') }}">
                        <i class="fa fa-arrow-right"></i>
                        <span>@lang('trans.all internal transfers')</span>
                    </a>
                </li>
            @endif

        <!-- Users Route -->
            @if(auth()->user()->hasPermission('read-users'))
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <i class="fa fa-users"></i>
                        <span>@lang('trans.all user')</span>
                    </a>
                </li>
            @endif


            <!-- Branches Route -->
            @if(auth()->user()->hasPermission('read-branches'))
                <li>
                    <a href="{{ route('admin.branches.index') }}">
                        <i class="fa fa-building-o"></i>
                        <span>@lang('trans.all branch')</span>
                    </a>
                </li>
            @endif


            <!-- Technicals Route -->
            @if(auth()->user()->hasPermission('read-technicals'))
                <li>
                    <a href="{{ route('admin.technicals.index') }}">
                        <i class="fa fa-child"></i>
                        <span>@lang('trans.all technical')</span>
                    </a>
                </li>
            @endif


            <!-- Engineers Route -->
            @if(auth()->user()->hasPermission('read-engineers'))
                <li>
                    <a href="{{ route('admin.engineers.index') }}">
                        <i class="fa fa-cogs"></i>
                        <span>@lang('trans.all engineer')</span>
                    </a>
                </li>
            @endif

            @if (!Auth::user()->hasRole(['super_owner', 'owner', 'general_manager', 'deputy_manager']))
            <!-- Money Safe Route -->
                @if(auth()->user()->hasPermission('read-moneySafe'))
                    <li>
                        <a href="{{ route('admin.moneySafe.index', Auth::user() -> branch_id) }}">
                            <i class="fa fa-money"></i>
                            <span>@lang('trans.money safe')</span>
                        </a>
                    </li>
                @endif

            <!-- Bank Route -->
                @if(auth()->user()->hasPermission('read-bank'))
                    <li>
                        <a href="{{ route('admin.bank.index', Auth::user() -> branch_id) }}">
                            <i class="fa fa-money"></i>
                            <span>@lang('trans.bank')</span>
                        </a>
                    </li>
                @endif
            @endif

{{--            @if (auth()->user()->branch_id == null)--}}
{{--                @php($route = route('admin.statement.selectBranch'))--}}
{{--            @else--}}
{{--                @php($route = route('admin.statement.index'))--}}
{{--            @endif--}}
            <li class="active">
                <a href="{{ route('admin.statement.index') }}">
                    <i class="fa fa-paperclip"></i>
                    <span>اليومية</span>
                </a>
            </li>
            <!-- Multiple Links -->
            @if(!auth()->user()->hasRole(['accountant']))
                <li class="treeview">
                    <a href="#"><i class="fa fa-list"></i> <span>{{ __('trans.extra pages') }}</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">

                        <!-- Roles Manager Route -->
                        @if(auth()->user()->hasPermission('read-rolesManager'))
                            <li>
                                <a href="{{ route('admin.rolesManager.index') }}">
                                    <i class="fa fa-user-secret"></i>
                                    <span>@lang('trans.all roles')</span>
                                </a>
                            </li>
                        @endif

                        <!-- Check Status Route -->
                        @if(auth()->user()->hasPermission('read-checkStatus'))
                            <li>
                                <a href="{{ route('admin.checkStatus.index') }}">
                                    <i class="fa fa-plus"></i>
                                    <span>@lang('trans.all checkStatus')</span>
                                </a>
                            </li>
                        @endif


                    <!-- Job Title Route -->
                        @if(auth()->user()->hasPermission('read-jobTitle'))
                            <li>
                                <a href="{{ route('admin.jobTitle.index') }}">
                                    <i class="fa fa-briefcase"></i>
                                    <span>@lang('trans.all job title')</span>
                                </a>
                            </li>
                        @endif


                    <!-- Car Type Route -->
                        @if(auth()->user()->hasPermission('read-carType'))
                            <li>
                                <a href="{{ route('admin.carType.index') }}">
                                    <i class="fa fa-car"></i>
                                    <span>@lang('trans.all car type')</span>
                                </a>
                            </li>
                        @endif


                    <!-- Car Size Route -->
                        @if(auth()->user()->hasPermission('read-carSize'))
                            <li>
                                <a href="{{ route('admin.carSize.index') }}">
                                    <i class="fa fa-car"></i>
                                    <span>@lang('trans.all car size')</span>
                                </a>
                            </li>
                        @endif


                    <!-- Car Model Route -->
                        @if(auth()->user()->hasPermission('read-carModel'))
                            <li>
                                <a href="{{ route('admin.carModel.index') }}">
                                    <i class="fa fa-car"></i>
                                    <span>@lang('trans.all car model')</span>
                                </a>
                            </li>
                        @endif


                    <!-- Car Engine Route -->
                        @if(auth()->user()->hasPermission('read-carEngine'))
                            <li>
                                <a href="{{ route('admin.carEngine.index') }}">
                                    <i class="fa fa-car"></i>
                                    <span>@lang('trans.all car engine')</span>
                                </a>
                            </li>
                        @endif

                    <!-- Car Development Code Route -->
                        @if(auth()->user()->hasPermission('read-carDevelopmentCode'))
                            <li>
                                <a href="{{ route('admin.carDevelopmentCode.index') }}">
                                    <i class="fa fa-car"></i>
                                    <span>@lang('trans.all car development code')</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            <li>
                <a target="_blank" href="http://uae.skbmw-system.com/ar" style=" cursor: pointer; color: #0a0a0a; background-color: #FFF; margin: 3px; text-align: center">الذهاب الى فرع الامارات</a>
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
