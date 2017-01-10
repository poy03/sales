<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$cat=@$_GET['cat'];
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];



include 'db.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Expenses</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  if(isset($themes)&&$themes!=''){
	  echo "<link rel='stylesheet' href='css/$themes'>";
  }else{
	  echo "<link rel='stylesheet' href='css/bootstrap.min.css'>";
  }
  
  ?>
  
  <link rel="stylesheet" href="style.css">
  <script src="jquery.min.js"></script>
  <script src="main.js"></script>
  <script src="js/bootstrap.min.js"></script>
  
  <link rel="stylesheet" href="themes/smoothness/jquery-ui.css">
   
  <script src="jquery-ui.js"></script><script type="text/javascript" src="js/shortcut.js"></script>

  <style>
  .item:hover{
	  cursor:pointer;
  }
  
  </style>
  <script>
	$(document).ready(function(){
		$(".delete").click(function(){
			if(confirm("Are you sure you want to delete selected item?")){
				window.location = $(this).attr("my");
			}
		});
		
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });	

	$( "#search_for_expenses" ).autocomplete({
      source: 'search-for-expenses'
    });

    $("#expenses-form").submit(function(e){
    	e.preventDefault();
    	$("#expenses-form-submit").attr("disabled","disabled");
    	$.ajax({
    		type: "POST",
    		url: $("#expenses-form").attr("action"),
    		data: $("#expenses-form :input").serialize()+"&save=1",
    		cache: false,
    		success: function(data){
    			$("#expenses-modal").modal("show");
    			$("#expenses-form")[0].reset();
    		}
    	});
    });
  
  	$("#cancel").click(function(){
  		$.ajax({
  			type: "POST",
  			url: $("#expenses-form").attr("action"),
  			data: "delete=1",
  			cache: false,
  			success: function(data){
  				location.reload();
  			}
  		});
  	});
  });
  </script>
    <style>

	.page{
		display:none;
	}
	
@media print{
	.page{
		display:inline !important;
	}
  .prints{
	  display:none;
	  }
	  .content{
		  border-color:white;
	  }
	  
    a[href]:after {
    content: none !important;
  }
}
  </style>
</head>
<body>
	   <nav class = "navbar navbar-default" role = "navigation" id='heading'>
	   <div class = "navbar-header">
		  <button type = "button" class = "navbar-toggle" 
			 data-toggle = "collapse" data-target = "#example-navbar-collapse">
			 <span class = "sr-only">Toggle navigation</span>
			 <span class = "icon-bar"></span>
			 <span class = "icon-bar"></span>
			 <span class = "icon-bar"></span>
		  </button>
		  <a class = "navbar-brand" href = "index"><?php echo $app_name; ?></a>
	   </div>
	   
	   <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
		  <ul class = "nav navbar-nav  navbar-left">
		  <?php
		  $header = new Template;
		  foreach ($list_modules as $module) {
			echo $header->header($module,0,1);
		  }
		  ?>

		 <?php if($logged!=0){ ?>
		 <div class="form-group navbar-form navbar-right">
			<input type="text" class="form-control" placeholder="Search" name='search' id='search' autocomplete='off'><div id='item_results'></div>
		 </div>
		 <?php } ?>
						
		  </ul>

		  
		  
		  <?php 
		  if($logged==0){
			
		  ?>
		  	<ul class='nav navbar-nav navbar-right'>
				<li><a href='login'><span class='glyphicon glyphicon-log-in'></span> Login</a></li>
			</ul>
		  <?php }else{ ?>
		  	<ul class='nav navbar-nav navbar-right'>
				
				
				<li>
					<a href='#' role='button' 
					  data-container = 'body' data-toggle = 'popover' data-placement = 'bottom' 
					  data-content = "
						<a href='settings' class = 'list-group-item'><span class='glyphicon glyphicon-cog'></span> Settings</a>
						<a href = 'maintenance' class = 'list-group-item'><span class='glyphicon glyphicon-hdd'></span> Maintenance</a><a href = 'logout' class = 'list-group-item'><span class='glyphicon glyphicon-log-out'></span> Logout</a><a href = '#' class = 'list-group-item shutdown'><span class='glyphicon glyphicon-off'></span> Shutdown</a>
										  					  
					  ">
					Hi <?php echo $employee_name; ?></a></a>
				</li>				
				
			</ul>
		  <?php }?>

		  
		  
		  </div>

	   </nav>	
<div class="container-fluid">
  <div class='row'>
  	<div class='col-md-12 prints'>
	
	
	<?php
	if($logged==1||$logged==2){
		
		if($expenses==1){
			?>
		<div class="col-md-2">
			<button class="btn btn-primary btn-block" type="submit" form="expenses-form"><span class="glyphicon glyphicon-floppy-disk"></span> Save</button>
		</div>
		<div class="col-md-10">
		<form action="expenses-form" method="post" id="expenses-form">
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>Expenses</th>
							<th>Amount</th>
							<th>Comments</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$i = 0;
							while ($i <= 10) {
								echo '
								<tr>
									<td><input type="text" class="form-control" name="description[]" placeholder="Expenses Description"></td>
									<td><input type="number" class="form-control" min="0" step="0.01" name="amount[]" placeholder="Expenses Amount"></td>
									<td><input type="text" class="form-control" name="comments[]" placeholder="Expenses Comments"></td>
								</tr>
								';

								$i++;
							}

						?>
					</tbody>
				</table>
			</div>
		</form>
		</div>

		<div class = "modal fade" id = "expenses-modal" tabindex = "-1" role = "dialog" 
		   aria-labelledby = "myModalLabel" aria-hidden = "true">
		   
		   <div class = "modal-dialog">
		      <div class = "modal-content">
		         <form action='#' method='get'>
		         <div class = "modal-header">
		            <button type = "button" class = "close" data-dismiss = "modal" aria-hidden = "true">
		                  &times;
		            </button>
		            
		            <h4 class = "modal-title" id = "myModalLabel">
		               Success!
		            </h4>
		         </div>
		         
		         <div class = "modal-body">
		         	<p>
		         		The Expenses are successfuly saved.
		         	</p>					
		         </div>
		         
		         <div class = "modal-footer">
		            <button type = "button" class = "btn btn-default" data-dismiss = "modal">
		               Close
		            </button>
		         </div>
		         </form>
		      </div><!-- /.modal-content -->
		   </div><!-- /.modal-dialog -->
		  
		</div><!-- /.modal -->


			<?php
		}
	}else{
     header("location:index");
	} ?>
	</div>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>