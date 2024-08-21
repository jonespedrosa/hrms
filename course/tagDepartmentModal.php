<div class="modal fade" id="tagDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="tagDepartmentModal"
	aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex flex-column">
				<div class="d-flex w-100">
					<h4 class="modal-title font-weight-bold" id="modal-department-title"></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="row p-4" id="department-selection">
					<!-- Content Here -->
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="onAddDepartment()">Add +</button>
			</div>
		</div>
	</div>
</div>