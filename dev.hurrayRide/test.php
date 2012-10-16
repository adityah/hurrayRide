<?php
	$sampleData = 'la la la';
	if (isset($_POST['data']) and trim($_POST['data']) != '' ) {
		$sampleData = trim($_POST['data']);
	}
	$rowCount = rand(1, 10);
?>
<p>
	Total data rows : <?php echo $rowCount; ?><br />
	Sample data : <?php echo $sampleData; ?><br />
	Request time :
	<?php echo date('F d, Y h:i:s a', $_SERVER['REQUEST_TIME']); ?>
</p>
<table>
	<thead>
	<tr>
		<td class="td-col1">#</td>
		<td class="td-col2">Column 1</td>
		<td class="td-col3">Column 2</td>
		<td class="td-col4">Column 3</td>
	</tr>
	</thead>
	<tbody>
	<?php for($rowCtr = 1; $rowCtr <= $rowCount; $rowCtr++): ?>
	<tr>
		<td><?php echo $rowCtr; ?></td>
		<td><?php echo $sampleData; ?></td>
		<td><?php echo $sampleData; ?></td>
		<td><?php echo $sampleData; ?></td>
	</tr>
	<?php endfor; ?>
	</tbody>
</table>