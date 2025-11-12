<!DOCTYPE html>
		<html>
		<head>
			<title>Template Email</title>
			<style type="text/css">
			.tg {
				border-collapse:collapse;
				border-color:#ccc;
				border-spacing:0;
			}
			 .tg td{
				background-color:#fff;
				border-color:#ccc;
				border-style:solid;
				border-width:1px;
				color:#333;
				 font-family:Arial, sans-serif;
				font-size:14px;
				overflow:hidden;
				padding:10px 5px;
				word-break:bold;
				font-weight:bold;
			}
			 .tg th {
				
				border-color:#ccc;
				border-style:solid;
				border-width:1px;
				color:#fff;
				 font-family:Arial, sans-serif;
				font-size:14px;
				font-weight:bold;
				overflow:hidden;
				padding:10px 5px;
				word-break:normal;
			}
			 .tg .tg-0lax-green{
				text-align:left;
				vertical-align:top;
				background-color:green;
			}
			.tg .tg-0lax-yellow{
				text-align:left;
				vertical-align:top;
				background-color:#FFA500;
			}
			.tg .tg-0lax-red{
				text-align:left;
				vertical-align:top;
				background-color:red;
			}
			</style>
			<style>
				/* CSS untuk memastikan bahwa padding termasuk dalam lebar yang ditentukan */
				.email-container {
					box-sizing: border-box;
					width: 620px;
					margin-left: auto;
					margin-right: auto;
					border: 1px solid #dddddd;
					border-radius: 4px;
					box-shadow: 4px 4px 8px rgba(0,0,0,0.1);
					overflow: hidden; /* Ini akan memastikan bahwa isi tidak melebihi batas kotak */
					text-align: justify;
		
				}
		
				.email-content {
					padding: 20px;
					box-sizing: border-box;
				}
			</style>
		</head>
		<body style="margin: 0; padding: 0; width: 100%; font-family: Arial, sans-serif; background-color: #f4f4f4;">
		
		<!-- Container untuk memusatkan email -->
		<table class="email-container" align="center" border="0" cellpadding="0" cellspacing="0">
			<!-- Gambar Header -->
			<tr>
				<td>
					<img src="https://dms.bankwoorisaudara.com/assets/img/dms/header.png" width="620" style="display: block;" alt="Header">
				</td>
			</tr>
		
			<!-- Tubuh Email -->
			<tr>
				<td class="email-content">
                <div>Kepada Yth Bapak /Ibu {{ $details['user_input_name'] }}</div>
                <br/>
<div>Dokumen Loan ID <a href="https://dms.bankwoorisaudara.com/loans/{{ $details['loan_app_no'] }}" target="_blank"><b>{{ $details['loan_app_no'] }}</b></a> yang disubmit, telah kami periksa dengan hasil  <i><b>{{ $details['result'] }}.</b></i> </div>
<br/>
@if($details['result']=='Verify' || $details['result']=='TBO' )
<div>Dokumen yang diupload sudah lengkap, jelas dan benar sesuai dengan data Debitur sebagaimana opini dari Team DCRM. </div>
@else
<div>Mohon untuk memastikan dokumen yang diupload selanjutnya lebih lengkap, jelas dan benar sesuai dengan data Debitur sebagaimana opini dari Team DCRM. </div>
@endif
<br/>
<div>Terimakasih.</div>
<br/>
		</td>
		</tr>
	
		<!-- Gambar Footer -->
		<tr>
			<td>
				<img src="https://dms.bankwoorisaudara.com/assets/img/dms/footer.png" width="620" style="display: block;" alt="Footer">
			</td>
		</tr>
	</table>
	
	</body>
	</html>

