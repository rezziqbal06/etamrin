<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- If you delete this meta tag, Half Life 3 will never be released. -->
		<meta name="viewport" content="width=device-width" />

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Reset Password Kamu</title>

		<link rel="stylesheet" type="text/css" href="https://cdn.thecloudalert.com/assets/css/email.min.css" />

	</head>

	<body bgcolor="#FFFFFF">
		<!-- HEADER -->
		<table class="head-wrap" style="width: 100%;">
			<tr>
				<td class="header container" >
					<table style="width: 100%;">
						<tr>
							<td style="text-align: center;"><img src="{{site_logo}}" /></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<!-- /HEADER -->

		<!-- BODY -->
		<table class="body-wrap">
			<tr>
				<td>&nbsp;</td>
				<td class="container" bgcolor="#FFFFFF">
					<div class="content">
						<table style="width: 100%;">
							<tr>
								<td style="text-align: center;">
									<h1><b>Permintaan Reset Password</b></h1>
									<br />
									<br />
									<h3><b>Halo {{fnama}},</b></h3>
									<p class="lead">
										Kami telah menerima permintaan untuk reset password akun {{app_name}}.
										Buka link dibawah ini untuk mereset password.
									</p>
                  <p>&nbsp;</p>
									<table class="" border="1" cellpadding="1" cellspacing="2" width="100%" style="border-style: dashed;">
										<tbody>
											<tr>
												<td style="text-align:center">
													<a href="{{reset_link}}">{{reset_link}}</a>
												</td>
											</tr>
										</tbody>
									</table>
									<br />
									<p class="callout">
										Apabila link tidak bisa diklik, silakan copy-paste link tersebut dan langsung buka linknya di browser.
										<br />
									</p>
									<br />
									<br />
                  <p><b>Abaikan email ini jika kamu tidak pernah meminta untuk reset password. Untuk pertanyaan, silakan hubungi <a href="mailto:{{cs_email}}">{{cs_email}}</a>.</p>
									<br />
									<br />
									<p>Best Regards,</p>
									<p>HR Recruitment and Development</p>
                  <p>{{company_name}}</p>
									<br />
									<br />
									<br />
									<hr>
									<p style="color: #3c3c3c; font-size: smaller"><em>Apabila link tidak bisa diklik / dibuka, silakan <b>Copy</b> <b>Paste</b> langsung di browser anda.</em></p>
									<p style="color: #3c3c3c; font-size: smaller"><em>Supaya tidak masuk ke spam, silakan tambahkan email: {{email_dari}} ini ke kontak anda.</em></p>
								</td>
							</tr>
						</table>
						<p style="text-align: center; font-size: small; color: #3c3c3c; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
					</div><!-- /content -->
				</td>
				<td>&nbsp;</td>
			</tr>
		</table><!-- /BODY -->

		<!-- FOOTER -->
		<table class="footer-wrap">
			<tr>
				<td>&nbsp;</td>
				<td class="container">
					<!-- content -->
					<!-- end content -->
				</td>
				<td>&nbsp;</td>
			</tr>
		</table><!-- /FOOTER -->

	</body>
</html>
