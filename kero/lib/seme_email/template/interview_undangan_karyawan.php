<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Pemberitahuan Jadwal Interview {{interview_utype}}</title>
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
								<td style="text-align: center;">
									<h1><b>Pemberitahuan Jadwal Interview {{interview_utype}}</b></h1>
									<br />
									<br />
									<h3>Yang terhormat Bapak/Ibu {{interviewer_nama}},</h3>
									<p>Kami informasikan bahwa anda dijadwalkan menjadi <b>interviewer</b> untuk Interview {{interview_utype}} secara {{interview_jenis}}. Berikut ini adalah detailnya:</p>
									<p>Nama: <b>{{pelamar_nama}}</b></p>
									<p>Posisi: <b>{{pelamar_posisi}}</b></p>
									<p>Tanggal: {{interview_waktu_tanggal}}.</p>
									<p>Tempat: {{interview_tempat}}</p>
									<p>Link / Alamat: {{interview_lokasi}} {{interview_keterangan}}</p>
									<p>Link Profil Pelamar: <a href="{{link_profil}}">{{link_profil}}</a>.</p>
									<p>Link Form Interview: <a href="{{link_form}}">{{link_form}}</a>.</p>
									<p>Kandidat yang bersangkutan juga telah diberitahu melalui email untuk jadwal ini. Mohon kesediannya untuk mempersiapkan jadwal anda pada waktu yang telah ditentukan.</p>
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
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>

	</body>
</html>
