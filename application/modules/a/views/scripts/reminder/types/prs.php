<form  role="form" id='frm_prospect'>	
<div class="row form-horizontal">
				<div class="col-md-12">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet light">
						<div class="portlet-title">
							<div class="caption col-sm-6">
								<i class="fa fa-pencil font-green-sharp"></i>
								<span class="caption-subject font-green-sharp bold uppercase">Edit Prospect</span>
							</div>

							
						</div>
						<div class="portlet-body">

							
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Date</label>
									<div class="col-md-6">

											<input id='prs_date' type="text" class="form-control" name='prs_date' value="<?php echo $this->prs['prs_date'] != '0000-00-00' ? App_Date::fromMysqlDate($this->prs['prs_date']) : ''; ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Agent</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_agent' value="<?php echo $this->prs['prs_agent'] ?>">
									</div>
								</div>
								
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Agency</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_agency' value="<?php echo $this->prs['prs_agency'] ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Phone</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_phone' value="<?php echo $this->prs['prs_phone'] ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Email</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_email' value="<?php echo $this->prs['prs_email'] ?>">
									</div>
								</div>
								
								
								<div class="form-group">
									<label  class="col-md-4 control-label">Call attempt</label>
									<div class="col-md-6">

											<select name='prs_call_attempt'  class='form-control'>
												<?php 
												echo App_Form_Select::simpleBuild(Application_Model_Imported::$callAttempt, $this->prs['prs_call_attempt'])
												?>
											</select>
									</div>
								</div>
								
								<div class="form-group">
									<label  class="col-md-4 control-label">Status</label>
									<div class="col-md-6">
											<select name='prs_status' class='form-control'>
												<?php 
												echo App_Form_Select::simpleBuild(Application_Model_Imported::$status, $this->prs['prs_status'])
												?>
											</select>
										
									</div>
								</div>
								
								
									<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Disposition</label>
									<div class="col-md-6">

											<select name='prs_dsp_id' class='form-control'>
												<?php 
												echo App_Form_Select::build($this->dsp['rows'], array('key' => 'dsp_id', 'value' => 'dsp_name'));
												?>
											</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-md-4 control-label">Notes</label>
									<div class="col-md-6">

											<textarea  class="form-control" name='prs_notes'><?php echo $this->prs['prs_notes'] ?></textarea>
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">POC</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_poc' value="<?php echo $this->prs['prs_poc'] ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">FAX</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_fax' value="<?php echo $this->prs['prs_fax'] ?>">
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputEmail12" class="col-md-4 control-label">Address</label>
									<div class="col-md-6">

											<input type="text" class="form-control" name='prs_address' value="<?php echo $this->prs['prs_address'] ?>">
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-offset-4 col-md-8">
									<a href="#promote-to-partner" class="btn purple" data-toggle='modal'>
															<i class="fa fa-arrow-circle-up"></i> Promote to partner </a>
										<a href="javascript:;" class="btn green" onclick='registerEntry()'>
															<i class="fa fa-save"></i> Save  </a>
									</div>
								</div>
								

						</div>	
					</div>
				</div>
				
</div>
					

	
							<div class="modal fade" id=promote-to-partner tabindex="-1" role="basic" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
											<h4 class="modal-title">Promote to partner</h4>
										</div>
										<div class="modal-body">
											<a class="btn  btn-block btn-primary" href="javascript:;" onclick='promoteTo(<?php echo Application_Model_Promote::PROMOTE_TYPE_HOME_INSPECTOR?>)'>Home Inspector</a>
											<a class="btn btn-primary btn-block" href="javascript:;" onclick='promoteTo(<?php echo Application_Model_Promote::PROMOTE_TYPE_REAL_ESTATE?>)'>Real Estate</a>
											<a class="btn btn-primary btn-block" href="javascript:;"  onclick='promoteTo(<?php echo Application_Model_Promote::PROMOTE_TYPE_INSURANCE_AGENT?>)'>Insurance Agent</a>
											<a class="btn btn-primary btn-block" href="javascript:;" onclick='promoteTo(<?php echo Application_Model_Promote::PROMOTE_TYPE_LARGE_ORGANIZATION?>)'>Large Corporation</a>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn default" data-dismiss="modal">Close</button>

										</div>
									</div>
									<!-- /.modal-content -->
								</div>
								<!-- /.modal-dialog -->
							</div>
							<!-- /.modal -->
							
</form>						
<script>

function  saveAnswers()
{
	$('#save_answers').val(1);
	registerEntry();

}

function promoteTo(promoteType)
{
	$.ajax({
		type : 'post',
		data : 	$('#frm_prospect').serialize(),

		url : '/prospect/post/getid/true/id/<?php echo $this->prs['prs_id']?>',
		success : function(r)
		{

			if( !isNaN(parseFloat(r)) && isFinite(r))
			{
				document.location.href = '/promote/add/type/<?php echo Application_Model_Import::IMP_TYPE_PROSPECTS?>/promote-type/'+ promoteType  + '/ref_id/' + r
			}
			else 
			{
				bootbox.alert(r);
			}
		}
});
}

function getMonthName(mNr)
{

	var month = new Array();
	month[0] = "January";
	month[1] = "February";
	month[2] = "March";
	month[3] = "April";
	month[4] = "May";
	month[5] = "June";
	month[6] = "July";
	month[7] = "August";
	month[8] = "September";
	month[9] = "October";
	month[10] = "November";
	month[11] = "December";
	return month[mNr];
}
function buildAlert(d)
{
	$('#info-alert').removeClass('hide');
	d = new Date(d);
	$('#info-reminder').html('will be set for: '+ d.getDate() + ' ' + getMonthName(d.getMonth()) + ' ' + d.getFullYear() + ' at ' + d.getHours() + ':' + d.getMinutes());
	date = d.getUTCFullYear() + '-' +
    ('00' + (d.getUTCMonth()+1)).slice(-2) + '-' +
    ('00' + d.getUTCDate()).slice(-2) + ' ' + 
    ('00' + d.getUTCHours()).slice(-2) + ':' + 
    ('00' + d.getUTCMinutes()).slice(-2) + ':' + 
    ('00' + d.getUTCSeconds()).slice(-2);
	$('#ntf_date').val(date);
}
$(function(){

		$('#prs_date').datepicker({
			 autoclose: true,
			});
	   $(".form_datetime").datetimepicker({

           autoclose: true,

           isRTL: Metronic.isRTL(),

           format: "dd MM yyyy - hh:ii",
           showMeridian: true,

           pickerPosition: (Metronic.isRTL() ? "bottom-right" : "bottom-left"),
           onSelect: function(date) {
               alert(date)
           },

       }).on('changeDate', function(e){
				buildAlert(e.date);
           });
;
	
})
/*
function promoteToAffiliate()
{
	$.ajax({
		type : 'post',
		data : 	$('#frm_lead').serialize(),

		url : '/lead/post/getid/true',
		success : function(r)
		{
			if( !isNaN(parseFloat(r)) && isFinite(r))
			{
				alert('mere');
			}
			else 
			{
				bootbox.alert(r);
			}
		}
});
}
*/


function registerEntry()
{
	$.ajax({
				type : 'post',
				data : 	$('#frm_prospect').serialize(),

				url : '/prospect/post/id/<?php echo $this->prs['prs_id']?>',
				success : function(r)
				{
					if(r == 'done')
					{
						//document.location.reload();
						bootbox.alert('Entry was edited!');
					}
					else 
					{
						bootbox.alert(r);
					}
				}
		});
}
</script>
					