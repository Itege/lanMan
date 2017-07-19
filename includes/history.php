<div class="row">
	<div class="col-lg-12">
		<ul class="nav nav-pills nav-fill" style='margin-bottom: 10px'>
			<li role="presentation" class="nav-item">
				<a href=".?t=dashboard" class="nav-link">Dashboard</a>
			</li>
			<li role="presentation" class="nav-item nav-fill">
				<a href=".?t=stream" class="nav-link">Stream</a>
			</li>
			<li role="presentation" class="nav-item">
				<a href=".?t=history" class="active nav-link">History</a>
			</li>
		</ul>
	</div>
</div>
<div class='row'>
	<div class="col-lg-12">
		<div class="card">
			<div class="card-header">History</div>
			<div class="card-block">
				<table class="table">
					<thead>
						<tr>
							<th>Date</th>
							<th>Docket</th>
							<th>Food</th>
						</tr>
					</thead>
					<tbody>
						<?php getHistorical() ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
