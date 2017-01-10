<?php
ob_start();
session_start();
$accountID=@$_SESSION['accountID'];
$page=@$_GET['page'];
$tab=@$_GET['tab'];
$id=@$_GET['id'];
unset($_SESSION['selectcredit']);
if(!isset($tab)){
	$tab=1;
}
$keyword=@$_GET['keyword'];
$search=@$_GET['search'];
			$by=@$_GET['by'];
			$order=@$_GET['order'];

#$connect = mysql_connect("localhost","qfcdavao_admin","_39a11nwpm");
#mysql_select_db("qfcdavao_dbinventory");

include 'db.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $app_name; ?> - Credits</title>
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
  .popover{
    width:100%;   
}
  </style>
  <script>
		$(document).ready(function(){
	$( "#search" ).autocomplete({
      source: 'search-item-all',
	  select: function(event, ui){
		  window.location='item?s='+ui.item.data;
	  }
    });
	$( "#customer" ).autocomplete({
      source: 'search-customer-auto',
	  select: function(event, ui){
		  window.location='credits?tab=2&id='+ui.item.data;
	  }
    });
	
	$("#date_now").datepicker();
	$("#date_now").change(function(){
		var date_now = $(this).val();
		window.location = 'credits?d='+date_now;
	});
	
		 $('.selected').click(function(event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });
	
  });
  </script>
    <style>
		#item_results
	{
		position:absolute;
		width:250px;
		z-index:5;
		max-height:200px;
		padding:10px;
		display:none;
		margin-top:-1px;
		border-top:0px;
		overflow:auto;
		border:1px #CCC solid;
		background-color: white;
	}
	.show,.cusclose
	{
		padding:10px; 
		border-bottom:1px #999 dashed;
		font-size:15px; 
	}
	.cusclose:hover,.show:hover
	{
		background:#4c66a4;
		color:#FFF;
		cursor:pointer;
	}
	
	
	
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
			if($module==1){
				$badge_arg = $badge_i;
			}elseif($module==3){
				$badge_arg = $badge;
			}elseif($module==8){
				$badge_arg = $badge_credit;
			}else{
				$badge_arg = 0;
			}
			echo $header->header($module,$badge_arg,1);
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
		  	header("location:index");
			
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
	<form action='credits?tab=2' method='post'>
  	<div class='col-md-12'>	
	<?php
	if($logged==1||$logged==2){
		if($credits=='1'){
			if(isset($id)){
				$credits_query = mysql_query("SELECT * FROM tbl_soa WHERE soaID='$id'");
				if(mysql_num_rows($credits_query)!=0){
					while($credits_row=mysql_fetch_assoc($credits_query)){
						$customerID=$credits_row["customerID"];
						$date=$credits_row["date"];
						$time=$credits_row["time"];
						$paymentID_array=explode(",",$credits_row["orderID"]);
					}
					// var_dump($paymentID_array);
					$customer_query = mysql_query("SELECT * FROM tbl_customer WHERE customerID='$customerID'");
					while($customer_row=mysql_fetch_assoc($customer_query)){
						$companyname = $customer_row["companyname"];
					}
					
					echo "
					<center>
					<img src='$logo' align='middle' style='margin-bottom:0px' class='img-responsive' alt='LOGO'></img>
					$address<br>
					$contact_number<br>
					</center>
					<br>
					<table style='width:100%'>
					<tr>
						<td style='width:70%'></td>
						<td style='width:30%'>STATEMENT #: ".sprintf("%06d",$id)."</td>
					</tr>
					<tr>
						<td>To: <b>$companyname</b></td>
						<td>Date: <b>".date("F d, Y",strtotime($date))."</b></td>
					</tr>
					</table>
					<h4 style='text-align:center'>STATEMENT OF ACCOUNT
					</h4>
					<table border='1' style='width:100%'>
					<tbody>
					<tr>
						<th style='width:10%;text-align:center'>DATE</th>
						<th style='width:10%;text-align:center'>REF NUMBER</th>
						<th style='width:10%;text-align:center'>DUE DATE</th>
						<th style='width:50%;text-align:center'>PARTICULARS</th>
						<th style='width:20%;text-align:center'>AMOUNT</th>
					</tr>";
					$total_amount = 0;
					foreach($paymentID_array as $paymentID){
						$payment_query = mysql_query("SELECT * FROM tbl_payments WHERE orderID='$paymentID'");
						while($payment_row=mysql_fetch_assoc($payment_query)){
							$amount = $payment_row["payment"];
							$orderID = $payment_row["orderID"];
							$date_due = $payment_row["date_due"];
							$invoice = $payment_row["comments"];
							$order_query = mysql_query("SELECT * FROM tbl_orders WHERE orderID='$orderID'");
							while($order_row=mysql_fetch_assoc($order_query)){
								$date_ordered=$order_row["date_ordered"];
							}
						}
						echo "
						<tr>
							<td style='text-align:center;padding-left:5px;padding-right:5px'>$date_ordered</td>
							<td style='text-align:center;padding-left:5px;padding-right:5px'>S".sprintf("%06d",$orderID)."<br>$invoice</td>
							<td style='text-align:center;padding-left:5px;padding-right:5px'>".date("m/d/Y",$date_due)."</td>
							<td style='text-align:right'></td>
							<td style='text-align:right;padding-left:5px;padding-right:5px'>₱".number_format($amount,2)."</td>
						</tr>
						";
						$total_amount+=$amount;
					}
					mysql_query("UPDATE tbl_soa SET total = '$total_amount' WHERE soaID='$id'");
					$value = $total_amount;
					$value_float = (float)$value;
					$value_int = (int)$value;
					$string = sprintf("%0.2f",$value_float-$value_int);
					$explode_str = explode(".",$string);
					$final_words=convert_number_to_words($value_int);
					$decimal= "$explode_str[1]/100";
					
					if((int)$decimal!=0){
						$final_words.=" and $decimal";
					}					echo "
					</tbody>
					<tfoot>
					<tr>
						<th colspan='4' style='text-align:right'>TOTAL:&nbsp;</th>
						<th style='text-align:right;padding-left:5px;padding-right:5px''>₱".number_format($total_amount,2)."</th>
					</tr>
					</tfoot>

					</table>
					<br>
					";
					echo "<b><span style='position:relative;left:12pt;border-bottom-style:solid;border-bottom-width:1px;'>&nbsp;&nbsp;".ucwords($final_words)." Pesos Only.&nbsp;&nbsp;</span></b>";
					echo "
					<br>
					<br>
					<p style='    text-align: justify;'>Please name your bank check to <b><span style='border-bottom-style:solid;border-bottom-width:1px;'>&nbsp;$app_company_name&nbsp;</b></span> or visit www.qfcdavao.com/check.
					Thank your for your continued patronage. We truly wish to continually provide you with excellent service.
					<br>
					<br>
					For suggestions and concern, please send your email to qfcdavao_dealer1@yahoo.com</p>
					";
				}
			}else{
				
			}
			
		}else{
			echo "<strong><center>You do not have the authority to access this module.</center></strong>";
		}
	} ?>
	</div>
	</form>
  </div>
</div>
</body>
</html>
<?php mysql_close($connect);

function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' and ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}

	unset($_SESSION['selectcredit']);
?>
  <script>
$("[data-toggle=popover]")
.popover({html:true})
</script>