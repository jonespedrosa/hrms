let selectedDepartments = [];
let urlParams = new URLSearchParams(window.location.search);
let hasId = urlParams.has("id");
let isEdit = false;
let removedEnrolledDepartments = [];
let removedTopics = [];
$(function () {
  if (hasId) {
    Swal.fire({
      html: `
<div style="display: flex; flex-direction: column; justify-content: flex-end; align-items: center; height: 100%;">
	<img src="images/fetching-data.gif" width="250" height="200">
	<p class="mt-3 font-weight-bold h4 text-center">Getting Data from Cloud</p>
</div>
`,
      toast: true,
      position: "center",
      showConfirmButton: false,
      timerProgressBar: true,
      showCancelButton: false,
      didOpen: (toast) => {
        // Swal.showLoading();
        Swal.hideLoading();
        toast.style.pointerEvents = "none";
      },
      willClose: () => {
        Swal.hideLoading();
      },
    });
    onRenderCourse();
  } else {
    $("#courseForm").removeClass("d-none");
  }

  $("#departmentSelector").on("change", function () {
    let selectedValue = $(this).val();
    onDepartmentSelector(selectedValue);
  });

  // $("#addTopicButton").on("click", function () {
  //   onAddTopic();
  // });

  $(document).on("click", ".delete-topic", function () {
    if ($(".topic").length <= 1) {
      onShowAlert(
        null,
        "There should be at least one(1) topic.",
        "warning",
        true,
        "top-end",
        false,
        2500,
        true
      );
      return false;
    } else {
      if ($(this).data("topic-id") !== undefined) {
        let removedTopic = $(this).data("topic-id");
        removedTopics.push(removedTopic);
      }
      $(this).parent().remove();
    }
  });

  $(document).on("click", "#courseEdit", function () {
    isEdit = !isEdit;

    if (isEdit) {
      editCourse($(this));
    } else {
      cancelEditCourse();
    }
  });

  $("#courseForm").on("submit", function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    if (!isEdit) {
      $("#createCourseBtn").prop("disabled", true);
      onSubmitCourseForm(formData);
    } else {
      $("#updateCourseBtn").prop("disabled", true);
      onUpdateCourseForm(formData);
    }
  });
});
function onGetDepartments(areaType) {
  return $.ajax({
    url: "Function/learning_and_development_func.php?course=getDepartmentByAreatype",
    type: "GET",
    data: {
      areaType: areaType,
    },
  });
}

function onAddDepartment() {
  let departmentToBeAdded = selectedDepartments;
  let checkboxCount = 0;
  let duplicateCount = 0;
  let addCount = 0;
  $(".checkbox-department").each(function () {
    if ($(this).is(":checked")) {
      let value = parseInt($(this).val());
      let textContent = $(this).data("branch");
      if (!_.includes(departmentToBeAdded, value)) {
        selectedDepartments.push(parseInt(value));
        if (_.includes(departmentToBeAdded, value)) {
          _.pull(removedEnrolledDepartments, value);
        }
        $("#enrolled-departments").append(
          `<span class="badge badge-pill badge-info align-items-center m-1" id="added-department-${value}"> 
			${textContent}
			<a href="#" class="text-white ml-2 font-weight-bold" onClick="onRemoveSelection(${value})">&times;</a>
		</span>`
        );
        addCount++;
      } else {
        duplicateCount++;
      }
      checkboxCount++;
    }
  });

  if (checkboxCount === 0) {
    onShowAlert(
      null,
      "Please select at least one (1) department.",
      "warning",
      true,
      "top-end",
      false,
      2500,
      true
    );
  } else if (duplicateCount > 0 && addCount > 0) {
    $("#tagDepartmentModal").modal("hide");
    onShowAlert(
      null,
      "Successfully added some departments. However, some were not added as they were already selected",
      "info",
      true,
      "top-end",
      false,
      3500,
      true
    );
  } else if (duplicateCount > 0) {
    onShowAlert(
      null,
      "The selected department/s are already selected",
      "error",
      true,
      "top-end",
      false,
      3000,
      true
    );
  } else {
    $("#tagDepartmentModal").modal("hide");
    onShowAlert(
      null,
      "The department has been added to the selection.",
      "success",
      true,
      "top-end",
      false,
      2500,
      true
    );
  }
}

function onRemoveSelection(id) {
  $("#added-department-" + id).remove();
  _.pull(selectedDepartments, id);
  removedEnrolledDepartments.push(id);
}

function onShowAlert(
  title,
  text,
  icon,
  isToast,
  position,
  showConfirmButton,
  timer,
  timerProgressBar
) {
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

function onDepartmentSelector(selectedValue) {
  onGetDepartments(selectedValue)
    .then(function (response) {
      let jsonResponseDepartments = JSON.parse(response);
      let selectedText = $("#departmentSelector option:selected").text();
      $("#modal-department-title").text(selectedText + " Departments:");
      $("#department-selection").empty();
      $("#department-selection").append(`
	<div class="col-sm-6">
		<div class="input-group mb-3">
			<div class="input-group-prepend">
				<div class="input-group-text">
					<input id="selectAllDepartments" type="checkbox">
				</div>
			</div>
			<label for="selectAllDepartments" class="form-control text-truncate">Select All</label>
		</div>
	</div>
`);
      jsonResponseDepartments.forEach(function (department) {
        $("#department-selection").append(
          `<div class="col-sm-6">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<div class="input-group-text">
						<input id="${department.userid}" type="checkbox" class="checkbox-department" data-branch="${department.branch}" value="${department.userid}">
					</div>
				</div>
				<label for="${department.userid}" class="form-control text-truncate">${department.branch}</label>
			</div>
		</div>`
        );
      });
      $("#tagDepartmentModal").modal("show");
      $("#departmentSelector").val("");

      selectedDepartments.forEach(function (value) {
        let checkboxToUncheck = $(
          'input[type="checkbox"][value="' + value + '"]'
        );
        if (checkboxToUncheck.length > 0) {
          checkboxToUncheck.prop("checked", true);
        }
      });

      $("#selectAllDepartments").on("change", function () {
        let isChecked = $(this).prop("checked");
        $(".checkbox-department").prop("checked", isChecked);
      });
    })
    .catch(function (error) {
      console.log(error);
    });
}

// function onAddTopic() {
//   let clonedTopic = $("#topicContainer");
//   clonedTopic.append(`
// <div class="topic d-flex mb-3">
// <input type="text" class="d-none" name="topicId[]"/>
// <textarea class="form-control topic-title mr-3" id="topicTitle" name="topicTitle[]"
// placeholder="Topic Title" aria-label="Topic Title" aria-describedby="basic-addon2" required></textarea>

// <textarea class="form-control topic-textarea mr-3" id="topicDescription"
// name="topicDescription[]" placeholder="Topic Description" aria-label="Topic Description"
// aria-describedby="basic-addon2"></textarea>

// <a href="#" class="delete-topic btn btn-sm btn-white btn-icon-split text-danger">
// 	<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
// 	<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
// 	<path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
// 	</svg>
// </a>
// </div>
// `);
// }

function preventInvalidCharacters(event) {
  const invalidChars = [
    "'",
    "&",
    '"',
    "*",
    "<",
    ">",
    "{",
    "}",
    "[",
    "]",
    "+",
    "=",
    "^",
    "$",
    "@",
    "#",
    "!",
  ];
  if (event.key === "Enter" || invalidChars.includes(event.key)) {
    event.preventDefault();
  }
}

function onAddTopic() {
  let clonedTopic = $("#topicContainer");
  clonedTopic.append(`
      <div class="topic d-flex mb-3">
          <input type="text" class="d-none" name="topicId[]"/>
          <textarea class="form-control topic-title mr-3" id="topicTitle" name="topicTitle[]"
          placeholder="Topic Title" aria-label="Topic Title" aria-describedby="basic-addon2" required></textarea> 

          <textarea class="form-control topic-textarea mr-3" id="topicDescription"
          name="topicDescription[]" placeholder="Topic Description" aria-label="Topic Description" 
          aria-describedby="basic-addon2"></textarea>

          <a href="#" class="delete-topic btn btn-sm btn-white btn-icon-split text-danger">
              <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
              </svg>
          </a>
      </div>
  `);

  // Attach the event listener to the new textareas
  let newTextareas = clonedTopic.find("textarea");
  newTextareas.off("keypress").on("keypress", preventInvalidCharacters);
}

$(document).ready(function () {
  // Attach the event listener to existing textareas
  let textareas = $("textarea");
  textareas.on("keypress", preventInvalidCharacters);

  // Attach the onAddTopic function to the add button
  $("#addTopicButton").on("click", onAddTopic);
});

document.addEventListener("DOMContentLoaded", (event) => {
  const textareas = document.querySelectorAll("textarea");

  textareas.forEach((textarea) => {
    textarea.addEventListener("keypress", preventInvalidCharacters);
  });
});

function onSubmitCourseForm(formData) {
  if (selectedDepartments.length > 0) {
    $.ajax({
      url: "Function/learning_and_development_func.php?course=addCourse",
      type: "POST",
      data: {
        formData: formData,
        enrolledDepartments: selectedDepartments,
      },
      success: function (response) {
        Swal.fire({
          title: "Success!",
          text: "Course added successfully.",
          icon: "success",
          timerProgressBar: true,
          showConfirmButton: false,
          timer: 1500,
        }).then(() => {
          window.location.href = "/hrms/courses.php";
        });
      },
      error: function (error) {
        Swal.fire({
          title: "Error!",
          text: "An error occurred while adding the course. Please try again later.",
          icon: "error",
          showConfirmButton: false,
          timer: 1500,
        });
        $("#createCourseBtn").prop("disabled", false);
      },
    });
  } else {
    onShowAlert(
      null,
      "Please select at least one (1) department.",
      "warning",
      true,
      "top-end",
      false,
      2500,
      true
    );
    $("#createCourseBtn").prop("disabled", false);
  }
}

// function onRenderCourse() {
//   $("input").prop("disabled", true);
//   $("textarea").prop("disabled", true);
//   onGetCourseById()
//     .then(function (response) {
//       console.log(response);
//       console.log("Test 1");

//       Swal.close();
//       $("#courseForm").removeClass("d-none");
//       let jsonResponseCourse = JSON.parse(response);
//       if (!jsonResponseCourse.is_exist) {
//         $("#no-course").removeClass("d-none");
//         $("#courseForm").addClass("d-none");
//       } else {
//         $("#courseTitle").val(jsonResponseCourse.course[0].name);
//         $("#courseDescription").val(jsonResponseCourse.course[0].description);

//         jsonResponseCourse.departments.forEach(function (department) {
//           selectedDepartments.push(parseInt(department.userid));
//           $("#enrolled-departments").append(
//             `<span class="badge badge-pill badge-info align-items-center m-1" id="added-department-${department.userid}">
// 			${department.branch}
// 			<a href="#" class="enrolled-departments text-white ml-2 font-weight-bold d-none" onClick="onRemoveSelection(${department.userid})">&times;</a>
// 		</span>`
//           );
//         });

//         jsonResponseCourse.topics.forEach(function (topic) {
//           $("#topicContainer").append(
//             `<div class="topic d-flex mb-3">
// 			<input type="text" class="d-none" name="topicId[]" value="${topic.id}"/>
// 			<textarea class="form-control topic-title mr-3" id="topicTitle" name="topicTitle[]"
// 			placeholder="Topic Title" aria-label="Topic Title" aria-describedby="basic-addon2" disabled required>${topic.name}</textarea>
// 			<textarea class="form-control topic-textarea mr-3" id="topicDescription"
// 				name="topicDescription[]" placeholder="Topic Description" aria-label="Topic Description"
// 				aria-describedby="basic-addon2" disabled>${topic.description}</textarea>
// 				<a href="#" class="delete-topic btn btn-sm btn-white btn-icon-split text-danger d-none" data-topic-id="${topic.id}">
// 				<svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
// 				<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
// 				<path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
// 			</svg>
// 			</a>
// 		</div>`
//           );
//         });

//         $("textarea").each(function () {
//           this.style.height = "auto";
//           this.style.height = this.scrollHeight + 10 + "px";
//         });
//       }
//     })
//     .catch(function (jqXHR, textStatus, error) {
//       console.log(jqXHR, textStatus, error);
//     });
// }

function preventInvalidCharacters(event) {
  const invalidChars = [
    "'",
    "&",
    '"',
    "*",
    "<",
    ">",
    "{",
    "}",
    "[",
    "]",
    "+",
    "=",
    "^",
    "$",
    "@",
    "#",
    "!",
  ];
  if (event.key === "Enter" || invalidChars.includes(event.key)) {
    event.preventDefault();
  }
}

function onRenderCourse() {
  $("input").prop("disabled", true);
  $("textarea").prop("disabled", true);
  onGetCourseById()
    .then(function (response) {
      console.log(response);
      console.log("Test 1");

      Swal.close();
      $("#courseForm").removeClass("d-none");
      let jsonResponseCourse = JSON.parse(response);
      if (!jsonResponseCourse.is_exist) {
        $("#no-course").removeClass("d-none");
        $("#courseForm").addClass("d-none");
      } else {
        $("#courseTitle").val(jsonResponseCourse.course[0].name);
        $("#courseDescription").val(jsonResponseCourse.course[0].description);

        jsonResponseCourse.departments.forEach(function (department) {
          selectedDepartments.push(parseInt(department.userid));
          $("#enrolled-departments").append(
            `<span class="badge badge-pill badge-info align-items-center m-1" id="added-department-${department.userid}"> 
              ${department.branch}
              <a href="#" class="enrolled-departments text-white ml-2 font-weight-bold d-none" onClick="onRemoveSelection(${department.userid})">&times;</a>
            </span>`
          );
        });

        jsonResponseCourse.topics.forEach(function (topic) {
          $("#topicContainer").append(
            `<div class="topic d-flex mb-3">
              <input type="text" class="d-none" name="topicId[]" value="${topic.id}"/>
              <textarea class="form-control topic-title mr-3" id="topicTitle" name="topicTitle[]"
              placeholder="Topic Title" aria-label="Topic Title" aria-describedby="basic-addon2" disabled required>${topic.name}</textarea> 
              <textarea class="form-control topic-textarea mr-3" id="topicDescription"
                  name="topicDescription[]" placeholder="Topic Description" aria-label="Topic Description" 
                  aria-describedby="basic-addon2" disabled>${topic.description}</textarea>
              <a href="#" class="delete-topic btn btn-sm btn-white btn-icon-split text-danger d-none" data-topic-id="${topic.id}">
                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                </svg>
              </a>
            </div>`
          );
        });

        // Attach the preventInvalidCharacters event listener to the rendered textareas
        $("#topicContainer textarea").on("keypress", preventInvalidCharacters);

        $("textarea").each(function () {
          this.style.height = "auto";
          this.style.height = this.scrollHeight + 10 + "px";
        });
      }
    })
    .catch(function (jqXHR, textStatus, error) {
      console.log(jqXHR, textStatus, error);
    });
}

$(document).ready(function () {
  // Attach the event listener to existing textareas
  $("textarea").on("keypress", preventInvalidCharacters);
});

function onGetCourseById() {
  return $.ajax({
    url: "Function/learning_and_development_func.php?course=getCourseById",
    type: "GET",
    data: {
      id: urlParams.get("id"),
    },
  });
}

function editCourse(editBTN) {
  editBTN.removeClass("btn-success");
  editBTN.addClass("btn-danger");
  editBTN.html(`<span class="mr-3">Cancel Edit</span> &times;`);
  $("#page-title").text("Edit Course");

  $("input").removeAttr("disabled");
  $("textarea").removeAttr("disabled");
  $(".delete-topic").removeClass("d-none");
  $(".enrolled-departments").removeClass("d-none");
  $(".enroll-department").removeClass("d-none");
  $("#updateCourseBtn").removeClass("d-none");
  $("#addTopicButton").removeClass("d-none");
}

function cancelEditCourse() {
  window.location.reload();
}

function onUpdateCourseForm(formData) {
  if (selectedDepartments.length > 0) {
    $.ajax({
      url: "Function/learning_and_development_func.php?course=updateCourse",
      type: "POST",
      data: {
        id: urlParams.get("id"),
        formData: formData,
        enrolledDepartments: selectedDepartments,
        removedEnrolledDepartments:
          removedEnrolledDepartments.length > 0
            ? removedEnrolledDepartments
            : null,
        removedTopics: removedTopics.length > 0 ? removedTopics : null,
      },
      success: function (response) {
        Swal.fire({
          title: "Success!",
          text: "Course updated successfully.",
          icon: "success",
          timerProgressBar: true,
          showConfirmButton: false,
          timer: 1500,
        }).then(() => {
          window.location.reload();
        });
      },
      error: function (error) {
        $("#updateCourseBtn").prop("disabled", false);
        Swal.fire({
          title: "Error!",
          text: "An error occurred while adding the course. Please try again later.",
          icon: "error",
          showConfirmButton: false,
          timer: 1500,
        });
        console.log(error);
      },
    });
  } else {
    onShowAlert(
      null,
      "Please select at least one (1) department.",
      "warning",
      true,
      "top-end",
      false,
      2500,
      true
    );
    $("#createCourseBtn").prop("disabled", false);
  }
}
