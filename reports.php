<?php
    // Get Token Id

    require_once('lib/tokenClass.php');

    //create object of class for validating token and saving the same.
    $tokenClass = new TokenClass();
    $allTokenData=$tokenClass->getAllTokenData();

    
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Email Tracker Reports</title>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.4.js">
	</script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" class="init">
	

$(document).ready(function() {
	$('#report').DataTable();
} );


	</script>
</head>
<body>
<table id="report" cellspacing="0" style="width:90% !important;">
        <thead>
            <tr>
                <th>Token</th>
                <th>Unique Count(5 Min)</th>
                <th>Unique Count(30 Min)</th>
                <th>Unique Count(> 30 Min)</th>
		<th>Total Count(5 Min)</th>
                <th>Total Count(30 Min)</th>
		<th>Total Count(> 30 Min)</th>
		<th>Download Report</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
               <th>Token</th>
                <th>Unique Count(5 Min)</th>
                <th>Unique Count(30 Min)</th>
                <th>Unique Count(> 30 Min)</th>
		<th>Total Count(5 Min)</th>
                <th>Total Count(30 Min)</th>
		<th>Total Count(> 30 Min)</th>
		<th>Download Report</th>
            </tr>
        </tfoot>
        <tbody>
<?php foreach($allTokenData as $k=>$v){ ?>
            <tr>
                <td><?php echo $k; ?></td>
                <td><?php echo $v['5minunique']; ?></td>
		<td><?php echo $v['30minunique']; ?></td>
		<td><?php echo $v['grt30minunique']; ?></td>
		<td><?php echo $v['5min']; ?></td>
		<td><?php echo $v['30min']; ?></td>
		<td><?php echo $v['grt30min']; ?></td>
		<td><a target="_blank" href="/tokenreport.php?token=<?php echo $k; ?>">Download</td>
            </tr>
<?php } ?>
	</tbody>
</table>
</body>
</html>
