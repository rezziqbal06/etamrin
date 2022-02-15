<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Undangan Interview {{interview_utype}} {{app_name}}</title>
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
									<h1>SELAMAT!!!</h1>
									<br />
									<br />
									<h3>{{pelamar_nama}}</h3>
									<p>Anda telah selesai lulus tahapan profiling {{app_name}} untuk posisi:</p>
									<h4>{{pelamar_posisi}}</h4>
									<br />
									<p>Kami mengundang anda untuk interview {{interview_utype}} secara {{interview_jenis}} pada:</p>
									<p>Hari / Tanggal: {{interview_tanggal}}</p>
									<p>Pukul: {{interview_waktu}} - selesai</p>
									<p>Tempat: {{interview_tempat}}</p>
									<p>Link / Alamat: {{interview_lokasi}} {{interview_keterangan}}</p>
									<p>Catatan: Pastikan anda sudah join 15 menit sebelumnya, dan siap mengikuti interview</p>
									<p>Mohon untuk konfirmasi kehadiran H-1 sebelum jadwal yang telah ditentukan dengan cara:</p>
									<ol>
										<li>Login kedalam akun anda di https://karir.sbpgroup.id</li>
										<li>Klik menu interview</li>
										<li>Pilih jawaban Anda</li>
									</ol>
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
						<p style="font-size: small; color: #3c3c3c; font-style: italic;">Copyright © {{site_name}}, All rights reserved.</p>
					</div>
				</td>
				<td>&nbsp;</td>
			</tr>
		</table>

	</body>
</html>
