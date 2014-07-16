<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="/statics/css/bootstrap.min.css">
	<link rel="stylesheet" href="/statics/css/parsley.css">
</head>
<body style="padding:20px;">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Image Downloader Application</h3>
	  </div>
	  <div class="panel-body">
		<form method="POST" id="form" data-parsley-validate>
			<input type="text" data-parsley-required="true" data-parsley-type="url" name="url" value="" id="url" class="form-control" placeholder="Please provide a valid URL e.g http://"><br>
			<button id="process_url" class="btn btn-primary pull-right">Run Application</button>
			<button id="download_images" type="button" class="btn btn-info pull-right" style="display:none; margin-right:10px;">Download Images</button>
		</form>
	  </div>
	</div>
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Images</h3>
	  </div>
	  <div class="panel-body">
		<table id="images_table" class="table table-bordered">
			<thead>
				<tr>
					<td>URL</td>
					<td>Status</td>
					<td>Preview</td>
				</tr>
			</thead>
			<tbody>
				
			</tbody>
		</table>
	  </div>
	</div>
	<script type="text/javascript" src="/statics/js/jquery.js"></script>
	<script type="text/javascript" src="/statics/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/statics/js/parsley.js"></script>
	<script type="text/javascript" src="/statics/js/app.js"></script>
</body>
</html>