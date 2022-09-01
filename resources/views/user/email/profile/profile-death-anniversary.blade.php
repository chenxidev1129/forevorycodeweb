<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
	<title>Today is {{$data['profile_user']}}s death anniversary || Forevory</title>
</head>
<body>
	<style type="text/css">
		body{margin: 0;padding: 0; background-color: #f1f1f1; font-family: 'Inter', sans-serif;} 
	</style>

	<table style="width:600px;  margin:50px auto 0; background-color:#ffffff;padding:40px 20px; " cellpadding="0" cellspacing="0">
		<tr>
			<td style="margin:0 auto ; padding-bottom: 30px; border-bottom:1px solid #f1f1f1;text-align: center;"> <img src="{{ url('assets/images/logo.png') }}" alt="logo" style="margin:0 auto ; display: block; width:190px;"></td>
		</tr>
		
		<tr>
			<td style="padding-top:40px;"><h6 style="font-size:18px; color:#000; margin: 0;">Hi <strong>{{ $data['profile_user'] }},</strong></h6></td>
		</tr>
		<tr>
			<td style="font-size: 16px;color:#7b7777; padding-top:30px; padding-bottom: 10px;">Today is {{ $data['profile_name'] }}'s death anniversary.</td>
		</tr>
		<tr>
			<td style="font-size: 16px;color:#7b7777; padding-top:30px; padding-bottom: 20px;">As each day passes we miss the loved one more and more. May {{ $data['profile_name'] }} rest in peace.</td>
		</tr>
		<tr>
			<td>
				<p style="font-size:14px; color:#7b7777; margin:0 0 5px;">Thanks and Regards</p>
				<p style="font-size:14px; margin:0 0 5px;color:#35BDF3; font-weight: 600;">Forevory Team</p>
				<p style="font-size:14px; color:#7b7777; margin:0;">15 Lord Avenue, Suite 1, 
				Lawrence, New York 11559-1321, USA</p>
			</td>
		</tr>
	</table>
</body>
</html>