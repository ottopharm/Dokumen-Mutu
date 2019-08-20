<table class="table table-bordered table-condensed">
	<thead>
		<tr>
			<th>No.</th>
			<th>Requestor</th>
			<th>Doc. Type</th>
			<th>Doc. Number</th>
			<th>Submitted</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$html = '';
	// foreach($pr as $p) {
	// 	$active = ($idx == 1)?' active':'';
	// 	$html .= '<div class="list-group">
	// 		<a href="javascript:void(0)" class="list-group-item'.$active.'" 
	// 			onclick="open_tabs(\''.$this->createUrl('/TrxPr/approvalForm/prid').$p['pr_id'].'\',\'PR Approval\');$(\'#myDialog\').dialog(\'close\');">
	// 			PR No. '.$p['pr_code'].'
	// 		</a>
	// 	</div>';
	// 	$idx++;
	// }
	if(!empty($approval)) {
		$no = 1;
		foreach($approval as $a) {
			$highlight = ($a['is_read'] == 0)?' class="info"':'';
			$html .= '<tr'.$highlight.'>
				<td style="text-align:right">'.$no.'.</td>
				<td>'.$a['requestor_name'].'</td>
				<td>'.$a['doc_type'].'</td>
				<td>'.$a['doc_number'].'</td>
				<td>'.date('d-m-Y H:i:s',strtotime($a['created_date'])).'</td>
				<td><a href="javascript:void(0)"
					onclick="open_tabs(\''.$this->createUrl('/site/approvalForm/id').$a['approval_id'].'\',\'Approval Form\');$(\'#myDialog\').dialog(\'close\');">
					<strong>detail</strong>
					</a>
				</td>
			</tr>';
			// $active = ($a['is_read'] == 0)?' active':'';
			// $html .= '<div class="list-group">
			// 	<a href="javascript:void(0)" class="list-group-item'.$active.'" 
			// 		onclick="open_tabs(\''.$this->createUrl('/site/approvalForm/id').$a['approval_id'].'\',\'Approval Form\');$(\'#myDialog\').dialog(\'close\');">
			// 		<strong>'.$a['requestor_name'].'</strong>
			// 	</a>
			// </div>';
			$no++;
		}
	}
	echo $html;
	?>
	</tbody>
</table>
