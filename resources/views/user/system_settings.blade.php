<style>
.tabs-container {
    overflow-x: auto; /* Add horizontal scrollbar if tabs overflow */
    max-width: 100%; /* Ensure the container doesn't exceed the modal width */
  }
  .statement_invoice { width:28px !important; }
  </style>
<div class="modal fade dashboard_email_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:90%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Settings</h4>
          </div>
          <div class="modal-body" style="clear:both"> 
            <div class="tabs-container">
              <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item active">
                  <a class="nav-link active" id="dashboardfirst-tab" data-toggle="tab" href="#dashboardfirst" role="tab" aria-controls="dashboardfirst" aria-selected="true">Practice</a>
                </li>
                
                <li class="nav-item">
                  <a class="nav-link" id="dashboardcontact-tab" data-toggle="tab" href="#dashboardcontact" role="tab" aria-controls="dashboardcontact" aria-selected="false">Manage Users</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" id="dashboardpmssystem-tab" data-toggle="tab" href="#dashboardpmssystem" role="tab" aria-controls="dashboardpmssystem" aria-selected="false">PMS Settings</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" id="dashboardinfile-tab" data-toggle="tab" href="#dashboardinfile" role="tab" aria-controls="dashboardinfile" aria-selected="false">Infile Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardaml-tab" data-toggle="tab" href="#dashboardaml" role="tab" aria-controls="dashboardaml" aria-selected="false">AML Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardcroard-tab" data-toggle="tab" href="#dashboardcroard" role="tab" aria-controls="dashboardcroard" aria-selected="false">CRO-ARD Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardkeydocs-tab" data-toggle="tab" href="#dashboardkeydocs" role="tab" aria-controls="dashboardkeydocs" aria-selected="false">Keydocs Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardyearend-tab" data-toggle="tab" href="#dashboardyearend" role="tab" aria-controls="dashboardyearend" aria-selected="false">Yearend Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardmessageus-tab" data-toggle="tab" href="#dashboardmessageus" role="tab" aria-controls="dashboardmessageus" aria-selected="false">MessageUs Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardtaskmanager-tab" data-toggle="tab" href="#dashboardtaskmanager" role="tab" aria-controls="dashboardtaskmanager" aria-selected="false">Taskmanager Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardrequest-tab" data-toggle="tab" href="#dashboardrequest" role="tab" aria-controls="dashboardrequest" aria-selected="false">CRS Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardcar-tab" data-toggle="tab" href="#dashboardcar" role="tab" aria-controls="dashboardcar" aria-selected="false">Client Account Review Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardstatement-tab" data-toggle="tab" href="#dashboardstatement" role="tab" aria-controls="dashboardstatement" aria-selected="false">Client Statement Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardvat-tab" data-toggle="tab" href="#dashboardvat" role="tab" aria-controls="dashboardvat" aria-selected="false">VAT Settings</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="dashboardinv-tab" data-toggle="tab" href="#dashboardinv" role="tab" aria-controls="dashboardinv" aria-selected="false">Build Invoice Settings</a>
                </li>
              </ul>
              <?php
              $practice_details = DB::table('practices')->where('practice_code',Session::get('user_practice_code'))->first();
              $practice_code = Session::get('user_practice_code');
              $practice_name = '';
              $address1 = '';
              $address2 = '';
              $address3 = '';
              $address4 = '';
              $link1 = '';
              $link2 = '';
              $link3 = '';
              $phoneno = '';

              if($practice_details)
              {
                $practice_code = $practice_details->practice_code;
                $practice_name = $practice_details->practice_name;
                $address1 = $practice_details->address1;
                $address2 = $practice_details->address2;
                $address3 = $practice_details->address3;
                $address4 = $practice_details->address4;
                $link1 = $practice_details->link1;
                $link2 = $practice_details->link2;
                $link3 = $practice_details->link3;
                $phoneno = $practice_details->phoneno;
              }

              $infile_settings = DB::table('infile_settings')->where('practice_code', Session::get('user_practice_code'))->first();
              $infile_cc_email = '';
              if($infile_settings){
                $infile_cc_email = $infile_settings->cc_email;
              }

              $aml_settings = DB::table('aml_settings')->where('practice_code', Session::get('user_practice_code'))->first();
              $aml_cc_email = '';
              if($aml_settings){
                $aml_cc_email = $aml_settings->aml_cc_email;
              }
              ?>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="dashboardfirst" role="tabpanel" aria-labelledby="dashboardfirst-tab">
                <form id="dashboardform-validation-email" name="dashboardform-validation" method="POST" action="<?php echo URL::to('user/update_practice_setting'); ?>">
                  <div class="col-lg-12 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Practice Code:</label>
                             <input id="validation-email"
                                   class="form-control"
                                   placeholder="Enter Practice Code"
                                   value="<?php echo $practice_code ?>"
                                   name="practice_code"
                                   type="text"
                                   readonly style="background-color: #dfdfdf !important;">                                
                        </div> 
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Practice Name:</label>
                            <input id="validation-practice_name"
                                   class="form-control"
                                   placeholder="Enter Practice Name"
                                   value="<?php echo $practice_name; ?>"
                                   name="practice_name"
                                   type="text"
                                   required>                                
                        </div> 
                      </div> 
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 1:</label>
                            <input id="validation-address_1"
                                   class="form-control"
                                   placeholder="Enter Address 1"
                                   value="<?php echo $address1; ?>"
                                   name="address_1"
                                   type="text"
                                   required>                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 2:</label>
                            <input id="validation-address_2"
                                   class="form-control"
                                   placeholder="Enter Address 2"
                                   value="<?php echo $address2; ?>"
                                   name="address_2"
                                   type="text">                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 3:</label>
                            <input id="validation-address_3"
                                   class="form-control"
                                   placeholder="Enter Address 3"
                                   value="<?php echo $address3; ?>"
                                   name="address_3"
                                   type="text">                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 4:</label>
                            <input id="validation-address_4"
                                   class="form-control"
                                   placeholder="Enter Address 4"
                                   value="<?php echo $address4; ?>"
                                   name="address_4"
                                   type="text">                                
                        </div> 
                      </div>
                      <div class="col-lg-6"> 
                        <div class="form-group">
                            <label>Link 1:</label>
                            <input id="validation-link_1"
                                   class="form-control"
                                   placeholder="Enter Link 1"
                                   value="<?php echo $link1; ?>"
                                   name="link_1"
                                   type="text"
                                   required>                                
                        </div>  
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Link 2:</label>
                            <input id="validation-link_2"
                                   class="form-control"
                                   placeholder="Enter Link 2"
                                   value="<?php echo $link2; ?>"
                                   name="link_2"
                                   type="text">                                
                        </div>  
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Link 3:</label>
                            <input id="validation-link_3"
                                   class="form-control"
                                   placeholder="Enter Link 3"
                                   value="<?php echo $link3; ?>"
                                   name="link_3"
                                   type="text">                                
                        </div> 
                      </div>
                      <div class="col-lg-6"> 
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input id="validation-phone_no"
                                   class="form-control"
                                   placeholder="Enter Phone Number"
                                   value="<?php echo $phoneno; ?>"
                                   name="phone_no"
                                   type="text"
                                   required>                                
                        </div>  
                      </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;text-align:right">
                      <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
                      <input type="submit" class="common_black_button" id="park_submit" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>

              <div class="tab-pane fade" id="dashboardcontact" role="tabpanel" aria-labelledby="dashboardcontact-tab">
                <iframe src="{{URL::to('user/manage_user')}}" style="width:100%;height:800px;"></iframe>
              </div>
              <div class="tab-pane fade" id="dashboardpmssystem" role="tabpanel" aria-labelledby="dashboardpmssystem-tab">
                <h4 class="modal-title" style="font-weight:700;font-size:20px">Payroll Settings</h4>
                <ul class="nav nav-tabs" id="myTab2" role="tablist">
                  <li class="nav-item active">
                    <a class="nav-link active" id="setemaildashboard-tab" data-toggle="tab" href="#setemaildashboard" role="tab" aria-controls="setemaildashboard" aria-selected="true">Email Settings</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="setnotifydashboard-tab" data-toggle="tab" href="#setnotifydashboard" role="tab" aria-controls="setnotifydashboard" aria-selected="false">Notification Message</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="setdistributedashboard-tab" data-toggle="tab" href="#setdistributedashboard" role="tab" aria-controls="setdistributedashboard" aria-selected="false">Distribute by Link</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="profiledashboard-tab" data-toggle="tab" href="#profiledashboard" role="tab" aria-controls="profiledashboard" aria-selected="false">Manage Year</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent2">
                  <div class="tab-pane fade active in" id="setemaildashboard" role="tabpanel" aria-labelledby="setemaildashboard-tab">
                    <div class="admin_content_section" style="margin-top:10px">  
                      <div>
                        <div class="table-responsive">
                          <div>
                            <?php
                              $pms_admin_details = DB::table('pms_admin')->where('practice_code',Session::get('user_practice_code'))->first();
                            if(Session::has('message')) { ?>
                                <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
                            <?php }
                            ?>
                          </div>
                          <div class="col-lg-12 text-left padding_00">
                            <form name="dashboardpayroll_settings_form" id="dashboardpayroll_settings_form" method="post" action="<?php echo URL::to('user/save_payroll_settings'); ?>">
                              <div class="col-md-12 padding_00">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 22%;margin-top: 13px;">PMS Email Header Image:</spam>
                              <?php
                              if($pms_admin_details->email_header_url == '') {
                                $default_image = DB::table("email_header_images")->first();
                                if($default_image->url == "") {
                                  $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                                }
                                else{
                                  $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                                }
                              }
                              else {
                                $image_url = URL::to($pms_admin_details->email_header_url.'/'.$pms_admin_details->email_header_filename);
                              }
                              ?>
                              <img src="<?php echo $image_url; ?>" class="pms_dashboard_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                              <input type="button" name="pms_dashboard_email_header_img_btn" class="common_black_button pms_dashboard_email_header_img_btn" value="Browse">
                              </div>
                              <div class="col-md-12 padding_00" style="margin: 16px 0px;">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 22%;margin-top: 13px;">Paye M.R.S Email Header Image:</spam>
                              <?php
                              if($pms_admin_details->paye_mrs_email_header_url == '') {
                                $default_image = DB::table("email_header_images")->first();
                                if($default_image->url == "") {
                                  $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                                }
                                else{
                                  $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                                }
                              }
                              else {
                                $image_url = URL::to($pms_admin_details->paye_mrs_email_header_url.'/'.$pms_admin_details->paye_mrs_email_header_filename);
                              }
                              ?>
                              <img src="<?php echo $image_url; ?>" class="paye_mrs_dashboard_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                              <input type="button" name="paye_mrs_dashboard_email_header_img_btn" class="common_black_button paye_mrs_dashboard_email_header_img_btn" value="Browse">
                              </div>
                              <h4>Enter Email Signature:</h4>
                              <textarea name="message_editor" id="editor_dashboard_999"><?php echo $pms_admin_details->payroll_signature; ?></textarea>
                              <h4>Enter CC Email Box:</h4>
                              <input type="text" name="payroll_cc_input" class="form-control dashboardpayroll_cc_input" value="<?php echo $pms_admin_details->payroll_cc_email; ?>">
                              <div class="modal-footer">  
                                  <input type="submit" name="submit_payroll_settings" class="common_black_button submit_dashboardpayroll_settings" value="Submit">
                              </div>
                              @csrf
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="setnotifydashboard" role="tabpanel" aria-labelledby="setnotifydashboard-tab">
                    <form action="<?php echo URL::to('user/update_user_notification'); ?>" method="post" id="dashboardupdate_user_form">
                      <h4>Enter Notification Message:</h4>
                      <textarea class="form-control input-sm" id="editor_dashboard_9999"  name="user_notification" style="height:100px"><?php echo $pms_admin_details->notify_message; ?></textarea>
                      <div class="row">
                        <div class="col-md-12" style="text-align:center; margin-top:20px">
                            <input type="submit" name="notify_submit" id="dashboardnotify_submit" class="btn common_black_button" value="Update">
                        </div>
                      </div>
                      @csrf
                    </form>
                  </div>
                  <div class="tab-pane fade" id="setdistributedashboard" role="tabpanel" aria-labelledby="setdistributedashboard-tab">
                    <form action="<?php echo URL::to('user/update_distribute_link'); ?>" method="post" id="dashboardupdate_distribute_link">
                      <h4>Enter Distribution by Link Message:</h4>
                      <textarea class="form-control input-sm" id="editor_dashboard_distribute"  name="distribute_link" style="height:100px"><?php echo $pms_admin_details->distribute_link; ?></textarea>
                      <div class="row">
                        <div class="col-md-12" style="text-align:center; margin-top:20px">
                            <input type="submit" name="distribute_submit" id="dashboarddistribute_submit" class="btn common_black_button" value="Update">
                        </div>
                      </div>
                      @csrf
                    </form>
                  </div>
                  <div class="tab-pane fade" id="profiledashboard" role="tabpanel" aria-labelledby="profiledashboard-tab">
                    <iframe src="{{URL::to('user/manage_task')}}" style="width:100%;height:800px"></iframe>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="dashboardinfile" role="tabpanel" aria-labelledby="dashboardinfile-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form id="dashboardform-validation-email" name="dashboardform-validation" method="POST" action="<?php echo URL::to('user/update_infile_settings'); ?>">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                      <div>
                        <?php
                        if(Session::has('emailmessage')) { ?>
                            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                        <?php }
                        ?>
                      </div>
                        <div class="form-group">
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($infile_settings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($infile_settings->email_header_url.'/'.$infile_settings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="infile_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="infile_email_header_img_btn" class="common_black_button infile_email_header_img_btn" value="Browse"> 
                            <h4>Infile CC Email ID</h4>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Infile CC Email ID"
                                   value="<?php echo $infile_cc_email; ?>"
                                   name="infile_cc_email"
                                   type="text"
                                   required>      

                            <h4>Infile Items Initial Load Count:</h4>
                            <input type="number" name="loadcount" class="form-control loadcount" id="loadcount" value="<?php echo $infile_settings->loadcount; ?>" placeholder="Enter Load Count Values" required>                           
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" class="common_black_button" id="dashboardinfile_submit" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="dashboardaml" role="tabpanel" aria-labelledby="dashboardaml-tab" style="max-height:600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <div class="col-md-6" style="margin-top:20px">
                  <form id="dashboardform-validation-email" name="dashboardform-validation" method="POST" action="<?php echo URL::to('user/update_aml_settings'); ?>">
                    <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 28%;margin-top: 13px;">Email Header Image:</spam>
                      <?php
                      if($aml_settings->email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($aml_settings->email_header_url.'/'.$aml_settings->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="aml_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="aml_email_header_img_btn" class="common_black_button aml_email_header_img_btn" value="Browse">
                      <h4 style="margin-top:20px">Enter AML CC Email Box:</h4>
                      <input type="text" name="aml_cc_input" class="form-control aml_cc_input" value="<?php echo $aml_settings->aml_cc_email; ?>">

                      <h4 style="margin-top:20px">Email Body for ID Request:</h4>
                      <textarea name="editoramlbody" id="editoramlbody"><?php echo $aml_settings->email_body; ?></textarea>

                      <h4 style="margin-top:20px">Enter Email Signature:</h4>
                      <textarea name="editoramlsignature" id="editoramlsignature"><?php echo $aml_settings->email_signature; ?></textarea>
                      
                      <div class="modal-footer">  
                          <input type="submit" name="submit_aml_settings" class="common_black_button submit_aml_settings" value="Submit">
                      </div>
                  @csrf
                  </form>
                </div>
              </div>
              <?php
              $croard_settings = DB::table("croard_settings")->where('practice_code',Session::get('user_practice_code'))->first();
              ?>
              <div class="tab-pane fade" id="dashboardcroard" role="tabpanel" aria-labelledby="dashboardcroard-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form id="dashboardform-validation-email" name="dashboardform-validation" method="POST" action="<?php echo URL::to('user/save_croard_settings'); ?>">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                          <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                          <?php
                          if($croard_settings->email_header_url == '') {
                            $default_image = DB::table("email_header_images")->first();
                            if($default_image->url == "") {
                              $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                            }
                            else{
                              $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                            }
                          }
                          else {
                            $image_url = URL::to($croard_settings->email_header_url.'/'.$croard_settings->email_header_filename);
                          }
                          ?>
                          <img src="<?php echo $image_url; ?>" class="croard_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                          <input type="button" name="croard_email_header_img_btn" class="common_black_button croard_email_header_img_btn" value="Browse">
                          <div class="col-lg-12 padding_00" style="margin-top:30px">
                              <div class="form-group">
                                  <label>Enter Email Signature:</label>
                                  <textarea name="message_editor" id="editor_dashboard_croard"><?php echo $croard_settings->croard_signature; ?></textarea>                       
                              </div>
                          </div>
                          <div class="col-lg-12 padding_00">
                              <div class="form-group">
                                  <label>Enter CC Box:</label>
                                  <input type="text" name="croard_cc_input" class="form-control dashboardcroard_cc_input" value="<?php echo $croard_settings->croard_cc_email; ?>">
                              </div>
                          </div>
                          <div class="col-lg-12 padding_00">
                              <div class="form-group">
                                  <label>Submission Days Allowed After ARD Date:</label>
                                  <input type="text" name="croard_days_input" class="form-control dashboardcroard_days_input" value="<?php echo $croard_settings->croard_submission_days; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                              </div>
                          </div>
                          <div class="col-lg-12 padding_00">
                              <div class="form-group">
                                  <label>Username:</label>
                                  <input id="validation-email" class="form-control" placeholder="Enter Username" value="<?php echo $croard_settings->username; ?>" name="username" type="text" required > 
                              </div>
                          </div>
                          <div class="col-lg-12 padding_00">
                              <div class="form-group">
                                  <label>API Key:</label>
                                  <input id="validation-cc-email" class="form-control" placeholder="Enter API Key" value="<?php echo $croard_settings->api_key; ?>" name="api_key" type="text" required> 
                              </div>
                          </div>
                          <div class="col-lg-12 padding_00">
                            <input type="submit" name="submit_croard_settings" class="common_black_button dashboardsubmit_croard_settings" value="Submit">
                          </div>
                        </div>
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <?php
              $keydocs_setttings = DB::table('key_docs_settings')->where('practice_code',Session::get('user_practice_code'))->first();
              ?>
              <div class="tab-pane fade" id="dashboardkeydocs" role="tabpanel" aria-labelledby="dashboardkeydocs-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form name="dashboardkeydocs_settings_form" id="dashboardkeydocs_settings_form" method="post" action="<?php echo URL::to('user/save_keydocs_settings'); ?>" enctype="multipart/form-data">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                            <?php
                            $keydocs_setttings = DB::table('key_docs_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                            ?>
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($keydocs_setttings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($keydocs_setttings->email_header_url.'/'.$keydocs_setttings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="keydocs_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="keydocs_dashboard_email_header_img_btn" class="common_black_button keydocs_dashboard_email_header_img_btn" value="Browse">

                            <h4>Enter Email Signature:</h4>
                            <textarea name="message_editor" id="editor_dashboard_keydocs"><?php echo $keydocs_setttings->keydocs_signature; ?></textarea>
                            <h4>Enter CC Box:</h4>
                            <input type="text" name="keydocs_cc_input" class="form-control dashboardkeydocs_cc_input" value="<?php echo $keydocs_setttings->keydocs_cc_email; ?>">                              
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" name="submit_keydocs_settings" class="common_black_button dashboardsubmit_keydocs_settings" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="dashboardyearend" role="tabpanel" aria-labelledby="dashboardyearend-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form name="dashboardyearend_settings_form" id="dashboardyearend_settings_form" method="post" action="<?php echo URL::to('user/update_yearend_settings'); ?>" enctype="multipart/form-data">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                            <?php
                            $yearend_setttings = DB::table('yearend_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                            ?>
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($yearend_setttings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($yearend_setttings->email_header_url.'/'.$yearend_setttings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="yearend_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="yearend_dashboard_email_header_img_btn" class="common_black_button yearend_dashboard_email_header_img_btn" value="Browse">
                            <h4>Enter CC Email Box:</h4>
                            <input type="text" name="yearend_cc_email" class="form-control dashboardyearend_cc_input" value="<?php echo $yearend_setttings->yearend_cc_email; ?>">                              
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" name="submit_yearend_settings" class="common_black_button dashboardsubmit_yearend_settings" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="dashboardmessageus" role="tabpanel" aria-labelledby="dashboardmessageus-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form name="dashboardmessageus_settings_form" id="dashboardmessageus_settings_form" method="post" action="<?php echo URL::to('user/update_messageus_settings'); ?>" enctype="multipart/form-data">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                            <?php
                            $messageus_setttings = DB::table('messageus_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                            ?>
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($messageus_setttings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($messageus_setttings->email_header_url.'/'.$messageus_setttings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="messageus_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="messageus_dashboard_email_header_img_btn" class="common_black_button messageus_dashboard_email_header_img_btn" value="Browse">
                            <h4 style="margin-top:20px">Enter CC Email Box:</h4>
                            <input type="text" name="messageus_cc_email" class="form-control dashboardmessageus_cc_input" value="<?php echo $messageus_setttings->messageus_cc_email; ?>">                              
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" name="submit_messageus_settings" class="common_black_button dashboardsubmit_messageus_settings" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <div class="tab-pane fade" id="dashboardtaskmanager" role="tabpanel" aria-labelledby="dashboardtaskmanager-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form name="dashboardtaskmanager_settings_form" id="dashboardtaskmanager_settings_form" method="post" action="<?php echo URL::to('user/save_taskmanager_settings'); ?>" enctype="multipart/form-data">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                            <?php
                            $taskmanager_setttings = DB::table('taskmanager_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                            ?>
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($taskmanager_setttings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($taskmanager_setttings->email_header_url.'/'.$taskmanager_setttings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="taskmanager_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="taskmanager_dashboard_email_header_img_btn" class="common_black_button taskmanager_dashboard_email_header_img_btn" value="Browse">
                            <h4>Enter TaskManager CC Email Box:</h4>
                            <input type="text" name="taskmanager_cc_email" class="form-control dashboardtaskmanager_cc_input" value="<?php echo $taskmanager_setttings->taskmanager_cc_email; ?>">                              
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" name="submit_taskmanager_settings" class="common_black_button dashboardsubmit_taskmanager_settings" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>

              <div class="tab-pane fade" id="dashboardrequest" role="tabpanel" aria-labelledby="dashboardrequest-tab" style="max-height:700px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <form name="dashboardrequest_settings_form" id="dashboardrequest_settings_form" method="post" action="<?php echo URL::to('user/save_crm_settings'); ?>" enctype="multipart/form-data">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                        <div class="form-group">
                            <?php
                            $request_setttings = DB::table('request_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                            ?>
                            <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                            <?php
                            if($request_setttings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                            }
                            else {
                              $image_url = URL::to($request_setttings->email_header_url.'/'.$request_setttings->email_header_filename);
                            }
                            ?>
                            <img src="<?php echo $image_url; ?>" class="request_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                            <input type="button" name="request_dashboard_email_header_img_btn" class="common_black_button request_dashboard_email_header_img_btn" value="Browse">
                            <h4>Enter CRS CC Email Box:</h4>
                            <input type="text" name="crs_cc_email" class="form-control dashboardrequest_cc_input" value="<?php echo $request_setttings->crs_cc_email; ?>">                              
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;margin-top: -49px;">
                      <input type="submit" name="submit_request_settings" class="common_black_button dashboardsubmit_request_settings" value="Submit">
                    </div>
                  </div>
                @csrf
                </form>
              </div>
              <?php
              $car_settings = DB::table('clientaccountreview_settings')->where('practice_code', Session::get('user_practice_code'))->first();
              $client_account_cc_email = '';
              if($car_settings){
                $client_account_cc_email = $car_settings->client_account_cc_email;
              }
              ?>
              <div class="tab-pane fade" id="dashboardcar" role="tabpanel" aria-labelledby="dashboardcar-tab" style="max-height:600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <div class="col-md-6" style="margin-top:20px">
                  <form id="dashboardform-validation-email" name="dashboardform-validation" method="POST" action="<?php echo URL::to('user/save_car_settings'); ?>">
                    <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;float: left;width: 28%;margin-top: 13px;">Email Header Image:</spam>
                      <?php
                      if($car_settings->email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($car_settings->email_header_url.'/'.$car_settings->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="car_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="car_email_header_img_btn" class="common_black_button car_email_header_img_btn" value="Browse">
                      <h4 style="margin-top:20px">Client Account Review CC Email:</h4>
                      <input type="text" name="client_account_cc_email" class="form-control client_account_cc_email" value="<?php echo $car_settings->client_account_cc_email; ?>">

                      <h4 style="margin-top:20px">Enter Email Signature:</h4>
                      <textarea name="email_signature" id="editorcarsignature"><?php echo $car_settings->email_signature; ?></textarea>
                      
                      <div class="modal-footer">  
                          <input type="submit" name="submit_car_settings" class="common_black_button submit_car_settings" value="Submit">
                      </div>
                  @csrf
                  </form>
                </div>
              </div>
              <div class="tab-pane fade" id="dashboardstatement" role="tabpanel" aria-labelledby="dashboardstatement-tab" style="max-height:600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <div class="col-md-6" style="margin-top:20px">
                 <form id="statement_settings_form" method="post" action="<?php echo URL::to('user/save_statement_settings'); ?>" enctype="multipart/form-data">
                    <?php
                    $settingsval = DB::table('client_statement_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                    ?>
                    <div class="row">
                      <div class="col-md-4">
                        <spam>Email Header Image:</spam>
                      </div>
                      <div class="col-md-8">
                      <?php
                      if($settingsval->email_header_url == '') {
                        $default_image = DB::table("email_header_images")->first();
                        if($default_image->url == "") {
                          $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                        }
                        else{
                          $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                        }
                      }
                      else {
                        $image_url = URL::to($settingsval->email_header_url.'/'.$settingsval->email_header_filename);
                      }
                      ?>
                      <img src="<?php echo $image_url; ?>" class="statement_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                      <input type="button" name="statement_dashboard_email_header_img_btn" class="common_black_button statement_dashboard_email_header_img_btn" value="Browse"> 
                      </div>
                    </div>
                    <div class="row" style="margin-top:20px">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Secondary Email CC Address:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="email" name="statement_secondary" class="form-control statement_secondary" value="<?php echo (isset($settingsval->statement_cc_email))?$settingsval->statement_cc_email:''; ?>">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Minimum Balance Level:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="text" name="minimum_bal" class="form-control minimum_bal" onkeypress="validate(event)" value="<?php echo (isset($settingsval->minimum_balance))?$settingsval->minimum_balance:''; ?>">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Statement to Include Invoices Set as Not for Statement:</label>
                      </div>
                      <div class="col-md-8">
                        <?php
                        $statement_invoice = (isset($settingsval->statement_invoice))?$settingsval->statement_invoice:1;
                        ?>
                        <input type="radio" name="statement_invoice" class="statement_invoice" id="statement_invoice_yes" value="1" <?php if($statement_invoice == 1) { echo 'checked'; } ?> style="width:0px"><label for="statement_invoice_yes" style="width:12%">Yes</label>
                        <input type="radio" name="statement_invoice" class="statement_invoice" id="statement_invoice_no" value="0" <?php if($statement_invoice == 0) { echo 'checked'; } ?> style="width:0px"><label for="statement_invoice_no" style="width:12%">No</label>
                      </div>
                    </div>
                    <br>
                    <div class="row" style="padding-bottom:8px; font-weight:bold;">
                      <div class="col-md-12">Bank Details:</div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Payments to IBAN:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="text" name="payments_to_iban" class="form-control payments_to_iban" value="<?php echo (isset($settingsval->payments_to_iban))?$settingsval->payments_to_iban:''; ?>">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Payments to BIC:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="text" name="payments_to_bic" class="form-control payments_to_bic" value="<?php echo (isset($settingsval->payments_to_bic))?$settingsval->payments_to_bic:''; ?>">
                      </div>
                    </div>
                    <br>
                    <div class="row" style="padding-bottom:8px; font-weight:bold;">
                      <div class="col-md-12">Background Image:</div>
                    </div>
                    <div class="row" style="padding-bottom:8px;">
                      <div class="col-md-12"><b><u>Note:</u></b>
                        <p>1. Kindly attach png or jpg format only</p>
                        <p>2. Kindly attach image size as 827px * 1100px</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Statement Page 1 Background:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="file" name="bg_image" class="form-control bg_image" accept="image/png, image/jpeg" value="">
                        <?php 
                        if(isset($settingsval->bg_filename)) {
                          if($settingsval->bg_filename != "")
                          {
                            echo '<h4 class="h_delete_bg1">Attachment:</h4><a class="a_delete_bg1" href="'.URL::to($settingsval->bg_url.'/'.$settingsval->bg_filename).'" download>'.$settingsval->bg_filename.'</a>
                            <a href="javascript:" class="delete_bg1" style="color:red;"><span><i class="fa fa-trash-o delete_bg1" aria-hidden="true"></i></span></a>
                            ';
                          } 
                        }
                        ?>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label style="font-weight:normal;">Statement Subsequent Page Background:</label>
                      </div>
                      <div class="col-md-8">
                        <input type="file" name="bg_image2" class="form-control bg_image2" accept="image/png, image/jpeg" value="">
                        <?php 
                        if(isset($settingsval->bg_filename1)) {
                          if($settingsval->bg_filename1 != "")
                          {
                            echo '<h4 class="h_delete_bg2">Attachment:</h4><a class="a_delete_bg2" href="'.URL::to($settingsval->bg_url1.'/'.$settingsval->bg_filename1).'" download>'.$settingsval->bg_filename1.'</a>
                            <a href="javascript:" class="delete_bg2" style="color:red;"><span><i class="fa fa-trash-o delete_bg2" aria-hidden="true"></i></span></a>';
                          } 
                        }
                        ?>
                      </div>
                    </div>
                    <br>
                    <div class="row" style="padding-bottom:8px; font-weight:bold;">
                      <div class="col-md-12">
                        <label>Email Body Message:</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <textarea name="email_body" class="form-control email_body" id="editor_1"><?php echo (isset($settingsval->email_body))?$settingsval->email_body:''; ?></textarea>
                      </div>
                    </div>
                    <div class="row" style="padding-bottom:8px; font-weight:bold;margin-top:20px;">
                      <div class="col-md-12">
                        <label>Email Signature:</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <textarea name="email_signature" class="form-control email_signature" id="editor_2"><?php echo (isset($settingsval->email_signature))?$settingsval->email_signature:''; ?></textarea>
                      </div>
                    </div>
                    <div class="modal-footer" style="clear:both">  
                        <input type="submit" class="common_black_button" value="Submit">
                    </div>
                    @csrf
                 </form>
                </div>
              </div>
              <div class="tab-pane fade" id="dashboardvat" role="tabpanel" aria-labelledby="dashboardvat-tab" style="max-height:600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <div class="col-md-6" style="margin-top:20px">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item active">
                      <a class="nav-link active" id="dashboardvatemailsettings-tab" data-toggle="tab" href="#dashboardvatemailsettingstab" role="tab" aria-controls="dashboardvatemailsettingstab" aria-selected="false">VAT Email Settings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="dashboardvatreviewsettings-tab" data-toggle="tab" href="#dashboardvatreviewsettingstab" role="tab" aria-controls="dashboardvatreviewsettingstab" aria-selected="false">VAT System VAT Review Settings</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent3">
                    <div class="tab-pane fade active in" id="dashboardvatemailsettingstab" role="tabpanel" aria-labelledby="dashboardvatemailsettings-tab">
                      <div class="admin_content_section" style="margin-top:20px">  
                      <form action="<?php echo URL::to('user/update_user_signature'); ?>" method="post" id="update_user_signature_form">
                          <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                          <?php
                          $vat_settings = DB::table('vat_settings')->where('practice_code',Session::get('user_practice_code'))->first();
                          if($vat_settings->email_header_url == '') {
                          $default_image = DB::table("email_header_images")->first();
                          if($default_image->url == "") {
                            $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                          }
                          else{
                            $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                          }
                          }
                          else {
                          $image_url = URL::to($vat_settings->email_header_url.'/'.$vat_settings->email_header_filename);
                          }
                          ?>
                          <img src="<?php echo $image_url; ?>" class="dashboard_vat_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                          <input type="button" name="dashboard_vat_email_header_img_btn" class="common_black_button dashboard_vat_email_header_img_btn" value="Browse">
                          <h4>VAT CC Email ID</h4>
                          <input id="validation-cc-email"
                               class="form-control"
                               placeholder="Enter VAT CC Email ID"
                               value="<?php echo $vat_settings->vat_cc_email; ?>"
                               name="vat_cc_email"
                               type="text"
                               required> 
                          <h4>VAT Email Signature :</h4>
                          <textarea class="form-control input-sm" id="editor_vat_review_dashboard"  name="user_signature" style="height:100px"><?php echo $vat_settings->email_signature; ?></textarea>

                          <div class="col-md-12" style="text-align:right; margin-top:20px">
                              <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
                          </div>
                          @csrf
                      </form>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="dashboardvatreviewsettingstab" role="tabpanel" aria-labelledby="dashboardvatreviewsettings-tab">
                      <div class="row">
                          <div class="col-md-12">
                            <form action="<?php echo URL::to('user/update_vat_review_settings'); ?>" method="post" id="update_vat_review_settings_form">
                              <table class="table vat_settings_table" style="width:95%;margin-top:20px">
                                <tbody>
                                  <?php
                                  $setting_details = DB::table('vat_review_settings')->where('practice_code', Session::get('user_practice_code'))->first();
                                  $period_checked = '';
                                  $breakdown_checked = '';
                                  $include_client_name_radio = '';
                                  $subject = '';
                                  $note = '';
                                  $signature = '';
                                  if($setting_details) {
                                    if($setting_details->period_end == 1){
                                      $period_checked = 'checked';
                                    }
                                    if($setting_details->breakdown == 1){
                                      $breakdown_checked = 'checked';
                                    }
                                    if($setting_details->include_client_name == 1){
                                      $include_client_name_radio = 'checked';
                                    }
                                    $subject = $setting_details->subject;
                                    $note = $setting_details->note;
                                    $signature = $setting_details->signature;
                                  }
                                  ?>
                                    <tr>
                                      <td style="width:13%;vertical-align: middle"><strong>Subject: </strong></td>
                                      <td><input type="text" class="form-control vat_review_subject" style="margin-top: 8px;" name="vat_review_subject" placeholder="Enter Subject" value="<?php echo $subject; ?>"></td>
                                      <td style="width:30%"><div class="approve_t2_div" style="float:none;text-align:left;margin-top:-11px"><input type="checkbox" class="form-control vat_review_period_end" id="vat_review_period_end" name="vat_review_period_end" value="1" <?php echo $period_checked; ?>> <label for="vat_review_period_end" class="include_vat_breakdown">Include Period End</label></div>
                                        <div class="approve_t2_div" style="float:none;text-align:left;margin-top:-17px">
                                          <input type="checkbox" class="form-control vat_review_client_name_subject" style="margin-top:-8px" id="vat_review_client_name_subject" name="vat_review_client_name_subject" value="1" <?php echo $include_client_name_radio; ?>> <label for="vat_review_client_name_subject" class="include_vat_breakdown">Include Client Name</label>
                                        </div>
                                      </td>
                                    </tr>
                                  <tr>
                                    <td style="vertical-align: middle"><strong>Notes: </strong></td>
                                    <td colspan="2">
                                      <textarea class="form-control input-sm" id="editor_vat_review_notes_dashboard"  name="vat_review_notes" style="height:100px"><?php echo $note; ?></textarea>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td style="vertical-align: middle"><strong>Breakdown: </strong></td>
                                    <td colspan="2"><input type="checkbox" class="form-control vat_review_breakdown" id="vat_review_breakdown" name="vat_review_breakdown" value="1" <?php echo $breakdown_checked; ?>> <label for="vat_review_breakdown" class="include_vat_breakdown">Include VAT Breakdown</label></td>
                                  </tr>
                                  <tr>
                                    <td style="vertical-align: middle"><strong>Signature: </strong></td>
                                    <td colspan="2">
                                        <textarea class="form-control input-sm" id="editor_vat_review_signature_dashboard"  name="vat_review_signature" style="height:100px"><?php echo $signature; ?></textarea>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" style="text-align: right">
                                      <input type="submit" name="review_setting_submit" id="review_setting_submit" class="btn common_black_button" value="Update">
                                    </td>
                                  </tr>
                                </tbody>
                              </table>
                              @csrf
                            </form>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="dashboardinv" role="tabpanel" aria-labelledby="dashboardinv-tab" style="max-height:600px;overflow-y: scroll;scrollbar-color: #3db0e6 #f5f5f5;scrollbar-width: thin;">
                <div class="col-md-8">
                  <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-top:20px">
                    <li class="nav-item active">
                      <a class="nav-link active" id="invoiceemailsettings-tab" data-toggle="tab" href="#invoiceemailsettingstab" role="tab" aria-controls="invoiceemailsettingstab" aria-selected="false">Email Settings</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="placeholdsettings-tab" data-toggle="tab" href="#placeholdsettingstab" role="tab" aria-controls="placeholdsettingstab" aria-selected="false">Placehold Settings</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContentinvoice">
                      <div class="tab-pane fade active in" id="invoiceemailsettingstab" role="tabpanel" aria-labelledby="invoiceemailsettings-tab">
                        <div class="admin_content_section" style="margin-top:20px">  
                          <form action="<?php echo URL::to('user/update_invoice_email_settings'); ?>" method="post" id="update_invoice_email_settings">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Email Header Image:</spam>
                              <?php
                              $invoice_settings = DB::table('invoice_system_settings')->where('practice_code',Session::get('user_practice_code'))->first();

                              if($invoice_settings->email_header_url == '') {
                              $default_image = DB::table("email_header_images")->first();
                              if($default_image->url == "") {
                                $image_url = URL::to('public/assets/images/easy_payroll_logo.png');
                              }
                              else{
                                $image_url = URL::to($default_image->url.'/'.$default_image->filename);
                              }
                              }
                              else {
                              $image_url = URL::to($invoice_settings->email_header_url.'/'.$invoice_settings->email_header_filename);
                              }
                              ?>
                              <img src="<?php echo $image_url; ?>" class="invoice_email_header_img" style="width:200px;margin-left:20px;margin-right:20px">
                              <input type="button" name="invoice_email_header_img_btn" class="common_black_button invoice_email_header_img_btn" value="Browse">

                              <div class="col-md-12 padding_00" style="margin-top:20px">
                              <spam style="font-size: 18px;font-weight: 500;line-height: 1.1;">Subject:</spam>
                              <?php
                              $checked_subject = '';
                              if($invoice_settings->include_practice == 1) {
                                $checked_subject = 'checked';
                              }
                              ?>
                              <input type="checkbox" name="invoice_include_practice" class="invoice_include_practice" id="invoice_include_practice" value="1" <?php echo $checked_subject; ?>> <label for="invoice_include_practice" style="width:400px;margin-left:20px;margin-right:20px">Include Practice Name in Subject</label>
                              </div>

                              <h4 style="margin-top:70px">Invoice CC Email ID</h4>
                              <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Invoice CC Email ID"
                                   value="<?php echo $invoice_settings->invoice_cc_email; ?>"
                                   name="invoice_cc_email"
                                   type="text"
                                   required> 
                              <h4 style="margin-top:25px">Email Signature :</h4>
                              <textarea class="form-control input-sm" id="editor_inv_review"  name="user_signature" style="height:100px"><?php echo $invoice_settings->email_signature; ?></textarea>
                              
                              <div class="col-md-12" style="text-align:right; margin-top:20px">
                                  <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">
                              </div>
                              @csrf
                          </form>
                        </div>
                      </div>
                      <div class="tab-pane fade" id="placeholdsettingstab" role="tabpanel" aria-labelledby="placeholdsettings-tab">
                          <?php
                          $settings_details = DB::table('settings')->where('source','build_invoice')->where('practice_code',Session::get('user_practice_code'))->first();
                          if($settings_details)
                          {
                            $settings = unserialize($settings_details->settings);

                            $offset_lines = ($settings['offset_lines'] != "") ? $settings['offset_lines'] : 6;
                            $plus_invoice_heading = ($settings['plus_invoice_heading'] != "") ? $settings['plus_invoice_heading'] : 'Invoice';
                            $minus_invoice_heading = ($settings['minus_invoice_heading'] != "") ? $settings['minus_invoice_heading'] : 'Credit Note';
                            $suboffset_lines = ($settings['suboffset_lines'] != "") ? $settings['suboffset_lines'] : 7;
                            $iban_field = ($settings['iban_field'] != "") ? $settings['iban_field'] : '5465623131';
                            $bic_field = ($settings['bic_field'] != "") ? $settings['bic_field'] : '545JHJDJ4544';
                            $first_invoice_number = ($settings['first_invoice_number'] != "") ? $settings['first_invoice_number'] : '';
                            $left_margin = ($settings['left_margin'] != "") ? $settings['left_margin'] : '95';
                            $top_margin = ($settings['top_margin'] != "") ? $settings['top_margin'] : '200';

                            $inv_to_text = ($settings['inv_to_text'] != "") ? $settings['inv_to_text'] : 'To';
                            $inv_date_location_text = ($settings['inv_date_location_text'] != "") ? $settings['inv_date_location_text'] : 'Date';
                            $inv_iban_text = ($settings['inv_iban_text'] != "") ? $settings['inv_iban_text'] : 'IBAN';
                            $inv_bic_text = ($settings['inv_bic_text'] != "") ? $settings['inv_bic_text'] : 'BIC';
                            $inv_no_text = ($settings['inv_no_text'] != "") ? $settings['inv_no_text'] : 'Invoice';
                            $client_id_text = ($settings['client_id_text'] != "") ? $settings['client_id_text'] : 'Client ID';
                            $net_text = ($settings['net_text'] != "") ? $settings['net_text'] : 'Net';
                            $vat_text = ($settings['vat_text'] != "") ? $settings['vat_text'] : 'VAT';
                            $gross_text = ($settings['gross_text'] != "") ? $settings['gross_text'] : 'Gross';

                            $inv_to_offset = ($settings['inv_to_offset'] != "") ? $settings['inv_to_offset'] : 1;
                            $client_id_offset = ($settings['client_id_offset'] != "") ? $settings['client_id_offset'] : 1;
                            $inv_no_offset = ($settings['inv_no_offset'] != "") ? $settings['inv_no_offset'] : 2;
                            $inv_date_location_offset = ($settings['inv_date_location_offset'] != "") ? $settings['inv_date_location_offset'] : 3;
                            $inv_iban_offset = ($settings['inv_iban_offset'] != "") ? $settings['inv_iban_offset'] : 4;
                            $inv_bic_offset = ($settings['inv_bic_offset'] != "") ? $settings['inv_bic_offset'] : 5;
                            $net_offset = ($settings['net_offset'] != "") ? $settings['net_offset'] : 32;
                            $vat_offset = ($settings['vat_offset'] != "") ? $settings['vat_offset'] : 33;
                            $gross_offset = ($settings['gross_offset'] != "") ? $settings['gross_offset'] : 34;

                            $inv_to_left_offset = ($settings['inv_to_left_offset'] != "") ? $settings['inv_to_left_offset'] : 1;
                            $client_id_left_offset = ($settings['client_id_left_offset'] != "") ? $settings['client_id_left_offset'] : 4;
                            $inv_no_left_offset = ($settings['inv_no_left_offset'] != "") ? $settings['inv_no_left_offset'] : 4;
                            $inv_date_location_left_offset = ($settings['inv_date_location_left_offset'] != "") ? $settings['inv_date_location_left_offset'] : 4;
                            $inv_iban_left_offset = ($settings['inv_iban_left_offset'] != "") ? $settings['inv_iban_left_offset'] : 4;
                            $inv_bic_left_offset = ($settings['inv_bic_left_offset'] != "") ? $settings['inv_bic_left_offset'] : 4;
                            $net_left_offset = ($settings['net_left_offset'] != "") ? $settings['net_left_offset'] : 4;
                            $vat_left_offset = ($settings['vat_left_offset'] != "") ? $settings['vat_left_offset'] : 4;
                            $gross_left_offset = ($settings['gross_left_offset'] != "") ? $settings['gross_left_offset'] : 4;
                          }
                          else{
                            $offset_lines = 6;
                            $plus_invoice_heading = 'Invoice';
                            $minus_invoice_heading = 'Credit Note';
                            $suboffset_lines = 7;
                            $iban_field = '5465623131';
                            $bic_field = '545JHJDJ4544';
                            $first_invoice_number = '';
                            $left_margin = '95';
                            $top_margin = '200';

                            $inv_to_text = 'To';
                            $inv_date_location_text = 'Date';
                            $inv_iban_text = 'IBAN';
                            $inv_bic_text = 'BIC';
                            $inv_no_text = 'Invoice';
                            $client_id_text = 'Client ID';
                            $net_text = 'Net';
                            $vat_text = 'VAT';
                            $gross_text = 'Gross';

                            $inv_to_offset = 1;
                            $client_id_offset = 1;
                            $inv_no_offset = 2;
                            $inv_date_location_offset = 3;
                            $inv_iban_offset = 4;
                            $inv_bic_offset = 5;
                            $net_offset = 32;
                            $vat_offset = 33;
                            $gross_offset = 34;

                            $inv_to_left_offset = 1;
                            $client_id_left_offset = 4;
                            $inv_no_left_offset = 4;
                            $inv_date_location_left_offset = 4;
                            $inv_iban_left_offset = 4;
                            $inv_bic_left_offset = 4;
                            $net_left_offset = 4;
                            $vat_left_offset = 4;
                            $gross_left_offset = 4;
                          }
                          ?>
                          <div class="col-md-5">
                            <table style="width:100%" id="settings_table">
                                <tr>
                                    <td style="width: 52%;"><strong>Top Margin (In Pixels):</strong></td>
                                    <td style="width:35%"><input type="text" name="top_margin" class="form-control top_margin" value="<?php echo $top_margin; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                    <td><strong>Left Margin (In Pixels):</strong></td>
                                    <td><input type="text" name="left_margin" class="form-control left_margin" value="<?php echo $left_margin; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                    <td><strong>Number of offset lines:</strong></td>
                                    <td><input type="text" name="offset_lines" class="form-control offset_lines" value="<?php echo $offset_lines; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                    <td><strong>Number of Sub offset lines:</strong></td>
                                    <td><input type="text" name="suboffset_lines" class="form-control suboffset_lines" value="<?php echo $suboffset_lines; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                    <td><strong>+Invoice Heading:</strong></td>
                                    <td><input type="text" name="plus_invoice_heading" class="form-control plus_invoice_heading" value="<?php echo $plus_invoice_heading; ?>"></td>
                                </tr>
                                <tr>
                                    <td><strong>-Invoice Heading:</strong></td>
                                    <td><input type="text" name="minus_invoice_heading" class="form-control minus_invoice_heading" value="<?php echo $minus_invoice_heading; ?>"></td>
                                </tr>
                                <tr>
                                    <td><strong>IBAN:</strong></td>
                                    <td><input type="text" name="iban_field" class="form-control iban_field" value="<?php echo $iban_field; ?>"></td>
                                </tr>
                                <tr>
                                    <td><strong>BIC:</strong></td>
                                    <td><input type="text" name="bic_field" class="form-control bic_field" value="<?php echo $bic_field; ?>"></td>
                                </tr>
                                <tr>
                                    <td><strong>First Invoice Number:</strong></td>
                                    <td><input type="text" name="first_invoice_number" class="form-control first_invoice_number" value="<?php echo $first_invoice_number; ?>"></td>
                                </tr>
                            </table>
                          </div>
                          <div class="col-md-7">
                            <table style="width:100%" id="settings2_table">
                              <thead>
                                <th style="width: 36%;">Items</th>
                                <th style="width:15%">Item <br/>Text</th>
                                <th style="width:16%">Offset <br/>Lines</th>
                                <th style="width:18%">Left Margin <br/>Offset</th>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><strong>Invoice To Location:</strong></td>
                                  <td><input type="text" name="inv_to_text" class="form-control inv_to_text" value="<?php echo $inv_to_text; ?>"></td>
                                  <td><input type="text" name="inv_to_offset" class="form-control inv_to_offset" value="<?php echo $inv_to_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="inv_to_left_offset" class="form-control inv_to_left_offset" value="<?php echo $inv_to_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>Invoice Date Location:</strong></td>
                                  <td><input type="text" name="inv_date_location_text" class="form-control inv_date_location_text" value="<?php echo $inv_date_location_text; ?>"></td>
                                  <td><input type="text" name="inv_date_location_offset" class="form-control inv_date_location_offset" value="<?php echo $inv_date_location_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="inv_date_location_left_offset" class="form-control inv_date_location_left_offset" value="<?php echo $inv_date_location_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>IBAN Location:</strong></td>
                                  <td><input type="text" name="inv_iban_text" class="form-control inv_iban_text" value="<?php echo $inv_iban_text; ?>"></td>
                                  <td><input type="text" name="inv_iban_offset" class="form-control inv_iban_offset" value="<?php echo $inv_iban_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="inv_iban_left_offset" class="form-control inv_iban_left_offset" value="<?php echo $inv_iban_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>BIC Location:</strong></td>
                                  <td><input type="text" name="inv_bic_text" class="form-control inv_bic_text" value="<?php echo $inv_bic_text; ?>"></td>
                                  <td><input type="text" name="inv_bic_offset" class="form-control inv_bic_offset" value="<?php echo $inv_bic_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="inv_bic_left_offset" class="form-control inv_bic_left_offset" value="<?php echo $inv_bic_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>Invoice Number Location:</strong></td>
                                  <td><input type="text" name="inv_no_text" class="form-control inv_no_text" value="<?php echo $inv_no_text; ?>"></td>
                                  <td><input type="text" name="inv_no_offset" class="form-control inv_no_offset" value="<?php echo $inv_no_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="inv_no_left_offset" class="form-control inv_no_left_offset" value="<?php echo $inv_no_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>Client ID Location:</strong></td>
                                  <td><input type="text" name="client_id_text" class="form-control client_id_text" value="<?php echo $client_id_text; ?>"></td>
                                  <td><input type="text" name="client_id_offset" class="form-control client_id_offset" value="<?php echo $client_id_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="client_id_left_offset" class="form-control client_id_left_offset" value="<?php echo $client_id_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>Net Location:</strong></td>
                                  <td><input type="text" name="net_text" class="form-control net_text" value="<?php echo $net_text; ?>"></td>
                                  <td><input type="text" name="net_offset" class="form-control net_offset" value="<?php echo $net_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="net_left_offset" class="form-control net_left_offset" value="<?php echo $net_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>VAT Location:</strong></td>
                                  <td><input type="text" name="vat_text" class="form-control vat_text" value="<?php echo $vat_text; ?>"></td>
                                  <td><input type="text" name="vat_offset" class="form-control vat_offset" value="<?php echo $vat_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="vat_left_offset" class="form-control vat_left_offset" value="<?php echo $vat_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                                <tr>
                                  <td><strong>Gross Location:</strong></td>
                                  <td><input type="text" name="gross_text" class="form-control gross_text" value="<?php echo $gross_text; ?>"></td>
                                  <td><input type="text" name="gross_offset" class="form-control gross_offset" value="<?php echo $gross_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                  <td><input type="text" name="gross_left_offset" class="form-control gross_left_offset" value="<?php echo $gross_left_offset; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                          <br/>
                          <input type="button" class="common_black_button save_build_invoice_settings" id="save_build_invoice_settings" value="Save Settings">
                      </div>
                  </div>
                </div>
              </div>
              </div>
            </div> 
          </div>
          <div class="modal-footer" style="clear:both">
            
          </div>
          
        </div>
  </div>
</div>
<div class="modal fade dashboard_change_header_image" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" style="width:30%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Upload Image</h4>
        
      </div>
      <div class="modal-body">

          <form action="<?php echo URL::to('user/edit_dashboard_header_image'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="ImageUploadEmailDashboard" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:250px; width:100%; float:left">
                  <input name="_token" type="hidden" value="">
                  <input type="hidden" name="email_header_module" id="email_header_module" value="">
              @csrf
          </form>
      </div>
      <div class="modal-footer">
        <p style="font-size: 18px;font-weight: 500;margin-top:16px;float: left;line-height: 25px;text-align:left">NOTE: The Image size can only be between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>
      </div>
    </div>
  </div>
</div>
<script>
CKEDITOR.replace('editor_vat_review_dashboard',
{
height: '150px',
enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
});
CKEDITOR.replace('editor_vat_review_notes_dashboard',
{
height: '80px',
enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
});
CKEDITOR.replace('editor_vat_review_signature_dashboard',
{
height: '80px',
enterMode: CKEDITOR.ENTER_BR,
      shiftEnterMode: CKEDITOR.ENTER_P,
      autoParagraph: false,
      entities: false,
      contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
});
CKEDITOR.replace('editor_dashboard_999',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });

 CKEDITOR.replace('editor_dashboard_9999',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });

  CKEDITOR.replace('editor_dashboard_distribute',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });
    CKEDITOR.replace('editor_dashboard_croard',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });
    CKEDITOR.replace('editor_dashboard_keydocs',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });

    CKEDITOR.replace('editoramlsignature',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    CKEDITOR.replace('editoramlbody',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    CKEDITOR.replace('editorcarsignature',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    CKEDITOR.replace('editor_inv_review',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });

$(window).click(function(e) {
  if($(e.target).hasClass('invoice_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Invoice Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("invoice");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('keydocs_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Keydocs Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("keydocs");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('yearend_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the yearend Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("yearend");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('messageus_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the MessageUs Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("messageus");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('dashboard_vat_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the VAT Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("vat");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('pms_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Payroll Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("pms");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('paye_mrs_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Paye M.R.S Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("payemrs");
      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('croard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the CRO ARD Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("croard");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('infile_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Infile Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("infile");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('aml_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the AML Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("aml");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('taskmanager_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the taskmanager Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("taskmanager");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('request_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the request Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("request");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('car_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Client Account Review Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("car");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('statement_dashboard_email_header_img_btn')) {
    var r = confirm("Are you sure you want to change the Client Statement Email Header Image?");
    if(r) {
      $(".dashboard_change_header_image").modal("show");
      $("#email_header_module").val("statement");

      Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload.");
    }
  }
  if($(e.target).hasClass('dashboard_settings_email'))
  {
    $(".dashboard_email_settings_modal").modal("show");
  }
  if($(e.target).hasClass('save_build_invoice_settings'))
  {
    var offset_lines = $(".offset_lines").val();
    var plus_invoice_heading = $(".plus_invoice_heading").val();
    var minus_invoice_heading = $(".minus_invoice_heading").val();
    var suboffset_lines = $(".suboffset_lines").val();
    var iban_field = $(".iban_field").val();
    var bic_field = $(".bic_field").val();
    var first_invoice_number = $(".first_invoice_number").val();
    var left_margin = $(".left_margin").val();
    var top_margin = $(".top_margin").val();

    var inv_to_text = $(".inv_to_text").val();
    var inv_date_location_text = $(".inv_date_location_text").val();
    var inv_iban_text = $(".inv_iban_text").val();
    var inv_bic_text = $(".inv_bic_text").val();
    var inv_no_text = $(".inv_no_text").val();
    var client_id_text = $(".client_id_text").val();
    var net_text = $(".net_text").val();
    var vat_text = $(".vat_text").val();
    var gross_text = $(".gross_text").val();

    var inv_to_offset = $(".inv_to_offset").val();
    var client_id_offset = $(".client_id_offset").val();
    var inv_no_offset = $(".inv_no_offset").val();
    var inv_date_location_offset = $(".inv_date_location_offset").val();
    var inv_iban_offset = $(".inv_iban_offset").val();
    var inv_bic_offset = $(".inv_bic_offset").val();
    var net_offset = $(".net_offset").val();
    var vat_offset = $(".vat_offset").val();
    var gross_offset = $(".gross_offset").val();

    var inv_to_left_offset = $(".inv_to_left_offset").val();
    var client_id_left_offset = $(".client_id_left_offset").val();
    var inv_no_left_offset = $(".inv_no_left_offset").val();
    var inv_date_location_left_offset = $(".inv_date_location_left_offset").val();
    var inv_iban_left_offset = $(".inv_iban_left_offset").val();
    var inv_bic_left_offset = $(".inv_bic_left_offset").val();
    var net_left_offset = $(".net_left_offset").val();
    var vat_left_offset = $(".vat_left_offset").val();
    var gross_left_offset = $(".gross_left_offset").val();

    if(offset_lines == "") { alert("Please enter the offset_lines"); return false; }
    if(plus_invoice_heading == "") { alert("Please enter the plus_invoice_heading"); return false; }
    if(minus_invoice_heading == "") { alert("Please enter the minus_invoice_heading"); return false; }
    if(suboffset_lines == "") { alert("Please enter the suboffset_lines"); return false; }
    if(iban_field == "") { alert("Please enter the iban_field"); return false; }
    if(bic_field == "") { alert("Please enter the bic_field"); return false; }
    if(first_invoice_number == "") { alert("Please enter the first_invoice_number"); return false; }
    if(left_margin == "") { alert("Please enter the left_margin"); return false; }
    if(top_margin == "") { alert("Please enter the top_margin"); return false; }
    if(inv_to_text == "") { alert("Please enter the inv_to_text"); return false; }
    if(inv_date_location_text == "") { alert("Please enter the inv_date_location_text"); return false; }
    if(inv_iban_text == "") { alert("Please enter the inv_iban_text"); return false; }
    if(inv_bic_text == "") { alert("Please enter the inv_bic_text"); return false; }
    if(inv_no_text == "") { alert("Please enter the inv_no_text"); return false; }
    if(client_id_text == "") { alert("Please enter the client_id_text"); return false; }
    if(net_text == "") { alert("Please enter the net_text"); return false; }
    if(vat_text == "") { alert("Please enter the vat_text"); return false; }
    if(gross_text == "") { alert("Please enter the gross_text"); return false; }
    if(inv_to_offset == "") { alert("Please enter the inv_to_offset"); return false; }
    if(client_id_offset == "") { alert("Please enter the client_id_offset"); return false; }
    if(inv_no_offset == "") { alert("Please enter the inv_no_offset"); return false; }
    if(inv_date_location_offset == "") { alert("Please enter the inv_date_location_offset"); return false; }
    if(inv_iban_offset == "") { alert("Please enter the inv_iban_offset"); return false; }
    if(inv_bic_offset == "") { alert("Please enter the inv_bic_offset"); return false; }
    if(net_offset == "") { alert("Please enter the net_offset"); return false; }
    if(vat_offset == "") { alert("Please enter the vat_offset"); return false; }
    if(gross_offset == "") { alert("Please enter the gross_offset"); return false; }
    if(inv_to_left_offset == "") { alert("Please enter the inv_to_left_offset"); return false; }
    if(client_id_left_offset == "") { alert("Please enter the client_id_left_offset"); return false; }
    if(inv_no_left_offset == "") { alert("Please enter the inv_no_left_offset"); return false; }
    if(inv_date_location_left_offset == "") { alert("Please enter the inv_date_location_left_offset"); return false; }
    if(inv_iban_left_offset == "") { alert("Please enter the inv_iban_left_offset"); return false; }
    if(inv_bic_left_offset == "") { alert("Please enter the inv_bic_left_offset"); return false; }
    if(net_left_offset == "") { alert("Please enter the net_left_offset"); return false; }
    if(vat_left_offset == "") { alert("Please enter the vat_left_offset"); return false; }
    if(gross_left_offset == "") { alert("Please enter the gross_left_offset"); return false; }

    if(parseInt(suboffset_lines) <= parseInt(offset_lines)) { alert("The Number of Sub Offset Lines should be Greater than the Offset Lines"); return false; }
    if(parseInt(inv_to_offset) >= parseInt(offset_lines)) { alert("The Invoice To Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(client_id_offset) >= parseInt(offset_lines)) { alert("The Client ID Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_no_offset) >= parseInt(offset_lines)) { alert("The Invoice Number Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_date_location_offset) >= parseInt(offset_lines)) { alert("The Date Location Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_iban_offset) >= parseInt(offset_lines)) { alert("The IBAN Offset should be Lesser than the Offset Lines"); return false; }
    if(parseInt(inv_bic_offset) >= parseInt(offset_lines)) { alert("The BIC Offset should be Lesser than the Offset Lines"); return false; }

    var datavalues = [];

    datavalues.push({offset_lines: $(".offset_lines").val() });
    datavalues.push({plus_invoice_heading: $(".plus_invoice_heading").val() });
    datavalues.push({minus_invoice_heading: $(".minus_invoice_heading").val() });
    datavalues.push({suboffset_lines: $(".suboffset_lines").val() });
    datavalues.push({iban_field: $(".iban_field").val() });
    datavalues.push({bic_field: $(".bic_field").val() });
    datavalues.push({first_invoice_number: $(".first_invoice_number").val() });
    datavalues.push({left_margin: $(".left_margin").val() });
    datavalues.push({top_margin: $(".top_margin").val() });
    datavalues.push({inv_to_text: $(".inv_to_text").val() });
    datavalues.push({inv_date_location_text: $(".inv_date_location_text").val() });
    datavalues.push({inv_iban_text: $(".inv_iban_text").val() });
    datavalues.push({inv_bic_text: $(".inv_bic_text").val() });
    datavalues.push({inv_no_text: $(".inv_no_text").val() });
    datavalues.push({client_id_text: $(".client_id_text").val() });
    datavalues.push({net_text: $(".net_text").val() });
    datavalues.push({vat_text: $(".vat_text").val() });
    datavalues.push({gross_text: $(".gross_text").val() });
    datavalues.push({inv_to_offset: $(".inv_to_offset").val() });
    datavalues.push({client_id_offset: $(".client_id_offset").val() });
    datavalues.push({inv_no_offset: $(".inv_no_offset").val() });
    datavalues.push({inv_date_location_offset: $(".inv_date_location_offset").val() });
    datavalues.push({inv_iban_offset: $(".inv_iban_offset").val() });
    datavalues.push({inv_bic_offset: $(".inv_bic_offset").val() });
    datavalues.push({net_offset: $(".net_offset").val() });
    datavalues.push({vat_offset: $(".vat_offset").val() });
    datavalues.push({gross_offset: $(".gross_offset").val() });
    datavalues.push({inv_to_left_offset: $(".inv_to_left_offset").val() });
    datavalues.push({client_id_left_offset: $(".client_id_left_offset").val() });
    datavalues.push({inv_no_left_offset: $(".inv_no_left_offset").val() });
    datavalues.push({inv_date_location_left_offset: $(".inv_date_location_left_offset").val() });
    datavalues.push({inv_iban_left_offset: $(".inv_iban_left_offset").val() });
    datavalues.push({inv_bic_left_offset: $(".inv_bic_left_offset").val() });
    datavalues.push({net_left_offset: $(".net_left_offset").val() });
    datavalues.push({vat_left_offset: $(".vat_left_offset").val() });
    datavalues.push({gross_left_offset: $(".gross_left_offset").val() });

    $.ajax({
        type: "POST",
        url: "<?php echo URL::to('user/update_build_invoice_setings'); ?>",
        data: {datas:JSON.stringify(datavalues)},
        success: function(result){
          $(".build_invoice_settings_overlay").modal("hide");
          var countval = $(".highlight_inv").length;
          if(countval > 0){
            $(".highlight_inv").eq(0).trigger("click");
          }
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600"> Settings have been updated successfully.</p> <p style="text-align:center;margin-top:20px;"><a href="javascript:" class="common_black_button ok_proceed">Ok</a></p>',fixed:true,width:"500px"});
        }
    });
  }
});
$.ajaxSetup({async:false});
Dropzone.options.ImageUploadEmailDashboard = {
    maxFiles: 1,
    acceptedFiles: ".png",
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            if(obj.error == 1) {
              $("body").removeClass("loading");
              Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
            }
            else{
              var modulename = $("#email_header_module").val();
              if(modulename == "pms") {
                $(".pms_dashboard_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "croard") {
                $(".croard_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRO ARD Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "infile") {
                $(".infile_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Infile Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "keydocs") {
                $(".keydocs_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Keydocs Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "payemrs") {
                $(".paye_mrs_dashboard_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Paye M.R.S Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "yearend") {
                $(".yearend_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Yearend Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "messageus") {
                $(".messageus_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Messageus Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "aml") {
                $(".aml_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">AML Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "taskmanager") {
                $(".taskmanager_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Taskmanager Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "request") {
                $(".request_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRS Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "car") {
                $(".car_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Account Review Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "statement") {
                $(".statement_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Statement Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "vat") {
                $(".dashboard_vat_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">VAT System Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "invoice") {
                $(".invoice_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Invoice Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              $("body").removeClass("loading");
              $(".dashboard_change_header_image").modal("hide");
              Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
              $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            }
        });
        this.on("complete", function (file, response) {
          var obj = jQuery.parseJSON(response);
          if(obj.error == 1) {
            $("body").removeClass("loading");
            $(".dashboard_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Alert! The width and height of the uploaded image is not as per the allowed dimensions. Please make sure the image is between 55 px and 200 px WIDTH and between 51 px and 55 px HEIGHT</p>',fixed:true,width:"800px"});
          }
          else{
            var modulename = $("#email_header_module").val();
            if(modulename == "pms") {
              $(".pms_dashboard_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Payroll Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "croard") {
              $(".croard_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRO ARD Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "infile") {
              $(".infile_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Infile Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "keydocs") {
              $(".keydocs_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Keydocs Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "payemrs") {
              $(".paye_mrs_dashboard_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Paye M.R.S Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "yearend") {
              $(".yearend_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Yearend Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "messageus") {
              $(".messageus_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Messageus Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "aml") {
              $(".aml_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">AML Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "taskmanager") {
              $(".taskmanager_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Taskmanager Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "request") {
              $(".request_email_header_img").attr("src",obj.image);
              $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">CRS Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
            }
            else if(modulename == "car") {
                $(".car_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Account Review Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "statement") {
                $(".statement_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">Client Statement Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
              else if(modulename == "vat") {
                $(".dashboard_vat_email_header_img").attr("src",obj.image);
                $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600">VAT System Email Header Image has been Changed Successfully.</p>',fixed:true,width:"800px"});
              }
            $("body").removeClass("loading");
            $(".dashboard_change_header_image").modal("hide");
            Dropzone.forElement("#ImageUploadEmailDashboard").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the PNG file OR just drop a PNG file here to upload");
          }
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
    },
};
</script>