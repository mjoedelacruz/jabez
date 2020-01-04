<div class="layout-sidebar">
        <div class="layout-sidebar-backdrop"></div>
        <div class="layout-sidebar-body">
          <div class="custom-scrollbar">
            <nav id="sidenav" class="sidenav-collapse collapse">
                 <ul class="sidenav sidenav-collapsed" aria-expanded="false">
                <li class="sidenav-search hidden-md hidden-lg">
                  <form class="sidenav-form" action="/">
                    <div class="form-group form-group-sm">
                      <div class="input-with-icon">
                        <input class="form-control" type="text" placeholder="Search…">
                        <span class="icon icon-search input-icon"></span>
                      </div>
                    </div>
                  </form>
                </li>
                
                <li class="sidenav-heading">Navigation</li> 
         
                 <li class="sidenav-item">
                  <a href="/sales">
                    <span class="sidenav-icon icon icon-pencil-square-o"></span>
                    <span class="sidenav-label">TAKE ORDER</span>
                  </a>
                </li>

                <li class="sidenav-item">
                  <a href="{{route('sales.orderlist')}}">
                    <span class="sidenav-icon icon icon-th-list"></span>
                    <span class="sidenav-label">Order List</span>
                  </a>
                </li>

                <li class="sidenav-item">
                  <a href="{{route('menuItems.index')}}">
                    <span class="sidenav-icon icon icon-cutlery"></span>
                    <span class="sidenav-label">Menu List</span>
                  </a>
                </li>

                @if(Auth::user()->type == 1)
                <li class="sidenav-item">
                  <a href="/businesspartners">
                    <span class="sidenav-icon icon icon-users"></span>
                    <span class="sidenav-label">Business Partners</span>
                  </a>
                </li>
             

                <li class="sidenav-item">
                  <a href="/inventory">
                    <span class="sidenav-icon icon icon-cube"></span>
                    <span class="sidenav-label">Inventory</span>
                  </a>
                </li>
                <li class="sidenav-item">
                  <a href="/goodsentry">
                    <span class="sidenav-icon icon icon-cubes"></span>
                    <span class="sidenav-label">Goods Entry</span>
                  </a>
                </li>

                

                <li class="sidenav-item">
                  <a href="/users">
                    <span class="sidenav-icon icon icon-user"></span>
                    <span class="sidenav-label">Users</span>
                  </a>
                </li>

                <li class="sidenav-item">
                  <a href="/reports">
                    <span class="sidenav-icon icon icon-files-o"></span>
                    <span class="sidenav-label">Reports</span>
                  </a>
                </li>

                <li class="sidenav-item">
                  <a href="/settings">
                    <span class="sidenav-icon icon icon-gear"></span>
                    <span class="sidenav-label">Settings</span>
                  </a>
                </li>
                   @endif
                <!-- <li class="sidenav-item has-subnav">
                  <a href="#">
                    <span class="sidenav-icon icon icon-user"></span>
                    <span class="sidenav-label">Administration</span>
                  </a>
                  <ul class="sidenav-subnav collapse">
                    <li class=""><a href="/users">Users</a></li>
                    <li><a href="/reports" ">Reports</a></li>
                  </ul>
                </li> -->
              </ul>
            </nav>
          </div>
        </div>
      </div>