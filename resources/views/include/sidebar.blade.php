<!-- Start Sidebar Area -->
<div class="sidebar-area d-flex flex-column" id="sidebar-area">
    <div class="logo position-relative d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" class="d-block text-decoration-none position-relative">
            <img src="{{ asset(setting('logo') ?? 'assets/images/logo-icon.png') }} " width="100" height="50"
                alt="logo-icon">
        </a>

        <!-- Sidebar Toggle -->
        <button class="sidebar-burger-menu bg-transparent p-0 border-0" id="sidebar-burger-menu">
            <span class="d-block" style="border-bottom:1px solid #475569; height:1px; width:25px;"></span>
            <span class="d-block" style="border-bottom:1px solid #475569; height:1px; width:25px; margin:6px 0;"></span>
            <span class="d-block" style="border-bottom:1px solid #475569; height:1px; width:25px;"></span>
        </button>
    </div>

    <!-- Sidebar Menu -->
    <aside id="layout-menu" class="layout-menu menu-vertical menu active flex-grow-1" data-simplebar>

        <ul class="menu-inner">

            <!-- Dashboard -->
            <li class="menu-item {{ Route::is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">dashboard</span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <!-- User Management -->
            {{-- <li
                    class="menu-item {{  Route::is('users.*')  || Route::is('roles.*')  ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">groups</span>
                <span class="title">User Management</span>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="menu-link">
                        <span class="title">Users</span>
                    </a>
                </li>


            </ul>
            </li> --}}

            <!-- Employee Management -->
            {{-- @if (hasPermission('view-employee-detail') || hasPermission('view-employee-holiday') || hasPermission('view-employee-attendence') || hasPermission('view-employee-leave') || hasPermission('view-employee-salary') || hasPermission('view-employee-expense')) --}}
            @if(!auth()->user()->hasRole('HEAD PRODUCTION'))
            <li
                class="menu-item {{ Route::is('employee.*') ||
                Route::is('employee-assets.*') ||
                Route::is('employee-holiday.*') ||
                Route::is('employee-attandance.*') ||
                Route::is('employee-expense.*') ||
                Route::is('salary-generate.*') ||
                Route::is('leaves.*') ||
                Route::is('loan-advances.*') ||
                Route::is('last-adjustments.*') ||
                Route::is('asset.management.*') ||
                Route::is('tds.*')||
                Route::is('overtime.*')
              
                    ? 'active open'
                    : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">groups</span>
                    <span class="title">HRMS</span>
                </a>

                <ul class="menu-sub">
                    {{-- @if (hasPermission('view-employee-detail')) --}}
                    <li class="menu-item {{ Route::is('employee.*') ? 'active' : '' }}">
                        <a href="{{ route('employee.index') }}" class="menu-link"><span
                                class="title">Employee</span></a>
                    </li>
                    {{-- @endif --}}

                    {{-- @if (hasPermission('view-employee-holiday')) --}}
                    <li class="menu-item {{ Route::is('employee-holiday.*') ? 'active' : '' }}">
                        <a href="{{ route('employee-holiday.index') }}" class="menu-link"><span class="title">Employee
                                Holiday</span></a>
                    </li>
                    {{-- @endif --}}

                    {{-- @if (hasPermission('view-employee-attendence')) --}}
                    <li class="menu-item {{ Route::is('employee-attandance.*') ? 'active' : '' }}">
                        <a href="{{ route('employee-attandance.index') }}" class="menu-link"><span
                                class="title">Employee Attendance</span></a>
                    </li>
                    {{-- @endif --}}

                    {{-- @if (hasPermission('view-employee-leave')) --}}
                    <li class="menu-item {{ Route::is('leaves.*') ? 'active' : '' }}">
                        <a href="{{ route('leaves.index') }}" class="menu-link"><span class="title">Employee
                                Leave</span></a>
                    </li>
                    {{-- @endif --}}

                    {{-- @if (hasPermission('view-employee-salary')) --}}
                    <li class="menu-item {{ Route::is('salary-generate.*') ? 'active' : '' }}">
                        <a href="{{ route('salary-generate.index') }}" class="menu-link"><span class="title">Employee
                                Salary</span></a>
                    </li>
                    {{-- @endif --}}

                    <li class="menu-item {{ Route::is('employee-expense.*') ? 'active' : '' }}">
                        @php
                        $user = auth()->user();
                        $route = $user->full_name == 'Admin' ? 'employee-expense.index' : 'employee-expense.create';
                        @endphp

                        <a href="{{ route($route) }}" class="menu-link">
                            <span class="title">Expenses</span>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ Route::is('loan-advances.*') || Route::is('loan.showLoanMonthAdjustmentlist') || Route::is('loan.showLoanMonthAdjustmentCreate') || Route::is('loan.showLoanMonthAdjustmentEditForm') ? 'active' : '' }}">
                        <a href="{{ route('loan-advances.index') }}" class="menu-link"><span class="title">Advance
                                Salary</span></a>
                    </li>
                    <li class="menu-item {{ Route::is('last-adjustments.*') ? 'active' : '' }}">
                        <a href="{{ route('last-adjustments.index') }}" class="menu-link"><span class="title">Last
                                Month Adjustment</span></a>
                    </li>

                    <li class="menu-item {{ Route::is('tds.*') ? 'active' : '' }}">
                        <a href="{{ route('tds.index') }}" class="menu-link"><span class="title">Tds Management
                            </span></a>
                    </li>
                    <li class="menu-item {{ Route::is('overtime.*') ? 'active' : '' }}">
                        <a href="{{ route('overtime.index') }}" class="menu-link"><span class="title">Overtime Management
                            </span></a>
                    </li>

                </ul>
            </li>
            @endif
            {{-- @endif --}}
            @if(!auth()->user()->hasRole('HEAD PRODUCTION'))
            <li
                class="menu-item {{ Route::is('sale-orders.*') ||
                Route::is('invoice-orders.*') ||
                Route::is('credit-notes.*') ||
                Route::is('customers.*') ||
                Route::is('customer-product-discount.create') ||
                Route::is('customer.contactcreate.create') ||
                Route::is('customer.contactlist.index') ||
                Route::is('customer.contactedit.edit')
                    ? 'active open'
                    : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">trending_up</span>
                    <span class="title">Sales</span>

                </a>

                <ul class="menu-sub">
                    <li
                        class="menu-item {{ Route::is('customers.*') ||
                        Route::is('customer-product-discount.create') ||
                        Route::is('customer.contactcreate.create') ||
                        Route::is('customer.contactlist.index') ||
                        Route::is('customer.contactedit.edit')
                            ? 'active'
                            : '' }}">
                        <a href="{{ route('customers.index') }}" class="menu-link">
                            <span class="title">Customers</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::is('sale-orders.*') ? 'active' : '' }}">
                        <a href="{{ route('sale-orders.index') }}" class="menu-link">
                            <span class="title">Sales Orders</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::is('invoice-orders.*') ? 'active' : '' }}">
                        <a href="{{ route('invoice-orders.index') }}" class="menu-link">
                            <span class="title">Invoice Orders</span>
                        </a>
                    </li>

                    <li class="menu-item {{ Route::is('credit-notes.*') ? 'active' : '' }}">
                        <a href="{{ route('credit-notes.index') }}" class="menu-link">
                            <span class="title">Credit Notes</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- @if (hasPermission('view-vendor') || hasPermission('view-purchase-order') || hasPermission('view-brokers')) --}}

            <li
                class="menu-item {{ Route::is('vendor.*') || Route::is('purchase-order.*') || Route::is('brokers.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">shopping_cart</span>
                    <span class="title">Purchase</span>

                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ Route::is('vendor.*') ? 'active' : '' }}">
                        <a href="{{ route('vendor.index') }}" class="menu-link">
                            <span class="title">Vendors</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::is('brokers.*') ? 'active' : '' }}">
                        <a href="{{ route('brokers.index') }}" class="menu-link">
                            <span class="title">Brokers</span>
                        </a>
                    </li>
                    <li class="menu-item {{ Route::is('purchase-order.*') ? 'active' : '' }}">
                        <a href="{{ route('purchase-order.index') }}" class="menu-link">
                            <span class="title">Purchase Orders</span>
                        </a>
                    </li>
                </ul>
            </li>

            @if(auth()->user()->hasRole('HEAD QA') || (auth()->user()->hasRole('admin')))
            <li class="menu-item {{ Route::is('purchase-testing.*') || Route::is('production-testing.*')  ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">fact_check</span>
                    <span class="title">Quality Check</span>
                </a>


                <ul class="menu-sub">

                    <li class="menu-item {{ Route::is('purchase-testing.*') ? 'active' : '' }}">
                        <a href="{{ route('purchase-testing.index') }}" class="menu-link">
                            <span class="title">Purchase Testing</span>
                        </a>
                    </li>

                    <li class="menu-item {{ Route::is('production-testing.*') ? 'active' : '' }}">
                        <a href="{{ route('production-testing.index') }}" class="menu-link">
                            <span class="title">Production Testing</span>
                        </a>
                    </li>

                </ul>


            </li>
            @endif

            {{-- @endif --}}

            {{-- @if (hasPermission('view-bom-master') || hasPermission('view-production-batch') || hasPermission('view-store-issurance') || hasPermission('view-production-voucher') || hasPermission('view-production-process') || hasPermission('view-production-start')) --}}

            <li
                class="menu-item {{ Route::is('bom-master.*') ||
                Route::is('production-batch.*') ||
                Route::is('store-issurance.*') ||
                Route::is('production-voucher.*') ||
                Route::is('production-process.create') ||
                Route::is('production-start.*') ||
                Route::is('createAssignTeam')
                    ? 'active open'
                    : '' }} ">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">construction</span>
                    <span class="title">Production</span>
                </a>

                <ul class="menu-sub">

                    <li
                        class="menu-item {{ Route::is('bom-master.*') || Route::is('production-process.create') ? 'active' : '' }}">
                        <a href="{{ route('bom-master.index') }}" class="menu-link">
                            <span class="title">BOM Master</span>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ Route::is('production-batch.*') || (Route::is('createAssignTeam') && request('module') == 'Requisition') ? 'active' : '' }}">
                        <a href="{{ route('production-batch.index') }}" class="menu-link">
                            <span class="title">Requisition</span>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ Route::is('store-issurance.*') || (Route::is('createAssignTeam') && request('module') == 'Store') ? 'active' : '' }}">
                        <a href="{{ route('store-issurance.index') }}" class="menu-link">
                            <span class="title">Store Issuance</span>
                        </a>
                    </li>

                    <li
                        class="menu-item {{ Route::is('production-voucher.*') || (Route::is('createAssignTeam') && request('module') == 'Voucher') ? 'active' : '' }}">
                        <a href="{{ route('production-voucher.index') }}" class="menu-link">
                            <span class="title">Production Voucher</span>
                        </a>
                    </li>

                    <li class="menu-item {{ Route::is('production-start.*') ? 'active' : '' }}">
                        <a href="{{ route('production-start.index') }}" class="menu-link">
                            <span class="title">Production Flow</span>
                        </a>
                    </li>

                </ul>

            </li>   


            {{-- @endif --}}
            {{-- @if (hasPermission('view-product-category') || hasPermission('view-finished-goods') || hasPermission('view-batch-management')) --}}
            <!-- Finished Goods -->

            <li
                class="menu-item {{ Route::is('finished-good.*') ||
                Route::is('product-details.*') ||
                Route::is('batch-details.*') ||
                Route::is('batch-management.*') ||
                Route::is('category.*') ||
                ROute::is('rawcategory.*') ||
                ROute::is('rawmaterial.*') ||
                Route::is('raw-material-batch.*') ||
                Route::is('stock-ledger.index')
                    ? 'active open'
                    : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">inventory_2</span>
                    <span class="title">Stocks</span>
                </a>
                <ul class="menu-sub">
                    {{-- @if (hasPermission('view-product-category')) --}}
                    <li class="menu-item {{ Route::is('category.*') ? 'active' : '' }}">
                        <a href="{{ route('category.index') }}" class="menu-link">
                            <span class="title">Category</span>
                        </a>
                    </li>
                    {{-- @endif
                        @if (hasPermission('view-finished-goods')) --}}
                    <li
                        class="menu-item {{ Route::is('finished-good.*') || Route::is('product-details.*') || Route::is('batch-details.*') ? 'active' : '' }}">
                        <a href="{{ route('finished-good.index') }}" class="menu-link">
                            <span class="title">Finished Goods</span>
                        </a>
                    </li>
                    {{-- @endif --}}
                    {{-- @if (hasPermission('view-batch-management')) --}}
                    {{-- <li class="menu-item {{ Route::is('batch-management.*') ? 'active' : '' }}">
                    <a href="{{ route('batch-management.index') }}" class="menu-link">
                        <span class="title">Batch Management</span>
                    </a>
            </li> --}}
            <li class="menu-item {{ Route::is('rawcategory.*') ? 'active' : '' }}">
                <a href="{{ route('rawcategory.index') }}" class="menu-link">
                    <span class="title">Raw Category</span>
                </a>
            </li>
            <li class="menu-item {{ Route::is('rawmaterial.*') ? 'active' : '' }}">
                <a href="{{ route('rawmaterial.index') }}" class="menu-link">
                    <span class="title">Raw Material</span>
                </a>
            </li>


            {{-- @endif --}}
            {{-- <li
                        class="menu-item {{ Route::is('raw-material-batch.*') || Route::is('stock-ledger.index') ? 'active' : '' }}">
            <a href="{{ route('raw-material-batch.index') }}" class="menu-link">
                <span class="title">Raw Material Batch</span>
            </a>
            </li> --}}
        </ul>
        </li>

        {{-- @endif --}}

        {{-- @if (hasPermission('view-system-log')) --}}

        {{-- <!-- Master Menu -->
            @if (hasPermission('view-bank') || hasPermission('view-gst') || hasPermission('view-uom') || hasPermission('view-salary-component')) --}}
        @if(!auth()->user()->hasRole('HEAD PRODUCTION')) <li
            class="menu-item {{ Route::is('bank-details.*') ||
                Route::is('gst-rates.*') ||
                Route::is('uom.*') ||
                Route::is('prefixes.*') ||
                Route::is('payment-terms.*') ||
                Route::is('salary-component.*') ||
                Route::is('currencies.*') ||
                Route::is('bom-types.*') ||
                Route::is('product-types.*') ||
                Route::is('packging-types.*')
                    ? 'active open'
                    : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">settings</span>
                <span class="title">Master</span>
            </a>
            <ul class="menu-sub">

                {{-- @if (hasPermission('view-bank')) --}}
                <li class="menu-item {{ Route::is('bank-details.*') ? 'active' : '' }}">
                    <a href="{{ route('bank-details.index') }}" class="menu-link">
                        <span class="title">Bank</span>
                    </a>
                </li>
                {{-- @endif --}}
                {{-- @if (hasPermission('view-gst')) --}}
                <li class="menu-item {{ Route::is('gst-rates.*') ? 'active' : '' }}">
                    <a href="{{ route('gst-rates.index') }}" class="menu-link">
                        <span class="title">GST Rates</span>
                    </a>
                </li>
                {{-- @endif --}}
                {{-- @if (hasPermission('view-uom')) --}}
                <li class="menu-item {{ Route::is('uom.*') ? 'active' : '' }}">
                    <a href="{{ route('uom.index') }}" class="menu-link">
                        <span class="title">Unit of Measure</span>
                    </a>
                </li>
                {{-- @endif --}}
                {{-- @if (hasPermission('view-salary-component')) --}}
                <li class="menu-item {{ Route::is('salary-component.*') ? 'active' : '' }}">
                    <a href="{{ route('salary-component.index') }}" class="menu-link">
                        <span class="title">Salary Component</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('prefixes.*') ? 'active' : '' }}">
                    <a href="{{ route('prefixes.index') }}" class="menu-link">
                        <span class="title">Prefixes</span>
                    </a>

                </li>
                <li class="menu-item {{ Route::is('payment-terms.*') ? 'active' : '' }}">
                    <a href="{{ route('payment-terms.index') }}" class="menu-link">
                        <span class="title">Payment Terms</span>
                    </a>

                </li>
                <li class="menu-item {{ Route::is('bom-types.*') ? 'active' : '' }}">
                    <a href="{{ route('bom-types.index') }}" class="menu-link">
                        <span class="title">Bom Type</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('product-types.*') ? 'active' : '' }}">
                    <a href="{{ route('product-types.index') }}" class="menu-link">
                        <span class="title">Product Type</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('packging-types.*') ? 'active' : '' }}">
                    <a href="{{ route('packging-types.index') }}" class="menu-link">
                        <span class="title">Pack Config</span>
                    </a>
                </li>

                {{-- @endif --}}
                <li class="menu-item {{ Route::is('currencies.*') ? 'active' : '' }}">
                    <a href="{{ route('currencies.index') }}" class="menu-link">
                        <span class="title">Currency</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        {{-- @endif --}}

        {{-- @endif --}}
        <!-- Organization -->
        {{-- @if (hasPermission('view-branch') || hasPermission('view-department') || hasPermission('view-warehouse') || hasPermission('view-designation')) --}}
        @if(!auth()->user()->hasRole('HEAD PRODUCTION')) <li
            class="menu-item {{ Route::is('branches.*') ||
                Route::is('departments.*') ||
                Route::is('warehouse.*') ||
                Route::is('designation.*')
                    ? 'active open'
                    : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">business</span>
                <span class="title">Organization</span>
            </a>
            <ul class="menu-sub">
                {{-- @if (hasPermission('view-branch')) --}}
                <li class="menu-item {{ Route::is('branches.*') ? 'active' : '' }}">
                    <a href="{{ route('branches.index') }}" class="menu-link">
                        <span class="title">Branch</span>
                    </a>
                </li>
                {{-- @endif
                        @if (hasPermission('view-department')) --}}
                <li class="menu-item {{ Route::is('departments.*') ? 'active' : '' }}">
                    <a href="{{ route('departments.index') }}" class="menu-link">
                        <span class="title">Department</span>
                    </a>
                </li>
                {{-- @endif
                        @if (hasPermission('view-designation')) --}}
                <li class="menu-item {{ Route::is('designation.*') ? 'active' : '' }}">
                    <a href="{{ route('designation.index') }}" class="menu-link">
                        <span class="title">Designation</span>
                    </a>
                </li>
                {{-- @endif
                        @if (hasPermission('view-warehouse')) --}}
                <li class="menu-item {{ Route::is('warehouse.*') ? 'active' : '' }}">
                    <a href="{{ route('warehouse.index') }}" class="menu-link">
                        <span class="title">Warehouse</span>
                    </a>
                </li>
                {{-- @endif --}}
            </ul>
        </li>
        {{-- @endif --}}
        @endif
        <!-- Settings -->
        @if(!auth()->user()->hasRole('HEAD PRODUCTION'))
        <li
            class="menu-item {{ Route::is('settings.*') || Route::is('roles.*') || Route::is('permission.*') ? 'open active' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">settings</span>
                <span class="title">Settings</span>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ Route::is('roles.*') || Route::is('permission.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="menu-link">
                        <span class="title">Role</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('settings.create') ? 'active' : '' }}">
                    <a href="{{ route('settings.create') }}" class="menu-link">
                        <span class="title">Company Setting</span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- @if (auth()->user()->hasRole('HEAD QA'))
        <li
            class="menu-item {{ Route::is('production-batch.*') ||
                    Route::is('store-issurance.*') ||
                    Route::is('production-voucher.*') ||
                    Route::is('production-process.create') ||
                    Route::is('production-start.*') ||
                    Route::is('createAssignTeam') ||
                    Route::is('purchase-order')
                        ? 'active open'
                        : '' }}">

            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <span class="material-symbols-outlined menu-icon">verified</span>
                <span class="title">QA Approval</span>
            </a>

            <ul class="menu-sub">



                <li class="menu-item {{ Route::is('production-batch.*') ? 'active' : '' }}">
                    <a href="{{ route('production-batch.index') }}" class="menu-link">
                        <span class="title">Requisition</span>
                    </a>
                </li>

                <li class="menu-item {{ Route::is('store-issurance.*') ? 'active' : '' }}">
                    <a href="{{ route('store-issurance.index') }}" class="menu-link">
                        <span class="title">Store Issuance</span>
                    </a>
                </li>

                <li class="menu-item {{ Route::is('production-voucher.*') ? 'active' : '' }}">
                    <a href="{{ route('production-voucher.index') }}" class="menu-link">
                        <span class="title">Production Voucher</span>
                    </a>
                </li>

                <li class="menu-item {{ Route::is('production-start.*') ? 'active' : '' }}">
                    <a href="{{ route('production-start.index') }}" class="menu-link">
                        <span class="title">Production Flow</span>
                    </a>
                </li>
                <li class="menu-item {{ Route::is('purchase-order.*') ? 'active' : '' }}">
                    <a href="{{ route('purchase-order.index') }}" class="menu-link">
                        <span class="title">Purchase Orders</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif -->
        @endif
        @if(!auth()->user()->hasRole('HEAD PRODUCTION'))
        <li class="menu-item {{ Route::is('system-logs.*') ? 'active' : '' }}">
            <a href="{{ route('system-logs.index') }}" class="menu-link">
                <span class="material-symbols-outlined menu-icon">history</span>
                <span class="title">System Logs</span>
            </a>
        </li>
        @endif


        <!-- Logout -->
        {{-- <li class="menu-item">
                <a href="{{ route('logout') }}" class="menu-link">
        <span class="material-symbols-outlined menu-icon">logout</span>
        <span class="title">Logout</span>
        </a>
        </li> --}}

        </ul>
    </aside>
</div>

<style>
    /* Main active menu item (Parent) */
    .menu-item.active>.menu-link {
        background: #0d6efd !important;
        color: #fff !important;
        border-radius: 8px;
        padding: 12px;
    }

    /* Main menu icon color when active */
    .menu-item.active .menu-icon {
        color: #fff !important;
    }

    /* Submenu (child items) default style */
    .menu-sub .menu-link {
        padding: 12px 15px 12px 40px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    /* Submenu hover */
    .menu-sub .menu-link:hover {
        background: #e0ecff;
        /* light blue hover */
        color: #0d6efd;
    }

    /* Submenu active (when clicked) */
    .menu-sub .menu-item.active>.menu-link {
        background: #e0ecff !important;
        /* lighter blue */
        color: #0d6efd !important;
        font-weight: 400;
    }

    /* Submenu active icon color */
    .menu-sub .menu-item.active .menu-icon {
        color: #0d6efd !important;
    }

    /* Base menu links */
    .menu-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 17px 15px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .menu-link:hover {
        background: #f1f5f9;
        color: #0d6efd;
    }

    .menu-icon {
        font-size: 20px;
        color: #475569;
    }

    /* Sidebar full height */
    .sidebar-area {
        height: 100vh;
        overflow: hidden;
    }

    /* Sidebar menu scroll */
    #layout-menu {
        overflow-y: auto;
        height: calc(100vh - 80px);
        /* logo height adjust */
    }

    /* Smooth scrollbar */
    #layout-menu::-webkit-scrollbar {
        width: 6px;
    }

    #layout-menu::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #layout-menu::-webkit-scrollbar-track {
        background: transparent;
    }
</style>