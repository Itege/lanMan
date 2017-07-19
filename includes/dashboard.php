	<div class="row">
		<div class="col-lg-12">
			<ul class="nav nav-pills nav-fill" style='margin-bottom: 10px'>
				<li role="presentation" class="nav-item">
					<a href=".?t=dashboard" class="active nav-link">Dashboard</a>
				</li>
				<li role="presentation" class="nav-item nav-fill">
					<a href=".?t=stream" class="nav-link">Stream</a>
				</li>
				<li role="presentation" class="nav-item">
					<a href=".?t=history" class="nav-link">History</a>
				</li>
			</ul>
		</div>
	</div>
	<div class='row'>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">Docket</div>
				<div class='<?= $userHasRsvp[0]?>' <?= $userHasRsvp[1] ?>>
					<div class="card-block">
						<form>
							<div class="row">
								<div class="col-lg-9">
									<input name="add" type="text" class="form-control" />
								</div>
								<div class="col-lg-3 text-right">
									<button type="button" class="btn btn-outline-primary add-btn activity">Add</button>
								</div>
							</div>
						</form>
						<br />
						<form method="post">
							<input type="hidden" value="activity" name="voteFor" />
							<div class="has-table">
								<table class="table table-sm">
									<?= buildVotes("activities") ?>
								</table>
							</div>
							<br />
							<button type="submit" class="btn btn-success">Vote</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<div class="card-header">Food</div>
				<div class='<?= $userHasRsvp[0]?>' <?= $userHasRsvp[1] ?>>
					<div class="card-block">
						<form>
							<div class="row">
								<div class="col-lg-9">
									<input name="add" type="text" class="form-control" />
								</div>
								<div class="col-lg-3 text-right">
									<button type="button" class="btn btn-outline-primary add-btn food">Add</button>
								</div>
							</div>
						</form>
						<br />
						<form method="post">
							<input type="hidden" value="food" name="voteFor" />
							<div class="has-table">
								<table class="table table-sm">
									<?= buildVotes("food") ?>
								</table>
							</div>
							<br />
							<button type="submit" class="btn btn-success">Vote</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='row'>
		<div class="col-lg-12">
			<div class="card" style="margin-top: 20px;">
				<div class="card-header">RSVP</div>
				<div class="card-block">
					<form method="post">
						<table class="table table-sm">
							<thead>
								<tr>
									<th>Attending</th>
									<th>Comment</th>
									<th>RSVP</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php $userRsvpStatus = getUserStatus()?>
									<td>
										<input type="checkbox" name="attending" <?= ($userRsvpStatus['user_id'] != ""?"checked":"") ?>>
										</td>
									<td>
										<input type="text" class="form-control" name="comment" value='<?= htmlspecialchars($userRsvpStatus["comment"],ENT_QUOTES)?>' />
									</td>
									<td>
										<button type="submit" class="btn btn-success" name="action" value="rsvp">RSVP</button>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					<div class="has-table">
						<table class="table table-sm">
							<tbody>
								<?= getRsvpUsers() ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
