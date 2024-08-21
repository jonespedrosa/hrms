let selectedDepartments = [];
$(function () {
	onRenderCourses()
	onRenderFilter();
});
function onGetCourses(departmentId = null) {
	if (Array.isArray(departmentId) && departmentId.length === 0) {
		departmentId = null;
	}
	return $.ajax({
		url: "Function/learning_and_development_func.php?course=getCourses",
		type: 'GET',
		data: {
			departmentId: departmentId,
		}
	});
}

function onGetDepartments() {
	return $.ajax({
		url: "Function/learning_and_development_func.php?course=getDepartments",
		type: 'GET',
	});
}

function onRenderCourses(departmentId = null) {
	onGetCourses(departmentId).then(function (response) {
		let jsonResponse = JSON.parse(response);
		$('#courseDisplayTable').DataTable().destroy();
		let dataTable = $('#courseDisplayTable').DataTable({
			data: jsonResponse,
			"columnDefs": [{
				"width": "auto",
				"targets": "_all"
			}],
			"autoWidth": true,
			columns: [
				{
					data: "name",
					title: "Title",
					className: "name"
				},
				{
					data: "description",
					title: "Description",
					className: "description"
				},
				{
					data: "topic_count",
					title: "No. of Topics",
					className: "topic_count"
				},
				{
					data: "status",
					title: "Status",
					className: "status",
					render: function (data, type, row) {
						if (row.status == 'active') {
							return `<span class="badge badge-success">Active</span>`;
						} else {
							return `<span class="badge badge-danger">Inactive</span>`;
						}
					}
				},
				{
					data: "id",
					title: "Action",
					className: "action_btns",
					render: function (data, type, row) {
						return `
					<div class="text-center">
						<a href='editcourses.php?id=${row.id}' data-toggle="tooltip" title="View Course">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
								<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
								<path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
							</svg>
						</a>
					</div>`;
					}
				},
			],
		});
	}).catch(function (jqXHR, textStatus, errorThrown) {
		console.log(jqXHR, textStatus, errorThrown)
	});

}

function onRenderFilter() {
	onGetDepartments().then(function (response) {
		console.log(response)
		let jsonResponse = JSON.parse(response);
		let currentDepartment = null;
		$('#filterModalBody').empty();

		jsonResponse.forEach(function (department) {
			let area = department.department;
			if (currentDepartment === null || currentDepartment.toLowerCase() !== area.toLowerCase()) {
				currentDepartment = area.toUpperCase();
				let accordionHeader = `
					<p class='font-weight-bold d-flex justify-content-between shadow p-2 align-items-center' data-toggle="collapse" data-target="#${currentDepartment}-content" aria-expanded="true" aria-controls="${currentDepartment}-content">
					${currentDepartment}
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
						</svg>
					</p>
					<div class="collapse" id="${currentDepartment}-content">
						<div class="row" id='${currentDepartment}'></div>
					</div>
				`;
				$('#filterModalBody').append(accordionHeader);
			}
			$('#' + currentDepartment).append(`
				<div class="col-sm-6">
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<div class="input-group-text">
								<input id="${department.userid}" type="checkbox" class="checkbox-department"
									data-branch="${department.branch.toUpperCase()}" value="${department.userid}">
							</div>
						</div>
						<label for="${department.userid}"
							class="form-control text-truncate">${department.branch.toUpperCase()}</label>
					</div>
				</div>
			`);
		});

	}).catch(function (jqXHR, textStatus, error) {
		console.log(jqXHR, textStatus, error)
	});
}

function applyFilter() {
	let value = null;
	let textContent = null;
	let duplicateCount = 0;
	let addCount = 0;
	$('.checkbox-department').each(function () {
		if ($(this).prop('checked')) {
			value = parseInt($(this).val());
			textContent = $(this).data('branch');
			if (!_.includes(selectedDepartments, value)) {
				selectedDepartments.push(value);
				addCount++;
				onRenderCourses(selectedDepartments);
				onRenderFilteredDepartments(value, textContent);
				onShowAlert(null, 'Filter applied.', 'success', true, 'top-end', false, 1500, true);
				$('#filterModal').modal('hide');
			} else {
				duplicateCount++
			}
		}
	});

	if (selectedDepartments.length <= 0) {
		onShowAlert(null, 'Select at least one (1) department to filter', 'info', true, 'top-end', false, 1500, true);
	} else if (addCount > 0) {
		onShowAlert(null, 'Filter applied.', 'success', true, 'top-end', false, 1500, true);
		$('#filterModal').modal('hide');
	} else if (duplicateCount > 0) {
		onShowAlert(null, 'Filter already applied', 'info', true, 'top-end', false, 1500, true);
	}

}

function onShowAlert(title, text, icon, isToast, position, showConfirmButton, timer, timerProgressBar) {
	Swal.fire({
		title: title,
		text: text,
		icon: icon,
		toast: isToast,
		position: position,
		showConfirmButton: showConfirmButton,
		timer: timer,
		timerProgressBar: timerProgressBar,
	});
}

function onRenderFilteredDepartments(value, textContent) {
	$('#filteredDepartments').append(`
		</span><span class="badge badge-pill badge-info align-items-center m-1" id="added-department-${value}">
			${textContent}
			<a href="#" class="text-white ml-2 font-weight-bold"
			onClick="onRemoveSelection(${value})">&times;</a>
		</span> 
	`);
}

function onRemoveSelection(id) {
	$('#added-department-' + id).remove();
	let checkboxToUncheck = $('input[type="checkbox"][value="' + id + '"]');
	if (checkboxToUncheck.length > 0) {
		checkboxToUncheck.prop('checked', false);
	}
	_.pull(selectedDepartments, id);

	onRenderCourses(selectedDepartments);
}
