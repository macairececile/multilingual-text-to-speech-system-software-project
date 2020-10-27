<div id="evaluation-audio" class="modal fade" role="dialog">
	<div class="modal-dialog">
	    <div class="modal-content" style="width: 650px;">
	      	<div class="modal-header">
	        	<h4 class="modal-title">Evaluation Audio</h4>
	      	</div>
	      	<div class="modal-body">
	      		<table class="table-listing">
					<thead>
						<tr>
							<th>No</th>
							<th>Audio</th>
							<th ng-if="filter.type == 1">Intelligibility</th>
							<th ng-if="filter.type == 1">Naturalness</th>
							<th ng-if="filter.type == 2">Speaker</th>
							<th>Emotion</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="eva in evaluation track by $index">
							<td>{{$index + 1}}</td>
							<td>
								<b>{{eva.name}}</b><br>
								<audio controls="">
									<source ng-src="{{eva.url}}">
								</audio>
							</td>
							<td ng-if="filter.type == 1">{{eva.intelligibility}}</td>
							<td ng-if="filter.type == 1">{{eva.naturalness}}</td>
							<td ng-if="filter.type == 2">{{eva.speaker}}</td>
							<td>{{eva.emotion}}</td>
						</tr>
					</tbody>
				</table>
	      	</div>
	      	<div class="modal-footer">
	      	</div>
	    </div>
  	</div>
</div>