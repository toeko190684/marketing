<?php 
	session_start();
	if($_POST['departemen_id'] <> ""){ 
		//cari group id untuk user tersebut
		$data = $crud->fetch("v_user_authority","group_id","username ='".$_SESSION['username']."'
							 and departemen_id='".$_POST['departemen_id']."'");
		
		$_SESSION['departemen_id'] = $_POST['departemen_id'];
		$_SESSION['group_id'] = $data[0]['group_id'];	
	}
?>

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<a class="navbar-brand" href="#"><img src="images/logo_company.jpg" width="120px"></a>
				</div>

				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li class="active"><a href="?r=home"><span class="glyphicon glyphicon-home"></span> Home <span class="sr-only">(current)</span></a></li>
						<?php
							$menu = $crud->fetch("menu_module","","");
							foreach($menu as $value){
						?>
								<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<span class="<?php echo $value['menu_icon']; ?>"></span>  
								<?php echo $value['menu_name']; ?> <span class="caret"></span></a>
								<ul class="dropdown-menu">
						<?php 
								$data = $crud->fetch("v_user_data","module_id,module_name,link","username='".$_SESSION['username']."' 
													 and departemen_id='".$_SESSION['departemen_id']."' 
													 and group_id='".$_SESSION['group_id']."' 
													 and menu_id='".$value['menu_id']."' and display=1");
								foreach($data as $key){
									echo "<li><a href=".$key['link']."&mod=".$key['module_id'].">".$key['module_name']."</a></li>";
								}
						?>
								</ul>
						<?php 
							}
						?>
						</li>				
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<form class="navbar-form navbar-left" role="search" method="post">				
							<div class="form-group">
								<select name="departemen_id" class="form-control">
								<option value="<?php echo $_SESSION['departemen_id']; ?>"><?php echo $_SESSION['departemen_name']; ?></option>
								<?php 
									$dep = $crud->fetch("v_user_authority","","username='".$_SESSION['username']."' and departemen_id<>'".$_SESSION['departemen_id']."'");
									foreach($dep as $value){
										echo "<option value=".$value['departemen_id'].">".$value['departemen_name']."</option>";
									}
								?>
								</select>
							</div>
							<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span></button>
					  </form>
				  </ul>				  
				  <ul class="nav navbar-nav navbar-right">
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  <span class="glyphicon glyphicon-comment"></span> Message <span class="badge"> 2 </span>
						</a>					
					</li>
					<li>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						  <span class="glyphicon glyphicon-globe"></span> Notification <span class="badge"> 10 </span>
						</a>					
					</li>
					<li class="dropdown">
					  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
					  <span class="glyphicon glyphicon-user"></span> 
					  <?php echo ucfirst($_SESSION['username']); ?> 
					  <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<li><a href="#"><span class="glyphicon glyphicon-comment"></span> Comment <span class="badge"> 42 </span></a></li>
						<li><a href="#"><span class="glyphicon glyphicon-envelope"></span> Message</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-education"></span> Profile</a></li>
						<li role="separator" class="divider"></li>
						<li  onclick="return confirm('Logout from SKProject System ?');"><a href="logout.php"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
					  </ul>
					</li>
				  </ul>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>
	</div>
</div>