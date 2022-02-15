<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Reminder: Undangan Interview {{interview_utype}}</title>
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
		
		<table class="body-wrap" summary="body content">
			<tr>
				<td>&nbsp;</td>
				<td class="container" bgcolor="#FFFFFF">
					<div class="content">
						<table width="100%">
							<tr>
								<td>
									<h1>Reminder: Undangan Interview {{interview_utype}}</h1>
									<br />
									<br />
									<h3>Yang terhormat Bapak/Ibu {{pelamar_nama}},</h3>
									<p>Kami informasikan bahwa anda telah diundang untuk mengikuti interview {{interview_utype}} pada:</p>

									<p>Hari / Tanggal: {{interview_waktu_tanggal}}.</p>
									<p>Lokasi: {{interview_lokasi}}.</p>
									<p>Mohon kesediaannya untuk mempersiapkan jadwal anda pada waktu yang telah ditentukan.</p>
									<p>Anda juga bisa melihat jadwal ini melalui {{link}}.</p>
									<hr>
                  <p style="color: #3c3c3c; font-size: smaller"><em>Supaya tidak masuk ke spam, silakan tambahkan email: {{email_dari}} ke kontak anda.</em></p>
									<br />
                  <p>&nbsp;</p>
									<p>&nbsp;</p>
									<p>Hormat kami,</p>
									<p>HRD {{company_nama}}</p>
								</td>
							</tr>
						</table>
						<p style="font-size: small; color: #3c3c3c; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>

	</body>
</html>
