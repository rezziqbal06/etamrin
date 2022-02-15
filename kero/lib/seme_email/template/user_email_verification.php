<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- If you delete this meta tag, Half Life 3 will never be released. -->
		<meta name="viewport" content="width=device-width" />

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Kode Email Verifikasi Pendaftaran Calon Karyawan {{app_name}}</title>

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
									<h1><b>Kode Email Verifikasi Pendaftaran Calon Karyawan {{app_name}}</b></h1>
									<br />
									<br />
									<h3>Halo {{fnama}},</h3>
									<p class="lead">
										Terimakasih sudah mendaftar di <b>{{app_name}}</b>, berikut ini adalah kode untuk memverifikasi email anda.
									</p>
									<table class="" border="1" cellpadding="1" cellspacing="2" width="100%" style="border-style: dashed;">
										<tbody>
											<tr>
												<td style="text-align:center"><h3><b>{{activation_code}}</b><h3></td>
											</tr>
										</tbody>
									</table>
									<br />
									<p class="callout">
										Setelah Anda berhasil mengisikan data diri, silahkan masukan kode verifikasi ini di kolom yang sudah disediakan. Kemudian perbaharui halaman anda agar dapat melanjutkan proses rekrutmen.
										<br />
									</p>
									<br />
                  <p><b>Abaikan email ini jika kamu tidak merasa mendaftar di {{app_name}}. Untuk pertanyaan, silakan hubungi <a href="mailto:{{email_reply}}">{{email_reply}}</a>.</p>
									<br />
									<br />
                  <p>Best Regards,</p>
									<p>HR Recruitment and Development</p>
                  <p>{{company_name}}</p>
									<br />
									<br />
									<br />
                  <p><small><i>Note : <b>Hati-hati penipuan ! Proses seleksi penerimaan karyawan tidak dipungut biaya apapun</b></i></small></p>
								</td>
							</tr>
						</table>
					</div><!-- /content -->
					<p style="text-align: center; font-size: small; color: #3c3c3c; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
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
