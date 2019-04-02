<!-- <!DOCTYPE html>
<html>
<head>
	<title>Facebook Login</title>
	<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '108283983076134',
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


 
</script>
</head>
<body>
	<?php if ($this->session->userdata('access_token')) {
		# code...
	}?>
<button class="btn facebbok" type="button" onclick="location.href = '<?php echo base_url()?>sociallogin/facebook';"> Facebook <i class="fa fa-facebook-square pull-right" aria-hidden="true"></i></button>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Facebook Log in</title>

	<style>
		body {
			padding: 0;
			margin: 0;
			font-family: Helvetica, Sans-serif;
			font-size: 16px;
			color: #333;
			line-height: 1.5;
		}

		.wrapper {
			width: 800px;
			margin: 60px auto;
			border: 1px solid #eee;
			background: #fcfcfc;
			padding: 0 20px 20px;
			box-shadow: 1px 1px 2px rgba(0,0,0,0.1);
		}

		h1, h3 {
			text-align: center;
		}

		.examples a {
			border: medium none;
			background: none repeat scroll 0% 0% #eee;
			color: #333;
			font-size: 24px;
			padding: 40px 20px;
			margin: 20px 2%;
			cursor: pointer;
			text-decoration: none;
			border: 1px solid #e9e9e9;
			width: 85.47%;
			display: inline-block;
			text-align: center;

			transition: background .6s ease;
		}
		.examples a {
			border: medium none;
			background: none repeat scroll 0% 0% #eee;
			color: #333;
			font-size: 24px;
			padding: 40px 20px;
			margin: 20px 2%;
			cursor: pointer;
			text-decoration: none;
			border: 1px solid #e9e9e9;
			width: 40.47%;
			display: inline-block;
			text-align: center;

			transition: background .6s ease;
		}

		.examples a:hover {
			background: #ccc;
		}
	</style>
</head>
<body>

<?php if (!$this->session->userdata('user_id')) {?>
<div class="wrapper">
	<div class="examples">
		<a href="<?php echo base_url()?>sociallogin/facebook" class="web">Login to Facebook</a>
	</div>
</div>
<?php }else
{?>
<div class="wrapper">
	<div class="examples2">
		Name : <?php echo $userdata['name']?>
	</div>
	<div class="examples2">
		Email : <?php echo $userdata['email'];?>
	</div>
	<div class="examples2">
		<img src="<?php echo base_url().$userdata['pic']?>">
	</div>
	<div class="examples">
		<a href="<?php echo base_url()?>sociallogin/logout" class="web">Logout from Facebook</a>
	</div>
</div>
<?php }?>


</body>
</html>
