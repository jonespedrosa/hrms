<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Mary Grace Foods Inc.</title>
    <link rel="icon" href="images/logoo.png">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .card-body {
            text-align: justify;
            /* Justify text */
        }

        /* Optional: To ensure the last line is also justified, add this */
        .card-body::after {
            content: "";
            display: block;
            width: 100%;
            height: 0;
            clear: both;
        }

        .swal-button-green {
            background-color: #48BF81 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 10px 20px !important;
            cursor: pointer !important;
            outline: none !important;
        }

        .swal-button-green:hover {
            background-color: #48BF81 !important;
            /* Optional: darker shade on hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <img src="images/Header.jpg" alt="Header Image" class="img-fluid rounded" style="width: 100%; height: auto;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="contentCard" class="card o-hidden border-0 shadow-lg my-4" style="max-width: 800px; width: 100%; margin: 0 auto;">
            <div id="cardHeader" class="card-header" style="background-color: #9D241A; color: white; font-weight: bold;">
                <!-- Header content will be dynamically added here -->
            </div>
            <div class="card-body p-0 ">
                <div class="row">
                    <div class="col-lg-12">
                        <div id="cardContent" class="p-4">
                            <!-- Initial content goes here -->
                            <p>Welcome to the MG Core Values Evaluation form. This gives an opportunity for leaders and peers in the
                                organization to assess the experience they have had with each other and
                            </p>
                        </div>

                        <div class="d-flex justify-content-between p-3">
                            <button id="backButton" class="btn btn-secondary" style="display: none;">Back</button>
                            <button id="nextButton" class="btn btn-primary">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardHeader = document.getElementById('cardHeader');
            const cardContent = document.getElementById('cardContent');
            const nextButton = document.getElementById('nextButton');
            const backButton = document.getElementById('backButton');

            // Event delegation to handle the "Submit another response" link click
            document.addEventListener('click', function(event) {
                if (event.target && event.target.id === 'submitAnotherResponse') {
                    event.preventDefault(); // Prevent the default link behavior
                    currentPage = 0; // Reset to the first page
                    updateContent(); // Update the content to show the first page
                }
            });

            function saveAnswer(name, label, value) {
                let answers = JSON.parse(sessionStorage.getItem('answers')) || {};
                answers[name] = answers[name] || {};
                answers[name][label] = value;
                sessionStorage.setItem('answers', JSON.stringify(answers));

                // Debug log to see what is saved
                console.log('Saved Answer:', {
                    name,
                    label,
                    value
                });
            }

            function savePageData() {
                if (currentPage === 1) {
                    const evaluatedNameElement = document.getElementById('evaluatedName');
                    const evaluatedPositionElement = document.getElementById('evaluatedPosition');
                    const evaluatedIdNumberElement = document.getElementById('empnoNumber');


                    if (evaluatedNameElement && evaluatedPositionElement) {
                        const evaluatedName = evaluatedNameElement.value.trim();
                        const evaluatedPosition = evaluatedPositionElement.value.trim();
                        const evaluatedIdNumber = evaluatedIdNumberElement.value.trim();

                        saveAnswer('evaluated', 'name', evaluatedName);
                        saveAnswer('evaluated', 'position', evaluatedPosition);
                        saveAnswer('evaluated', 'idnumber', evaluatedIdNumber);

                    }

                } else if (currentPage === 2 || currentPage === 3 || currentPage === 4 || currentPage === 5 || currentPage === 6) {
                    const radioButtons = document.querySelectorAll(`input[name]`);
                    radioButtons.forEach(button => {
                        if (button.checked) {
                            saveAnswer(button.name, button.id, button.value);
                            // Check for CHAMPION or DRAG
                            if (button.value === "5" || button.value === "-1") {
                                const textareaId = `${button.name}-short-explanation`;
                                const textareaValue = document.getElementById(textareaId)?.value.trim() || '';
                                if (textareaValue) {
                                    saveAnswer(button.name, textareaId, textareaValue);
                                } else {
                                    // Handle empty textarea if required
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Missing Reason',
                                        text: 'Please provide a reason for selecting CHAMPION or DRAG.',
                                        confirmButtonText: 'OK',
                                        customClass: {
                                            confirmButton: 'swal-button-green'
                                        }
                                    });
                                }
                            }
                        }
                    });
                }
            }

            function loadPageData() {
                const answers = JSON.parse(sessionStorage.getItem('answers')) || {};

                if (currentPage === 1) {
                    const evaluatedNameElement = document.getElementById('evaluatedName');
                    const evaluatedPositionElement = document.getElementById('evaluatedPosition');
                    const evaluatedIdNumberElement = document.getElementById('empnoNumber');


                    if (evaluatedNameElement && evaluatedPositionElement) {
                        const evaluatedName = answers.evaluated?.name || '';
                        const evaluatedPosition = answers.evaluated?.position || '';
                        const evaluatedIdNumber = answers.evaluated?.idnumber || '';

                        evaluatedNameElement.value = evaluatedName;
                        evaluatedPositionElement.value = evaluatedPosition;
                        evaluatedIdNumberElement.value = evaluatedIdNumber;

                    }
                }
                if (currentPage === 2 || currentPage === 3 || currentPage === 4 || currentPage === 5 || currentPage === 6) {
                    Object.keys(answers).forEach(name => {
                        const answer = answers[name];
                        Object.keys(answer).forEach(label => {
                            const value = answer[label];
                            // Set the radio buttons
                            const element = document.querySelector(`input[name="${name}"][value="${value}"]`);
                            if (element) {
                                element.checked = true;
                            }
                            // Set the textarea
                            const textarea = document.getElementById(label);
                            if (textarea) {
                                textarea.value = value;
                            }
                        });
                    });
                }
            }

            function loadAnswers() {
                const answers = JSON.parse(sessionStorage.getItem('answers')) || {};

                Object.keys(answers).forEach(name => {
                    const answer = answers[name];
                    Object.keys(answer).forEach(label => {
                        const value = answer[label];
                        const element = document.querySelector(`input[name="${name}"][value="${value}"]`);
                        if (element) {
                            element.checked = true;
                        }
                    });
                });
            }

            function getQueryParameter(name) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(name);
            }

            const fullName = getQueryParameter('name');
            const position = getQueryParameter('position');

            const pages = [{
                    header: "CORE VALUES",
                    content: `
                            <p>Test Site 
                            </p>
                            `
                },
                {
                    header: "Information",
                    content: `
                            <form>
                                <div class="mb-3">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName" value="${fullName}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="position" class="form-label">Position</label>
                                    <input type="text" class="form-control" id="position" value="${position}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="empnoNumber" class="form-label">ID Number of Employee to be Evaluated</label>
                                    <input type="text" class="form-control" id="empnoNumber" maxlength="4" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4);">
                                </div>
                                <div class="mb-3">
                                    <label for="evaluatedName" class="form-label">Name of Person to be Evaluated</label>
                                    <input type="text" class="form-control" id="evaluatedName" required>
                                </div>
                                <div class="mb-3">
                                    <label for="evaluatedPosition" class="form-label">Position of Person to be Evaluated</label>
                                    <input type="text" class="form-control" id="evaluatedPosition" required>
                                </div>
                            </form>
                        `
                },
                {
                    header: "HUMILITY - The way of littleness!",
                    content: `
                    <div>Humility-1a: ${onGenerateAnswer('humility-1a')}</div>
                    <div>Humility-1b: ${onGenerateAnswer('humility-1b')}</div>
                    <div>Humility-1c: ${onGenerateAnswer('humility-1c')}</div>
                    <div>Humility-2a: ${onGenerateAnswer('humility-2a')}</div>
                    <div>Humility-2b: ${onGenerateAnswer('humility-2b')}</div>
                    <div>Humility-2c: ${onGenerateAnswer('humility-2c')}</div>
                    <div>Humility-2d: ${onGenerateAnswer('humility-2d')}</div>
                    <div>Humility-2e: ${onGenerateAnswer('humility-2e')}</div>
                    <div>Humility-3a: ${onGenerateAnswer('humility-3a')}</div>
                    <div>Humility-3b: ${onGenerateAnswer('humility-3b')}</div>
                    <div>Humility-3c: ${onGenerateAnswer('humility-3c')}</div>
                    `
                },
                {
                    header: "RESPECT",
                    content: `
                    <div>Respect-1a: ${onGenerateAnswer('respect-1a')}</div>
                    <div>Respect-1b: ${onGenerateAnswer('respect-1b')}</div>
                    <div>Respect-1c: ${onGenerateAnswer('respect-1c')}</div>
                    <div>Respect-1d: ${onGenerateAnswer('respect-1d')}</div>
                    <div>Respect-1e: ${onGenerateAnswer('respect-1e')}</div>
                    <div>Respect-2a: ${onGenerateAnswer('respect-2a')}</div>
                    <div>Respect-2b: ${onGenerateAnswer('respect-2b')}</div>
                    <div>Respect-2c: ${onGenerateAnswer('respect-2c')}</div>
                    <div>Respect-2d: ${onGenerateAnswer('respect-2d')}</div>
                    <div>Respect-3a: ${onGenerateAnswer('respect-3a')}</div>
                    <div>Respect-3b: ${onGenerateAnswer('respect-3b')}</div>
                    <div>Respect-3c: ${onGenerateAnswer('respect-3c')}</div>
                    <div>Respect-3d: ${onGenerateAnswer('respect-3d')}</div>
                    <div>Respect-3e: ${onGenerateAnswer('respect-3e')}</div>
                    `
                },
                // {
                //     header: "Integrity and Honesty  - Be truthful. To yourself and others, always. Yes, always.",
                //     content: `
                //     <div>Integrity-and-Honesty-1a: ${onGenerateAnswer('integrity-and-honesty-1a')}</div>
                //     <div>Integrity-and-Honesty-1d: ${onGenerateAnswer('integrity-and-honesty-1d')}</div>
                //     <div>Integrity-and-Honesty-1b: ${onGenerateAnswer('integrity-and-honesty-1b')}</div>
                //     <div>Integrity-and-Honesty-2b: ${onGenerateAnswer('integrity-and-honesty-2b')}</div>
                //     <div>Integrity-and-Honesty-3a: ${onGenerateAnswer('integrity-and-honesty-3a')}</div>
                //     <div>Integrity-and-Honesty-3b: ${onGenerateAnswer('integrity-and-honesty-3b')}</div>
                //     <div>Integrity-and-Honesty-3c: ${onGenerateAnswer('integrity-and-honesty-3c')}</div>
                //     <div>Integrity-and-Honesty-3d: ${onGenerateAnswer('integrity-and-honesty-3d')}</div>
                //     <div>Integrity-and-Honesty-4b: ${onGenerateAnswer('integrity-and-honesty-4b')}</div>

                //     `
                // },
                // {
                //     header: "SERVICE FROM COMPASSION",
                //     content: `
                //     <div>Service-from-Compassion-1a: ${onGenerateAnswer('service-from-compassion-1a')}</div>
                //     <div>Service-from-Compassion-1b: ${onGenerateAnswer('service-from-compassion-1b')}</div>
                //     <div>Service-from-Compassion-2a: ${onGenerateAnswer('service-from-compassion-2a')}</div>
                //     <div>Service-from-Compassion-2b: ${onGenerateAnswer('service-from-compassion-2b')}</div>
                //     <div>Service-from-Compassion-2c: ${onGenerateAnswer('service-from-compassion-2c')}</div>
                //     <div>Service-from-Compassion-3c: ${onGenerateAnswer('service-from-compassion-3c')}</div>
                //     <div>Service-from-Compassion-3d: ${onGenerateAnswer('service-from-compassion-3d')}</div>

                //     `
                // },
                // {
                //     header: "Thriving Through Excellence",
                //     content: `
                //     <div>Thriving-Through-Excellence-1a: ${onGenerateAnswer('thriving-through-excellence-1a')}</div>
                //     <div>Thriving-Through-Excellence-1b: ${onGenerateAnswer('thriving-through-excellence-1b')}</div>
                //     <div>Thriving-Through-Excellence-1c: ${onGenerateAnswer('thriving-through-excellence-1c')}</div>
                //     <div>Thriving-Through-Excellence-1d: ${onGenerateAnswer('thriving-through-excellence-1d')}</div>
                //     <div>Thriving-Through-Excellence-2a: ${onGenerateAnswer('thriving-through-excellence-2a')}</div>
                //     <div>Thriving-Through-Excellence-2b: ${onGenerateAnswer('thriving-through-excellence-2b')}</div>
                //     <div>Thriving-Through-Excellence-2c: ${onGenerateAnswer('thriving-through-excellence-2c')}</div>
                //     <div>Thriving-Through-Excellence-2d: ${onGenerateAnswer('thriving-through-excellence-2d')}</div>
                //     <div>Thriving-Through-Excellence-3b: ${onGenerateAnswer('thriving-through-excellence-3b')}</div>
                //     <div>Thriving-Through-Excellence-4b: ${onGenerateAnswer('thriving-through-excellence-4b')}</div>
                //     <div>Thriving-Through-Excellence-5a: ${onGenerateAnswer('thriving-through-excellence-5a')}</div>
                //     <div>Thriving-Through-Excellence-5b: ${onGenerateAnswer('thriving-through-excellence-5b')}</div>
                //     <div>Thriving-Through-Excellence-6a: ${onGenerateAnswer('thriving-through-excellence-6a')}</div>
                //     <div>Thriving-Through-Excellence-6b: ${onGenerateAnswer('thriving-through-excellence-6b')}</div>
                //     <div>Thriving-Through-Excellence-7a: ${onGenerateAnswer('thriving-through-excellence-7a')}</div>
                //     <div>Thriving-Through-Excellence-7b: ${onGenerateAnswer('thriving-through-excellence-7b')}</div>
                //     <div>Thriving-Through-Excellence-7c: ${onGenerateAnswer('thriving-through-excellence-7c')}</div>
                //     `
                // },
                {
                    header: "",
                    content: `
                            <div class="card-body">
                                <h1>Shortened Core Values IST for mock up</h1>
                                <p>Your response has been recorded.</p>
                                <a href="#" id="submitAnotherResponse">Submit another response</a>
                            </div>
                        `
                }
            ];

            let currentPage = 0;

            function updateContent() {
                const page = pages[currentPage];
                cardHeader.innerHTML = page.header;
                cardContent.innerHTML = page.content;

                backButton.style.display = (currentPage === 0) ? 'none' : 'block';
                nextButton.innerHTML = (currentPage === pages.length - 1) ? 'Submit' : 'Next';

                if (currentPage === pages.length - 1) {
                    nextButton.style.display = 'none';
                } else {
                    nextButton.style.display = 'block';
                }

                // Debug log to see current page number
                console.log('Current Page:', currentPage);

                loadPageData(); // Load saved values on page update

                // Load saved answers
                loadAnswers();

            }

            // Define groups for each page
            const pageGroups = {
                2: [
                    'humility-1a', 'humility-1b', 'humility-1c',
                    'humility-2a', 'humility-2b', 'humility-2c', 'humility-2d', 'humility-2e',
                    'humility-3a', 'humility-3b', 'humility-3c'
                ],
                3: [
                    'respect-1a', 'respect-1b', 'respect-1c', 'respect-1d', 'respect-1e',
                    'respect-2a', 'respect-2b', 'respect-2c', 'respect-2d',
                    'respect-3a', 'respect-3b', 'respect-3c', 'respect-3d', 'respect-3e'
                ],
                4: [
                    'integrity-and-honesty-1a', 'integrity-and-honesty-1d', 'integrity-and-honesty-1b',
                    'integrity-and-honesty-2b', 'integrity-and-honesty-3a', 'integrity-and-honesty-3b',
                    'integrity-and-honesty-3c', 'integrity-and-honesty-3d', 'integrity-and-honesty-4b'
                ],
                5: [
                    'service-from-compassion-1a', 'service-from-compassion-1b',
                    'service-from-compassion-2a', 'service-from-compassion-2b',
                    'service-from-compassion-2c', 'service-from-compassion-3c',
                    'service-from-compassion-3d'
                ],
                6: [
                    'thriving-through-excellence-1a', 'thriving-through-excellence-1b',
                    'thriving-through-excellence-1c', 'thriving-through-excellence-1d',
                    'thriving-through-excellence-2a', 'thriving-through-excellence-2b',
                    'thriving-through-excellence-2c', 'thriving-through-excellence-2d',
                    'thriving-through-excellence-3b', 'thriving-through-excellence-4b',
                    'thriving-through-excellence-5a', 'thriving-through-excellence-5b',
                    'thriving-through-excellence-6a', 'thriving-through-excellence-6b',
                    'thriving-through-excellence-7a', 'thriving-through-excellence-7b',
                    'thriving-through-excellence-7c'
                ]
            };

            function validatePage() {
                if (currentPage === 1) {
                    const evaluatedName = document.getElementById('evaluatedName').value.trim();
                    const evaluatedPosition = document.getElementById('evaluatedPosition').value.trim();
                    const evalutedIdNumber = document.getElementById('empnoNumber').value.trim();


                    if (evaluatedName === "" || evaluatedPosition === "" || evalutedIdNumber === "") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Required Fields Missing',
                            text: 'Please fill in all required fields before proceeding.',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'swal-button-green'
                            }
                        });
                        return false;
                    }
                } else if (pageGroups[currentPage]) {
                    // Validate radio buttons for the current page
                    const groups = pageGroups[currentPage];
                    for (const group of groups) {
                        const radios = document.querySelectorAll(`input[name="${group}"]:checked`);
                        if (radios.length === 0) {
                            Swal.fire({
                                icon: 'error',
                                title: 'No Selection Made',
                                text: `Please select an option for ${group}.`,
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                }
                            });
                            return false;
                        }
                    }

                    // Additional validation for CHAMPION or DRAG options
                    const radioButtons = document.querySelectorAll('input[type="radio"]:checked');
                    for (const button of radioButtons) {
                        if (button.value === "5" || button.value === "-1") {
                            const textareaId = `${button.name}-short-explanation`;
                            const textareaValue = document.getElementById(textareaId)?.value.trim() || '';
                            if (textareaValue === "") {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Missing Reason',
                                    text: 'Please provide a reason for selecting CHAMPION or DRAG.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    }
                                });
                                return false;
                            }
                        }
                    }
                }
                return true;
            }

            nextButton.addEventListener('click', function() {
                if (!validatePage()) return;

                savePageData(); // Save page data before moving to the next page

                if (currentPage === pages.length - 2) {
                    console.log('Current Page is Last Page');

                    // Retrieve stored values
                    const evaluatedName = JSON.parse(sessionStorage.getItem('answers'))?.evaluated?.name || '';
                    const evaluatedPosition = JSON.parse(sessionStorage.getItem('answers'))?.evaluated?.position || '';
                    const evaluatedIdNumber = JSON.parse(sessionStorage.getItem('answers'))?.evaluated?.idnumber || '';


                    console.log('Evaluated Name:', evaluatedName);
                    console.log('Evaluated Position:', evaluatedPosition);
                    console.log('Evaluated idNumber:', evaluatedIdNumber);


                    // Handle the AJAX request on the last page (page 4)
                    const responses = JSON.parse(sessionStorage.getItem('answers')) || {};

                    const data = {
                        evaluated_name: {
                            name: evaluatedName,
                            position: evaluatedPosition,
                            idnumber: evaluatedIdNumber

                        },
                        responses: responses
                    };

                    console.log('Submitting Data:', data);

                    fetch('submit_corevalue.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            console.log('Submission Result:', result);
                            if (result.success) {
                                // Clear sessionStorage after successful submission
                                sessionStorage.removeItem('answers');

                                // Update content to show thank you message
                                currentPage = pages.length - 1; // Set to the last page
                                updateContent(); // Update content to display the thank you message
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Submission Failed',
                                    text: result.error || 'There was an error submitting your responses. Please try again.',
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'swal-button-green'
                                    }
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Submission Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: 'There was an error submitting your responses. Please try again.',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'swal-button-green'
                                }
                            });
                        });
                } else {
                    currentPage++;
                    updateContent();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            backButton.addEventListener('click', function() {
                if (currentPage > 0) {
                    savePageData(); // Save page data before moving to the previous page
                    currentPage--;
                    updateContent();
                }
            });

            function onGenerateAnswer(name) {
                return `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="form-check ${name}">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-champion" value="5">
                                <label class="form-check-label" for="${name}-champion">🤩 <b>CHAMPION</b></label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-positive" value="1">
                                <label class="form-check-label" for="${name}-positive">😌 <b>POSITIVE IMPRESSION</b></label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-noComment" value="0">
                                <label class="form-check-label" for="${name}-noComment">🤷‍♂️ <b>NO COMMENT</b></label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-negative" value="-5">
                                <label class="form-check-label" for="${name}-negative">😕 <b>NEGATIVE IMPRESSION</b></label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-drag" value="-1">
                                <label class="form-check-label" for="${name}-drag">😡 <b>DRAG</b></label>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <p>If your answer to the previous was either 🤩 <b>CHAMPION</b> or 😡  <b>DRAG</b></p>
                            <div class="mb-3">
                                <textarea class="form-control" placeholder="Your Answer" id="${name}-short-explanation" required></textarea>
                            </div>                            
                        </div>
                    </div>
                    `;
            }

            // Event delegation to handle radio button changes
            document.addEventListener('change', function(event) {
                if (event.target.type === 'radio') {
                    const name = event.target.name;
                    const value = event.target.value;
                    saveAnswer(name, event.target.id, value);
                }
            });

            updateContent();
        });
    </script>

</body>

</html>