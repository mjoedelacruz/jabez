 <div class="layout-header">
 	<div class="navbar navbar-inverse">
 		<div class="navbar-header">
 			<a class="navbar-brand navbar-brand-center" href="/dashboard">
 				JABEZ | <small>FNB</small>
 			</a>
 			<button class="navbar-toggler visible-xs-block collapsed" type="button" data-toggle="collapse" data-target="#sidenav">
            <span class="sr-only">Toggle navigation</span>
            <span class="bars">
              <span class="bar-line bar-line-1 out"></span>
              <span class="bar-line bar-line-2 out"></span>
              <span class="bar-line bar-line-3 out"></span>
            </span>
            <span class="bars bars-x">
              <span class="bar-line bar-line-4"></span>
              <span class="bar-line bar-line-5"></span>
            </span>
          </button>
          <button class="navbar-toggler visible-xs-block collapsed" type="button" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="arrow-up"></span>
            <span class="ellipsis ellipsis-vertical">
              <img class="ellipsis-object" width="32" height="32" src="img/user.jpg" alt="Teddy Wilson">
            </span>
          </button>
 		</div>

 		<div class="navbar-toggleable">
 			<nav id="navbar" class="navbar-collapse collapse">
 				<button class="sidenav-toggler hidden-xs" title="Collapse sidenav ( [ )" aria-expanded="true" type="button">
 					<span class="sr-only">Toggle navigation</span>
 					<span class="bars">
 						<span class="bar-line bar-line-1 out"></span>
 						<span class="bar-line bar-line-2 out"></span>
 						<span class="bar-line bar-line-3 out"></span>
 						<span class="bar-line bar-line-4 in"></span>
 						<span class="bar-line bar-line-5 in"></span>
 						<span class="bar-line bar-line-6 in"></span>
 					</span>
 				</button>


     

 				<ul class="nav navbar-nav navbar-right">

          

         
          <li class="dropdown hidden-xs">
 						<button class="navbar-account-btn" data-toggle="dropdown" aria-haspopup="true">
 							<img class="rounded" width="36" height="36" src="img/4608823220.png" alt="Teddy Wilson"><strong> </strong>
 							<span class="caret"></span>
 						</button>
 						<ul class="dropdown-menu dropdown-menu-right">

 							<li class="divider"></li>
 							<li class="navbar-upgrade-version"></li>
 							<li class="divider"></li>
 							<li>
 								@auth
 								<a class="nav-link" href="{{ route('logout') }}"
 								onclick="event.preventDefault();
 								document.getElementById('logout-form').submit();">
 								Logout
 							</a>

 							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
 								{{ csrf_field() }}
 							</form> 
 							@endauth

 						</li>
 					</ul>
 				</li>
 			</ul>
 		</nav>
 	</div>
 </div>
</div>