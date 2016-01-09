<page backimg="<?php echo DOCROOT?>uploads/bg_print/bg_a5_l.jpg" backimgx="center" backimgy="middle" backimgw="100%">
	<table cellpadding="0" cellspacing="0" style="width:100%; padding: 5mm;">
		<tbody>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 60px;font-weight: bold;padding-bottom: 45px;">Certificate of Completion</td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 30px;font-weight: bold;padding-bottom: 35px;">This is to certify that</td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 35px;font-weight: bold;"><?php echo $this->sess_cus['name'];?></td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 30px;font-weight: bold;padding-bottom: 35px;">has successfully completed the course</td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 30px;font-weight: bold;padding-bottom: 35px;">Pesticide Safety (Approved for 1 hour of Laws)</td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: center; width:100%;font-size: 30px;font-weight: bold;">on <?php echo !empty($certificate['date'])?$this->format_int_date($certificate['date'],'m/d/Y'):'';?> at <?php echo !empty($certificate['item']['provider_name'])?$certificate['item']['provider_name']:'';?>.</td>
			</tr>
			<tr>
				<td style="font-family: palace_mi;text-align: right; width:100%;font-size: 52px;padding-right: 10px;padding-bottom: 0px;padding-top: 0px;"><?php echo !empty($certificate['item']['course_manager'])?$certificate['item']['course_manager']:'';?></td>
			</tr>
			<tr>
				<td style="font-family: english_mt;text-align: right; width:100%;font-size: 24px;font-weight: bold;padding-right: 10px;padding-bottom: 0px;padding-top: 0px;">Course manager</td>
			</tr>
		</tbody>
	</table>
</page>