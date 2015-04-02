<?php
class Ez_Interface_Helpers_Scripts extends Ez_Interface_Helpers
{
	public static function getSectionPortlet($section = 1, $module = 0)
	{
		$modelScriptList = new Application_Model_Script_List(array('scr_section' => $section));
		$__scripts = $modelScriptList->find();
		$__scripts = $__scripts['rows'];
		$scripts = array();
		if(count($__scripts))
		{
			foreach ($__scripts as $script)
			{
				$scripts[$script['scr_type']][] = $script;
			}
		}
		
		//display the portlet
		?>
		<div class="portlet box blue-hoki">
			<div class="portlet-title tabbable-line">
				<div class="caption">
					<i class="fa  fa-bookmark"></i> Scripts
				</div>
				
			</div>
			<div class="portlet-body">
			
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#tab_script_for_osc" data-toggle="tab">
					OSC </a>
				</li>
				<li>
					<a href="#tab_script_for_secure24" data-toggle="tab">
					Secure24</a>
				</li>
			</ul>
				<div class="portlet-body">
					<div class="tab-content">
						<div class="tab-pane active" id="tab_script_for_osc">
							<a class="btn blue btn-block" data-toggle="modal" href='#opening-script'>Opening</a>
							<a class="btn default btn-block" data-toggle="modal" href='#closing-script'>Closing</a>
							<a class="btn red btn-block" data-toggle="modal" href='#objections-script'>Objections</a>
							<a class="btn green btn-block" data-toggle="modal" href='#asp-script'>ASP</a>
						</div>
						
						<div class="tab-pane" id="tab_script_for_secure24">
							<a class="btn blue btn-block" data-toggle="modal" href='#opening-script-s24'>Opening</a>
							<a class="btn default btn-block" data-toggle="modal" href='#closing-script-s24'>Closing</a>
							<a class="btn red btn-block" data-toggle="modal" href='#objections-script-s24'>Objections</a>
							<a class="btn green btn-block" data-toggle="modal" href='#asp-script-s24'>ASP</a>
						</div>
					</div>						
				</div>
			</div>
		</div>
			
				<!--  Display the modals -->
		
		<div class="modal fade bs-modal-lg" id="opening-script" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Opening Scripts</h4>
					</div>
					<div class="modal-body">
						
						<?php 
						$secure24Scripts = array();
						if(isset($scripts[Application_Model_Script::SCRIPT_TYPE_OPENING]) && count($scripts[Application_Model_Script::SCRIPT_TYPE_OPENING]))
						{
							foreach ($scripts[Application_Model_Script::SCRIPT_TYPE_OPENING] as $script)
							{
								if(!isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING]))
								{
									$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING] = array();
								}
								
								if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_SECURE24)
								{
									
									$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING] = $script;
									continue;
								}
								
								if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_BOTH)
								{
									$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING][] = $script;
								}

								?>
								<div class='well'>
								<h3><?php echo $script['scr_title']?>:</h3>
								<?php 
									echo App_Word::renderText($script['scr_content'])
								?>
								</div>
								<?php
							}
						}
						?>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
	
		<div class="modal fade bs-modal-lg" id="objections-script" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Objection Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion3">
						<?php
								if(isset($scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS]) && count($scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS]))
										{
											foreach ($scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS] as $script)
											{
												if(!isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS]))
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS] = array();
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_SECURE24)
												{
														
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS] = $script;
													continue;
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_BOTH)
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS][] = $script;
												}
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3" href="#collapse_3_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_3_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
								
							
		<div class="modal fade bs-modal-lg" id="closing-script" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Closing Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion-closing">
						<?php
								if(isset($scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING]) && count($scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING]))
										{
											foreach ($scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING] as $script)
											{
												if(!isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING]))
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING] = array();
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_SECURE24)
												{
												
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING] = $script;
													continue;
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_BOTH)
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING][] = $script;
												}
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion-closing" href="#collapse_4_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_4_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>	
		
		<div class="modal fade bs-modal-lg" id="asp-script" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">ASP Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion-asp">
						<?php
								if(isset($scripts[Application_Model_Script::SCRIPT_TYPE_ASP]) && count($scripts[Application_Model_Script::SCRIPT_TYPE_ASP]))
										{
											foreach ($scripts[Application_Model_Script::SCRIPT_TYPE_ASP] as $script)
											{
												if(!isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP]))
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP] = array();
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_SECURE24)
												{
												
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP][] = $script;
													continue;
												}
												
												if($script['scr_module'] == Application_Model_Module::MODULE_TYPE_BOTH)
												{
													$secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP][] = $script;
												}
												
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion-asp" href="#collapse_4_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_4_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		
		<!-- modals for secure 24 -->
		
				<div class="modal fade bs-modal-lg" id="opening-script-s24" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Opening Scripts</h4>
					</div>
					<div class="modal-body">
						
						<?php 
						if(isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING]) && count($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING]))
						{
							foreach ($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OPENING] as $script)
							{
								?>
								<div class='well'>
								<h3><?php echo $script['scr_title']?>:</h3>
								<?php 
									echo App_Word::renderText($script['scr_content'])
								?>
								</div>
								<?php
							}
						}
						?>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
	
		<div class="modal fade bs-modal-lg" id="objections-script-s24" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Objection Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion3-s24">
						<?php
								if(isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS]) && count($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS]))
										{
											foreach ($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_OBJECTIONS] as $script)
											{
												
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion3-s24" href="#collapse_s24_3_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_s24_3_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
								
							
		<div class="modal fade bs-modal-lg" id="closing-script-s24" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">Closing Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion-closing-s24">
						<?php
								if(isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING]) && count($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING]))
										{
											foreach ($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_CLOSING] as $script)
											{
												
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion-closing-s24" href="#collapse_s24_4_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_s24_4_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>	
		
		<div class="modal fade bs-modal-lg" id="asp-script-s24" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">ASP Scripts</h4>
					</div>
					<div class="modal-body">
						<div class="panel-group accordion" id="accordion-asp-s24">
						<?php
								if(isset($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP]) && count($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP]))
										{
											foreach ($secure24Scripts[Application_Model_Script::SCRIPT_TYPE_ASP] as $script)
											{
											
												
												?>
							<div class="panel panel-default">
								<div class="panel-heading">
								
											
									<h4 class="panel-title">
									<a class="accordion-toggle accordion-toggle-styled" data-toggle="collapse" data-parent="#accordion-asp-s24" href="#collapse_s24_4_<?php echo $script['scr_id']?>">
									<?php echo $script['scr_title']?></a>
									</h4>
								</div>
								<div id="collapse_s24_4_<?php echo $script['scr_id']?>" class="panel-collapse collapse">
									<div class="panel-body">
										<?php 
										echo App_Word::renderText($script['scr_content']);
										?>
									
									</div>
								</div>
							</div>
							<?php 
											}
										}
							?>
							
							
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn blue" data-dismiss="modal">Close</button>
					</div>
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal-dialog -->
		</div>
		
		<?php
		
	}
	
	
}