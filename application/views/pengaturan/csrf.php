<?php
if ($this->input->post('token') != $this->session->csrf_token) {
    $this->session->unset_userdata('csrf_token');
    $this->session->set_flashdata('message', "Swal.fire(
                                'Failed',
                                'Mau Ngehack ya, Hehehe!!',
                                'error'
                            )");
    redirect('admin/MDriver');
}
else{

}

die("<center><h1>Mau ngehack ya hehe!!!</h1></center>");

$ses = array(
    'csrf_token' => hash('sha1',time())
);
$this->session->set_userdata($ses);
$this->session->userdata('user_type');

var_dump($data);

?>

<input type="hidden" name="token" value="<?= $this->session->csrf_token;?>">
<div data-backdrop="static" data-keyboard="false" aria-hidden="true">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vertical Dark Timeline</title>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    </head>
    <body>
        <div class="container">
            <div class="timeline">
                <div class="timeline-container primary">
                    <div class="timeline-icon">
                        <i class="far fa-grin-wink"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Primary</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">1 Hours Ago</p>
                    </div>
                </div>
                <div class="timeline-container danger">
                    <div class="timeline-icon">
                        <i class="far fa-grin-hearts"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Danger</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">2 Hours Ago</p>
                    </div>
                </div>
                <div class="timeline-container success">
                    <div class="timeline-icon">
                        <i class="far fa-grin-tears"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Success</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">6 Hours Ago</p>
                    </div>
                </div>
                <div class="timeline-container warning">
                    <div class="timeline-icon">
                        <i class="far fa-grimace"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Warning</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">1 Day Ago</p>
                    </div>
                </div>
                <div class="timeline-container">
                    <div class="timeline-icon">
                        <i class="far fa-grin-beam-sweat"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Secondary</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">3 Days Ago</p>
                    </div>
                </div>
                <div class="timeline-container info">
                    <div class="timeline-icon">
                        <i class="far fa-grin"></i>
                    </div>
                    <div class="timeline-body">
                        <h4 class="timeline-title"><span class="badge">Info</span></h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam necessitatibus numquam earum ipsa fugiat veniam suscipit, officiis repudiandae, eum recusandae neque dignissimos. Cum fugit laboriosam culpa, repellendus esse commodi deserunt.</p>
                        <p class="timeline-subtitle">4 Days Ago</p>
                    </div>
                </div>
            </div>
        </div>
    </body>
       <section class="author">
        <p>Created by:  <a href="https://responsivemart.com" target="_blank" title="ResponsiveMart">ResponsiveMart</a></p>
        <a href="https://responsivemart.com/product/vertical-dark-timeline/" target="_blank" class="btn">Download Source Code</a>
    </section>


    "
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>A PHP Error was encountered</h4>

<p>Severity: Warning</p>
<p>Message:  Undefined property: Order::$security_function</p>
<p>Filename: admin/Order.php</p>
<p>Line Number: 87</p>


	<p>Backtrace:</p>
	
		
	
		
	
		
			<p style="margin-left:10px">
			File: C:\xampp\htdocs\SystemCargo\admin\application\controllers\admin\Order.php<br />
			Line: 87<br />
			Function: _error_handler			</p>

		
	
		
	
		
			<p style="margin-left:10px">
			File: C:\xampp\htdocs\SystemCargo\admin\index.php<br />
			Line: 315<br />
			Function: require_once			</p>

		
	

</div>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">

<h4>An uncaught Exception was encountered</h4>

<p>Type: Error</p>
<p>Message: Call to a member function permissions() on null</p>
<p>Filename: C:\xampp\htdocs\SystemCargo\admin\application\controllers\admin\Order.php</p>
<p>Line Number: 87</p>


	<p>Backtrace:</p>
	
		
	
		
			<p style="margin-left:10px">
			File: C:\xampp\htdocs\SystemCargo\admin\index.php<br />
			Line: 315<br />
			Function: require_once			</p>
		
	

</div>"