<?php
include_once str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-load.php";
include_once str_replace("\\", "/", dirname(dirname(dirname(dirname(dirname(__FILE__))))))."/wp-includes/pluggable.php";

class Helper_Word {

	public function __construct()
	{
		$func = $_GET['create'];
		$this->$func();
	}

	public function word()
	{
		$filter = array(
            'sort' => "floor_name",
            'search' => ""
        );
		$event_id = $_GET['event_id'];
		$event = get_post($event_id); 
		$model_attendee = &GnGn::getInstance('Model_Attendee');
		$attendees = $model_attendee->get_all_attendees($event_id, $filter);

		header("Content-type: application/vnd.ms-word");
		header(sprintf('Content-Disposition: attachment;Filename=%s.doc', $event->post_title));
		ob_start();
		?>
			<html>
			<meta http-equiv="Content-Type" content="text/html; charset=Windows-1252">
			<body style="text-align:center">
			<table border="1">
			<thead>
			<tr>
			<th>Floor name</th>
			<th>Full name</th>
			<th>Office Branch</th>
			<th>Email Address</th>
			<th>Contact Number</th>
			</tr>
			</thead>
			<tbody>
			  <?php if(!empty($attendees) && is_array($attendees)):
			  	  $branch = array(1 =>'Clark', 'Makati', 'Guest');
			      foreach ($attendees as $key => $value) { ?>
			          <tr>
			              <td><?php printf('%s', $value['floor_name'])?></td> 
			              <td><?php printf('%s %s %s', $value['firstname'], $value['middlename'], $value['lastname'])?></td> 
			              <td><?php printf('%s', $branch[$value['office_branch']]);?></td>
			              <td><?php printf('%s', $value['email_address'])?></td>
			              <td><?php printf('%s', $value['contact_no'])?></td>
			          </tr>
			      <?php } else: ?>
			          <tr>
			              <td colspan="5">No one registered yet.</td>
			          </tr>
			  <?php endif; ?>
			</tbody>
			</table>
			</body>
			</html>
		<?php
		$content = ob_get_contents();
		ob_end_clean();
		echo $content;
	}

	public function excel()
	{
		$filter = array(
            'sort' => "floor_name",
            'search' => ""
        );
		$event_id = $_GET['event_id'];
		$event = get_post($event_id); 
		$model_attendee = &GnGn::getInstance('Model_Attendee');
		$attendees = $model_attendee->get_all_attendees($event_id, $filter);

		header("Content-Type: application/csv");
		header(sprintf('Content-Disposition: attachment;Filename=%s.csv', $event->post_title));

		$outstr = 'FLOOR NAME, FULL NAME, OFFICE BRANCH, EMAIL ADDRESS, CONTACT NUMBER';
		$outstr .= sprintf('%s', "\n");
		if(!empty($attendees) && is_array($attendees)):
			$branch = array(1 =>'Clark', 'Makati', 'Guest');
	      	foreach ($attendees as $key => $value) { 
	      		$outstr.= join(',', array($value['floor_name'], sprintf('%s %s %s', $value['firstname'], $value['middlename'], $value['lastname']), 
	      							sprintf('%s', $branch[$value['office_branch']]), $value['email_address'], sprintf('No. %s', $value['contact_no'])));
	      		$outstr .= sprintf('%s', "\n");
	     	} 
	    else: 
	      		$outstr.= join(',', 'No one registered');
		endif;
		echo $outstr;
	}
}

new Helper_Word;

