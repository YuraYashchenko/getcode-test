<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
	<link rel="stylesheet" href="css/app.css">
	<script>
		window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
	</script>
</head>
<body>
	<div id="app">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<todos></todos>
				</div>
			</div>
		</div>
	</div>

	<script src="/js/app.js"></script>
</body>
</html>