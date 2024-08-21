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
        .gold-border {
            border: 1px solid #D9A118;
            /* Set the border color to gold */
        }

        .red-border {
            border: 1px solid #9D241A;
            /* Set the border color to red */
        }

        .card-body {
            text-align: justify;
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
                            <p>Welcome to the MG Core Values Evaluation form. This gives an opportunity for leaders and peers in the organization to assess the experience they have had with each other and 
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
                    <div class="card mb-3 red-border">
                        <div class="card-body">
                            <p><b>Here at Mary Grace we practice the ‘way of littleness’: ‘littleness’ because we put our mission and others before ourselves, ‘littleness’ because we listen before speaking, ‘littleness’ because we choose gentleness over bossiness, ‘littleness’ because we honor the talents and contributions of each one no matter how small.</b></p>
                            <p><i>Sa Mary Grace, sinusunod namin ang 'landas ng kababaang loob': ‘kababaang loob’ dahil inuuna namin ang aming misyon at ang kapakanan ng iba bago ang sarili, ‘kababaang loob’ dahil nakikinig kami bago magbigay ng opinyon, ‘kababaang loob’ dahil pinipili namin ang pagiging mapagpakumbaba kaysa sa pagiging mayabang, at ‘kababaang loob’ dahil iginagalang namin ang mga talento at kontribusyon ng bawat isa kahit gaano man ito kaliit.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 red-border">
                        <div class="card-body">
                            <p><b>Humility 1 -  UNITY: Common Goals vs Self Promotion.</b> - The aim of our effort is always to cooperate and contribute to the shared project of our mission together—not to point to our contributions as achievements in themselves to be praised and defended. We work not so as to brag but to cooperate, to contribute.</p>
                            <p><i>Ang layunin ng ating pagsisikap ay palaging magtulungan at mag-ambag sa iisang proyekto ng ating misyon, hindi upang ipagyabang ang ating mga tagumpay na dapat lamang purihin at kilalanin. Nagtatrabaho tayo hindi para magyabang, kundi para makipagtulungan at magbigay ng kontribusyon.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 1a: </b>Makes decisions with a focus on what's best for the team, not for individual achievement / Makes desicisions with a focus on what's best for the whole organization and not just his/her own department. </p>
                            <p><i>Nagdedesisyon na ang pokus ay sa kung ano ang pinakamabuti para sa buong pangkat, hindi para sa indibidwal na tagumpay / Nagdedesisyon nang may pokus sa kung ano ang pinakamabuti para sa buong organisasyon at hindi lamang para sa kanyang sariling departamento.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-1a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 1b: </b>Promotes teamwork by focusing on things that bring us together / is not heard constantly complaining about other departments or team members / considers with empathy the challenges and setbacks of other teams and tries to approach them with the mindset of a collaborator instead of that of a critic. </p>
                            <p><i>Binibigyang-pansin ang pagsasama-sama sa pamamagitan ng pagtuon sa mga bagay na nagbubuklod sa pangkat / hindi naririnig na nagrereklamo tungkol sa ibang departamento o ibang mga kasamahan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-1b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 1c: </b>Encourages other team members to contribute to new initiatives (vis-a-vis discluding other departments so as to claim all of the credit or so as not to have to put up with other departments).</p>
                            <p><i>Nagtutulak sa iba pang miyembro ng pangkat na mag-ambag sa bagong mga inisyatiba (kumpara sa hindi pagkilala o hindi pagtanggap ng ibang departamento upang mapasakanya ang lahat ng kredito o upang hindi na kailangan pang makisama sa ibang departamento).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-1c')}</div>

                    <div class="card mb-3 red-border">
                        <div class="card-body">
                            <p><b>Humility 2 -  Accepting of feedback / accepting areas of development </b> As we’re all collaborators in our mission, feedback is one of our best ways to work together: we welcome it, we look for it, we listen carefully to it, we strive to understand it with empathy. If our guests and other partners give us feedback, that means they care enough for us to help us. That makes them collaborators (not critics) too</p>
                            <p><i> Dahil tayong lahat ay bahagi ng ating misyon, ang feedback ay isa sa mga pinakamahusay na paraan para makipagtulungan tayo: tinatanggap namin ito, hinahanap namin ito, pinakikinggan namin ng maayos, at sinisikap naming unawain ito nang may malasakit. Kapag ang ating mga customer at iba pang mga partner ay nagbibigay ng feedback, ibig sabihin ay nagmamalasakit sila at nais tayong tulungan. Ipinapakita nito na sila ay mga partner rin (hindi mga kalaban).</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 2a: </b>Accepts and uses feedback from managers and co-workers to improve performance.</p>
                            <p><i>Sumasang-ayon at gumagamit ng katugunan o tamang puna mula sa mga tagapamahala at mga kasamahan sa trabaho upang mapabuti ang pagganap.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-2a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 2b: </b>Shows initiative to work on improving their weaknesses by calling them out and proposing solutions.</p>
                            <p><i>Nagtataglay ng inisyatiba na ayusin ang kanilang mga kahinaan sa pamamagitan ng pagtukoy at pagmumungkahi ng solusyon.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-2b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 2c: </b> Asks for help from team members with other skill sets to get things done.</p>
                            <p><i>Humihingi ng tulong mula sa mga miyembro ng pangkat na may iba pang mga hanay ng kasanayan upang magawa ang mga bagay.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-2c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 2d: </b> Willing to give honest and necessary feedback to their team members (as a manifestation of humility as it shows a recognition that others -like themselves- need and deserve the opportunity to grow just as they do).</p>
                            <p><i>Handang magbigay ng tapat at kinakailangang tugon sa kanilang mga kasamahan (isang pagpapakita ng kababaang-loob dahil ito ay nagpapakita ng pagkilala sa iba - tulad ng kanilang sarili - ay nangangailangan at karapat-dapat na pagkakataong umunlad tulad nila).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-2d')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 2e: </b> Delivers negative feedback constructively and effectively (vis-a-vis lording it over those who have made mistakes, happy to point out the errors of others in a "I told you so" attitude).</p>
                            <p><i>Naghahatid ng negatibong tugon na maayos at epektibo (kumpara sa pagmamayabang sa mga taong nagkakamali, at masaya sa pagtukoy sa mga pagkakamali ng iba sa pamamagitan ng "sabi ko na sa iyo eh" na pananaw).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-2e')}</div>

                    <div class="card mb-3 red-border">
                        <div class="card-body">
                            <p><b>Humility 3 -  Promotes collaboration in dialogue settings (especially as a leader) - </b>Iterate. Iterate. Iterate. Our ideas, our processes, our systems, our structures can always be better. So we are happy to receive suggestions, improvements, re-designs, builds. We each hold but a piece of the puzzle. What we individually achieve calls out to be completed by everone else.</p>
                            <p><i>Paulit-ulit. Paulit-ulit. Paulit-ulit. Laging may paraan para mapabuti ang ating mga ideya, proseso, sistema, at estruktura. Kaya't bukas kami sa mga rekomendasyon, pagpapabuti, pagbabago, at mga pagsasaayos. Lahat tayo ay may bahagi sa kabuuan ng puzzle. Ang bawat tagumpay na naaabot natin ay nag-aanyaya sa iba na makilahok at mag-ambag.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 3a: </b> Presents their ideas in a way that encourages feedback and discussion (as opposed to declaring "this is what I though of and everyone follows it--no suggestions from others).</p>
                            <p><i>Ipinapakita ang kanilang mga ideya sa paraan ng pagtataguyod ng tugon at diskusyon (kumpara sa pagdedeklara ng "ito ang naisip ko at lahat ay susunod dito—nang walang mungkahi mula sa iba)".</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-3a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 3b: </b> Great listener and learns from their team members.</p>
                            <p><i>Isang magaling na tagapakinig at natututo mula sa kanilang mga kasamahan sa koponan.Isang magaling na tagapakinig at natututo mula sa kanilang mga kasamahan sa pangkat.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-3b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Humility 3c: </b> Open-minded and flexible with new ways of doing things.</p>
                            <p><i>Bukas ang isip at may kakayahang umangkop sa mga bagong paraan ng paggawa ng mga bagay.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('humility-3c')}</div>
                    `
                },
                {
                    header: "RESPECT",
                    content: `
                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>RESPECT </b> -  In Mary Grace we care about the whole person. We don’t just see employees or co-workers or guests—we see you. And our aim is to promote and protect your dignity always.</p>
                            <p><i>Sa Mary Grace, inuuna namin ang kapakanan ng buong pagkatao. Nakikita namin kayo hindi lamang bilang mga empleyado, katrabaho, o bisita—nakikita namin kayo bilang mga indibidwal. Layunin namin na palaging itaguyod at protektahan ang inyong dignidad.</i></p>
                            <p><b>Respect 1 - Care of the ‘when’, ‘where’, ‘how’, and ‘who’ of our communications (and not just the what). </b>What you say, how you say it, where you say it, in front of who you say it--can mean the difference between lifting someone up or breaking them down. A big part of practicing respect is found in how we take care of how we communicate and relate with each other.</p>
                            <p><i>Kung ano ang sinasabi mo, paano mo ito sinasabi, saan mo ito sinasabi, at sa harap ng kanino mo ito sinasabi --maaari itong magdulot ng pagkakaiba sa pagitan ng pag-aangat ng isang tao o pagwasak sa kanilang kalooban. Isang malaking bahagi ng pagsasanay ng respeto ay makikita sa kung paano natin pinangangalagaan ang ating pakikipag-usap at pakikitungo sa isa't isa.</i></p>
                            <p><b>Care in corrections, reprimands, and discipline. </b>It will happen that mistakes are made, that rules are broken, that people make bad decisions. RESPECT means that we correct, reprimand -even- when necessary. Feedback like this means we care for the person and his/her development. But RESPECT also means making sure that our interventions lift up and don’t tear down: insulting or foul language is never used, as much as possible reprimands are done in private. Care for the wellbeing of the person is seen to at every step of the process.</p>
                            <p><i>Mangyayari talaga na magkakamali ang mga tao, may lalabag sa mga patakaran, at gagawa ng mga maling desisyon. Ang respeto ay nangangahulugang itinatama natin sila at nagbibigay ng puna kung kinakailangan. Ang ganitong uri ng feedback ay nagpapakita ng ating malasakit sa tao at sa kanilang pag-unlad. Ngunit ang respeto rin ay nangangahulugang tinitiyak natin na ang ating mga hakbang ay nakakatulong sa kanilang pag-angat at hindi nagpapahina. Iwasan ang paggamit ng mapanlait o masasakit na salita, at kung maaari, gawin ang mga pagsasaayos sa pribado. Ang pag-aalaga sa kapakanan ng tao ay laging isinasagawa sa bawat hakbang ng proseso. Makipag-usap tayo nang may malasakit at pagkakaintindihan.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 1a: </b> Listens attentively and communications are timely, concise, and respectful. Especially manifest in the care shown when in giving feedback so that it is: -premised on careful listening - specific (vis-a-vis vague "you suck!") - focused on an area where improvement is possible and within the control of the team or individual concerned</p>
                            <p><i>Nakikinig nang mabuti at ang komunikasyon ay maagap, maigsi, at magalang. Lalong naipapakita ang malasakit sa pagbigay ng "feedback" sa pamamagitan ng: -nakabatay sa maingat na pakikinig -tiyak (kumpara sa malabong pagtugon gaya ng "wala kang kwenta!") -nakatuon sa kung saan posible ang pagpapabuti na batay loob ng kontrol ng pangkat o ng indibidwal na kinauukulan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-1a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 1b: </b> Chooses the right channel and venue to communicate feedback Chooses the right venues to open discussions or debates (ex. does not use group threads or public platforms to give criticism to one person or one department).</p>
                            <p><i>Pinipili ang tamang paraan at lugar para magbigay ng tugon / magbukas ng talakayan o debate (halimbawa: hindi gumagamit ng group threads para batikusin ang isang tao o isang departamento.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-1b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 1c: </b> Chooses the appropriate time for corrections and feedback so that teams and individuals are not humiliated.</p>
                            <p><i>Pinipili ang tamang oras para sa mga pagwawasto at tugon upang ang mga grupo at indibidwal ay hindi mapahiya.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-1c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 1d: </b>  Ensures expectations are clear, well communicated and appropriately documented (values and cares for alignment and his communications and willingness to communicate show this).</p>
                            <p><i>Sinisiguradong malinaw, maayos na ipinaaabot, at tama ang pag-uulat sa mga inaasahan (pinahahalaga at iniingatan ang pagkakatugma sa kanyang komunikasyon at ang kanyang kagustuhang makipag-ugnayan ay nagpapakita nito).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-1d')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 1e: </b> Proactively focuses on and fixes any issues that are hurting communication and teamwork.</p>
                            <p><i>Proaktibong nakatuon at inaayos ang anumang mga isyu na nag-aapekto sa komunikasyon at sa pangkat.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-1e')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Respect 2 -  Welcoming debate and contradiction /handling it in a positive and empathetic manner</b> -  We value a plurality of view points and opinions. Sometimes having rivaling ideas is the very process of arriving at the best idea. And so we learn the art of keeping opposing voices engaged in healthy dialogue: the opposing sides of any debate are not enemies, they are co-workers in arriving at the best solutions.</p>
                            <p><i>Pinahahalagahan namin ang iba't ibang pananaw at opinyon. Minsan, ang pagkakaroon ng magkasalungat na ideya ay bahagi ng proseso para makuha ang pinakamahusay na solusyon. Kaya't natututunan naming i-handle ang mga magkasalungat na opinyon sa isang produktibong pag-uusap: ang mga magkaibang pananaw sa isang diskusyon ay hindi mga kaaway, kundi mga katuwang sa paghahanap ng pinakamahusay na solusyon.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 2a: </b> Values diverse opinions and displays respect toward everyone on the team (especially in meetings).</p>
                            <p><i>Pinahahalagahan ang iba't ibang opinyon at ipinapakita ang respeto sa bawat isa sa grupo (lalo na sa mga pagpupulong).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-2a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 2b: </b> Shows empathy and understanding for others and their viewpoints.</p>
                            <p><i>Nagpapakita ng pagkakaunawa at pang-unawa sa iba at sa kanilang mga pananaw.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-2b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 2c: </b> Consistently listens and learns from their co-workers.</p>
                            <p><i>Palaging nakikinig at natututo mula sa kanilang mga kasamahan sa trabaho.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-2c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 2d: </b> Displays understanding and shows consideration for how their job impacts and effects co-workers, shared projects, and the organization as a whole.</p>
                            <p><i>Nagpapakita ng pag-unawa at pag-aalala kung paano nakaaapekto ang trabaho nila sa kanilang mga kasamahan, mga proyektong pinagkakasunduan, at sa buong organisasyon.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-2d')}</div>
                    
                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                        <p><b>Respect 3 -  Encouraging an environment that allows for individuals and teams to work with consideration</b> We belong to a community. So we have shared spaces, we have shared facilities, we have shared tools, we have a common calendar--taking care of all of these things is an act of respect to each member of our community. Picking up litter, not making too much noise, keeping our common areas clean, being punctual--these are all important acts of respect for each other and the community.</p>
                        <p><i>- Tayo ay bahagi ng isang komunidad. Kaya't mayroon tayong mga lugar na ginagamit ng sama-sama, mga pasilidad na iisa, mga kagamitan na ibinabahagi, at isang pampublikong kalendaryo—ang pangangalaga sa lahat ng ito ay isang pagpapakita ng respeto sa bawat miyembro ng ating komunidad. Ang paglilinis ng kalat, hindi paggawa ng labis na ingay, pagpapanatili ng kalinisan sa mga pampublikong lugar, at pagiging maagap—lahat ng mga ito ay mahahalagang hakbang ng respeto sa isa't isa at sa komunidad."</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 3a: </b> Works well and collaborates in group/team situations.</p>
                            <p><i>Mahusay na nagtatrabaho at nakikipagtulungan sa mga sitwasyon ng grupo/o koponan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-3a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 3b: </b> Treats co-workers (especially subordinates) as they would treat one of their best client.</p>
                            <p><i>Mahusay na nagtatrabaho at nakikipagtulungan sa mga sitwasyon ng grupo/o koponan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-3b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 3c: </b> Uses good manners and is polite and humble in all their interactions.</p>
                            <p><i>Mahusay na nagtatrabaho at nakikipagtulungan sa mga sitwasyon ng grupo/o koponan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-3c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 3d: </b> Recognizes and congratulates the achievements of others on one's team and on other teams.</p>
                            <p><i>Nakikilala at bumabati sa mga tagumpay ng iba sa kanilang grupo at sa iba pang mga grupo.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-3d')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Respect 3e: </b> Shows initiative to take care of shared spaces by cleaning up, picking up litter etc.</p>
                            <p><i>Nagtataglay ng inisyatiba na alagaan ang mga espasyong pinagsasaluhan sa pamamagitan ng paglilinis, pagpupulot ng kalat, at iba pa."</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('respect-3e')}</div>
                    `
                },
                {
                    header: "Integrity and Honesty  - Be truthful. To yourself and others, always. Yes, always.",
                    content: `
                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Integrity and Honesty 1 -  Accepting responsibility and Honors commitments </b></p>
                            <p><b>Ownership and responsibility </b>We’re all collaborators working toward the same goal. Mistakes and failures are part of the process. Taking responsibility for what goes wrong helps us more quickly recognize mistakes and come together to overcome them as a team.</p>
                            <p><b>Pagmamay-ari at responsibilidad </b><i>Lahat tayo ay kasama sa pagtutulungan para sa iisang layunin. Ang mga pagkakamali at kabiguan ay bahagi ng proseso. Ang pagtanggap ng responsibilidad para sa mga pagkakamali ay tumutulong sa atin na mas mabilis na pagkilala sa mga kamalian at pagkakabuklod upang malampasan ito bilang isang grupo."</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 1a: </b>  Accepts responsibility for their actions, admits mistakes and shows a willingness to learn from them.</p>
                            <p><i>Tumatanggap ng responsibilidad sa kanilang mga aksyon, inaamin ang mga pagkakamali, at nagpapakita ng kagustuhang matuto mula sa mga ito.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-1a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 1d: </b> Consistently delivers on commitments to build trust within our team and with our customers (for example: deadlines, deliverables) Palaging tumutupad sa mga pangako upang mabuo ang tiwala sa loob ng ating grupo at sa ating mga customer (halimbawa: mga deadlines, mga deliverables).</p>
                            <p><i>Tumatanggap ng responsibilidad sa kanilang mga aksyon, inaamin ang mga pagkakamali, at nagpapakita ng kagustuhang matuto mula sa mga ito.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-1d')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Integrity and Honesty 2 -  Stewardship (treating the business as if it were your own)</b></p>
                            <p><b>Stewardship: giving more to more. </b>Everything that the company has -its resources, its tools, its raw materials, its products, its facilities and properties- are on loan to us from God. He entrusts them to us in the hope that we use them profitably so that the company can offer more opportunities to more people. And so we must use all of our resources wisely -without waste!- knowing that what is saved is used to be able to give more to more.</p>
                            <p><b>Pagiging Katiwala: </b> Pagbibigay ng Higit sa Mas Marami<i>Ang lahat ng pag-aari ng kumpanya—ang mga yaman, mga kagamitan, mga materyales, mga produkto, mga pasilidad, at mga ari-arian—ay ipinahiram sa atin ng Diyos. Ipinagkakatiwala Niya ito sa atin na may pag-asa na magamit natin ito nang may pakinabang upang ang kumpanya ay makapagbigay ng mas maraming oportunidad sa mas maraming tao. Kaya’t dapat nating gamitin ang lahat ng ating mga yaman nang wasto—walang nasasayang!—dahil ang bawat natipid ay nagpapakita ng ating pagiging maingat at makakatulong sa pagpapalawak ng mga oportunidad para sa iba.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 1b: </b> Stewardship: Actively seeks opportunities to improve processes and eliminate waste without negatively impacting the products and services provided. </p>
                            <p><i>Pagmamalasakit: Aktibong naghahanap ng mga pagkakataon upang mapabuti ang mga proseso at alisin ang mga pag-aaksaya nang hindi nakakaapekto ng negatibo sa mga produkto at serbisyong ibinibigay.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-1b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 2b: </b> Is cost-aware and takes the initiative to detect and eliminate waste. </p>
                            <p><i>May kamalayan sa gastusin at kusang nag-iimbentaryo upang matukoy at alisin ang pag-aaksaya</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-2b')}</div>


                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 3 - Honesty and Integrity at all times</b></p>
                            <p><b>Yes. At all times and in all things. </b>Truthfulness does not just apply to how we respond to questions and inquires. It is a way of life that permeates everything we do: we are truthful in caring for the accuracy of our reports, in how we use our time well-not wasting it, in the judicious use of raw materials and resources, in the care we put in documenting how money is spent, in seeing to it that our very thinking and acting is in accord with all of our values.</p>
                            <p><b>Oo. Sa lahat ng oras at sa lahat ng bagay. </b><i>Ang pagiging matapat ay hindi lamang tungkol sa kung paano tayo sumasagot sa mga tanong at pag-uusisa. Ito ay isang paraan ng pamumuhay na sumasaklaw sa lahat ng ating ginagawa: tayo ay matapat sa pangangalaga sa mga tamang impormasyon, sa kung paano natin ginagamit ang ating oras nang wasto—hindi nag aaksaya, matalino at maingat na paggamit ng mga materyales at resources, sa pag-aalaga sa dokumentasyon kung paano ginagamit ang pera, at sa pagtiyak na ang ating pag-iisip at pagkilos ay naaayon sa lahat ng ating mga prinsipyo.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 3a: </b> Does the right thing, regardless of the situation or external pressures. </p>
                            <p><i>Ginagawa ang tama, anuman ang sitwasyon o mga panlabas na presyon.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-3a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 3b: </b>  Adheres to the highest lawful and ethical standards for their profession. </p>
                            <p><i>Susunod sa pinakamataas na legal at etikal na pamantayan para sa kanilang propesyon.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-3b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 3c: </b> Displays consistent behavior regardless of the situation (we hold our values in all settings i.e. company outings, company events). </p>
                            <p><i>Pinapakita ang parehong ugali sa lahat ng sitwasyon (itinitindig natin ang ating mga halaga sa lahat ng pagkakataon, tulad ng mga outing ng kumpanya o mga kaganapan ng kumpanya).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-3c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 3d: </b> Is truthful in all pronouncements and statements spoken, written or otherwise. </p>
                            <p><i>Tapat sa lahat ng mga pahayag at mga sinasabi, isulat man o iba pa.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-3d')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Integrity and Honesty 4 -  Active cooperation in the audit thrust of the company.</b></p>
                            <p><b>Ready for and Seeking Transparency </b>As goods and resources are entrusted to us for the purpose of carrying out operations, we make a pro-active effort to document their use, inviting all of the necessary auditing bodies to have free access to all that we do. We acknowledge that this is not motivated by a lack of a trust but a desire to protect all from suspicion and needless accusations.</p>
                            <p><b>Handa para sa at Naghahanap ng Kalinawan. </b><i>Habang ang mga produkto at resources ay ipinagkakatiwala para sa ating operations, tayo ay nagsusumikap na idokumento ang kanilang paggamit, inaanyayahan ang lahat  ng mga ahensya ng pagsusuri na magkaroon ng malayang pag-access sa lahat ng ating ginagawa. Kinikilala natin na ito ay hindi bunga ng kawalan ng tiwala kundi ng hangaring protektahan ang lahat mula sa hinala at hindi kinakailangan na mga paratang.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Integrity and Honesty 4b: </b> Collaborates willingly with corporate audit.</p>
                            <p><i>Masayang nakikipagtulungan sa korporasyon sa pagsusuri.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('integrity-and-honesty-4b')}</div>
                    `
                },
                {
                    header: "SERVICE FROM COMPASSION",
                    content: `
                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Our service comes from “malasakit”: we go all out to understand our customers (both external and internal) and because we understand them we do everything we can to help. Our service at Mary Grace is never thus just about accomplishing tasks: it’s about being someone who cares to someone who needs that care.</b></p>
                            <p><i>Ang aming serbisyo ay nagmumula sa "malasakit": ginagawa namin ang lahat upang maunawaan ang aming mga customer, kapwa panloob at panlabas. Dahil nauunawaan namin sila, ginagawa namin ang lahat ng aming makakaya upang tumulong. Ang aming serbisyo sa Mary Grace ay hindi lamang tungkol sa pagtatapos ng mga gawain; ito ay tungkol sa pagiging isang taong nagmamalasakit sa isang taong nangangailangan ng malasakit.</i></p>
                            <p><b>Service from Compassion 1 -  The MG way</b> <i>" Ang Paraan ng MG "</i></p>
                            <p><b>Compassionate Service: more than a value, it’s our identity.</b> All of our values, all of our culture, is aimed at one thing: service. Taking care to live our values means creating an organization in which everyone’s priority is to listen, understand, and then serve. Serving our guests well is but an overflow of the great service we give each other.</p>
                            <p><b>Mapagmalasakit na Serbisyo: Higit Pa sa Isang Halaga, Ito ang Aming Pagkatao - hindi lang sya basta value-- kasi yun ay tayo.</b> <i>  Ang lahat ng aming mga halaga, ang lahat ng aming kultura, ay nakatuon sa isang bagay: serbisyo. Ang pag-aalaga na isabuhay ang aming mga halaga ay nangangahulugang paglikha ng isang organisasyon kung saan ang prayoridad ng bawat isa ay makinig, umunawa, at pagkatapos ay maglingkod. Ang mahusay na paglilingkod sa aming mga bisita ay isang bunga lamang ng mahusay na serbisyo na ibinibigay namin sa isa't isa.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 1a: </b>  Takes responsibility for maintaining a positive attitude, energy and work ethic (setting the tone of the organization especially in peak or high stress situations).</p>
                            <p><i>Tumutugon sa pagpapanatili ng positibong pananaw, enerhiya, at etika sa trabaho (nagtatakda ng tono ng organisasyon lalo na sa mga panahon ng mataas na stress).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-1a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 1b: </b>  Genuinely cares about each client's success as much as their own.</p>
                            <p><i>Taos-pusong Pinahahalagahan ang Tagumpay ng Bawat Kliyente Katulad ng Sarili Nilang Tagumpay.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-1b')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Service from Compassion 2 - Pro active Malasakit.</b><i>"Maagap na Pagmamalasakit"</i></p>
                            <p><b>Pro-active Malasakit</b> Our service is not a sluggish and slow, looking for excuses. It is attentive and looks to act before it is asked. It finds ways. It is ingenious and generous with its talents and gifts.</p>
                            <p><b>Pro-aktibong Malasakit. </b> <i>Ang aming serbisyo ay hindi tamad at mabagal na naghahanap ng mga palusot. Ito ay maagap at kumikilos bago pa man ito hilingin. Naghahanap ito ng mga paraan. Ito ay malikhain at mapagbigay sa kanyang mga talento at regalo.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 2a: </b>  Proactively offers assistance and support to co-workers and new team members.</p>
                            <p><i>Kusang-loob na Nag-aalok ng Tulong at Suporta sa Mga Kasamahan at Bagong Miyembro ng Koponan.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-2a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 2b: </b>  Considers what might be important to each client, anticipates needs and then acts proactively.</p>
                            <p><i>Iniisip kung ano ang mahalaga sa bawat kliyente, iniuunawa ang pangangailangan, at nagkilos nang maagap.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-2b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 2c:  Listens carefully </b> to customer objectives to understand and provide for customer needs (especially internal customers).</p>
                            <p><i>Maingat na Nakikinig sa Layunin ng Customer upang Maunawaan at Maibigay ang Kanilang Pangangailangan (Lalo na sa Mga Internal na Customer).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-2c')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Service from Compassion 3 - Taking time to develop others, Taking the time care for others </b></i></p>
                            <p><b>Investing time in each other</b> We acknowledge that in the fast paced world of food service, there never seems to be enough time for even our most basic tasks. While that may be so, we neverthrelss are commited to take the time to check on each other, to listen, to offer help, to console. We are especially generous in offering to teach and coach those in our teams who could benefit from our experience and learning.</p>
                            <p><b>Paglalaan ng Oras para sa Isa't Isa. </b> <i>Aminado kami na sa mabilis na takbo ng mundo ng serbisyo sa pagkain, parang laging kulang ang oras para sa kahit na ang aming mga pinakapangunahing gawain. Bagaman totoo ito, kami ay nananatiling nakatuon na maglaan ng oras para kamustahin ang isa't isa, makinig, mag-alok ng tulong, at magbigay ng kaaliwan. Kami ay lalo pang mapagbigay sa pag-aalok na magturo at mag-coach sa mga miyembro ng aming koponan na maaaring makinabang mula sa aming karanasan at kaalaman.</i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 3c: </b> Shares their job knowledge and mentors less experienced team members.</p>
                            <p><i>Nagsasabuhay ng kanilang kaalaman sa trabaho at nagme-mentor sa mga mas bata pang miyembro ng koponan</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-3c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Service from Compassion 3d: </b> Shows concern for others (team mates, persons in other departments, guests etc) by simply asking how each one is and taking the time to listen.</p>
                            <p><i>Nagpapakita ng Pag-aalala sa Iba (mga kasamahan sa koponan, mga tao sa ibang departamento, mga bisita, atbp.) sa simpleng pagtatanong kung kamusta sila at paglalaan ng oras na makinig.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('service-from-compassion-3d')}</div>
                    `
                },
                {
                    header: "Thriving Through Excellence",
                    content: `
                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>At Mary Grace we hold dear what our founder always encouraged: "littleness on the journey to excellence" which means working in little ways each day to be better than we were previously, never satisfied with what has been acheived thus far, never scorning the value of the little improvement, the little win of each day.</b></p>
                            <p><i>Sa Mary Grace, mahalaga sa amin ang ipinapayo ng aming tagapagtatag: "maliit na pagbabago sa paglalakbay tungo sa kahusayan," na nangangahulugang pagtatrabaho sa mga maliit na paraan araw-araw upang maging mas mahusay kaysa dati, hindi kailanman kuntento sa mga naabot na hanggang sa ngayon, hindi kailanman itinatanggi ang halaga ng maliit na pagpapabuti, ang maliit na tagumpay ng bawat araw.</i></p>
                            <p><b>Thriving Through Excellence 1 -  Excellence and commitment.</b></p>
                            <p><b>What’s little is not little </b>We must remember that Mary Grace as a company got to be where it is today not by trying to get “BIG” by doing “BIG” things. Rather, we got here by patiently and carefully attending to the task of each moment--done well and done for love. Little things. Done well and done for love. That is all. That is everything. It will always be this way no matter how “BIG” we get.</p>
                            <p><b>"Ang maliit ay hindi maliit." </b>Kailangan nating tandaan na ang Mary Grace bilang isang kumpanya ay nakarating sa kung saan ito ngayon hindi sa pamamagitan ng pagtatangkang maging "MALAKI" sa pamamagitan ng paggawa ng "MALALAKING" bagay. Sa halip, narating natin ang narito sa pamamagitan ng pasensya at maingat na pag-aasikaso sa bawat sandali--ginagawa nang maayos at ginagawa para sa pagmamahal. Maliit na bagay. Ginagawa nang maayos at ginagawa para sa pagmamahal. Iyan ang lahat. Iyan ang lahat. Ganito palagi kahit gaano pa tayo naging "MATAGUMPAY"</p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 1a: </b>  Takes pride in their work and and holds themselves to a very high standard of performance (shows awareness for his/her team targets, KRAs, KPIs).</p>
                            <p><i>Nakakaramdam ng pagmamalaki sa kanilang trabaho at itinataguyod ang kanilang sarili sa napakataas na pamantayan ng pagganap (nagpapakita ng kamalayan para sa mga target ng kanilang grupo, KRAs, KPIs).</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-1a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 1b: </b> Completes their work correctly the first time without taking short-cuts.</p>
                            <p><i>Natapos ang kanilang trabaho nang tama sa unang pagkakataon nang walang mga shortcut.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-1b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 1c: </b> Displays persistence to overcome challenges, setbacks and obstacles.</p>
                            <p><i>Nagpapakita ng pagpupunyagi upang lampasan ang mga hamon, pagsubok, at mga balakid.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-1c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 1d: </b> Demonstrates care in the little details of each task.</p>
                            <p><i>Nagpapakita ng pag-aalaga sa mga maliit na detalye ng bawat gawain.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-1d')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 2-   Continuous improvement (good to great - Kaizen)</b></p>
                            <p><b>Is it good? Can we make it better?</b> At Mary Grace we are always on the look out for improvement where ever it can be found. Leaders and team-members alike take the initiative to find ways to innovate. We keep tabs on new tech and learning, seeing how they might make increase the impact of our efforts. The statement “I have a suggestion” is always welcome, is always listened to.</p>
                            <p><b>Maganda na ba ito? Maaari pa ba nating pagandahin? </b><i>Sa Mary Grace, palagi naming hinahanap ang mga paraan para mapabuti ang anumang aspeto ng aming trabaho. Ang parehong mga lider at kasamahan sa trabaho ay aktibong naghahanap ng mga pagkakataon para sa paglikha ng bagong ideya. Patuloy naming sinusubaybayan ang mga bagong teknolohiya at kaalaman upang makita kung paano nila mapapalakas ang aming mga pagsisikap. Ang pahayag na "Mayroon akong mungkahi" ay palaging tinatanggap at pinakikinggan. </i></p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 2a: </b> Generates new ideas to continually improve the services we offer to our customers.</p>
                            <p><i>Nagbubuo ng mga bagong ideya upang patuloy na mapabuti ang mga serbisyong inaalok natin sa ating mga customer.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-2a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 2b: </b> Seeks out and uses new technology to improve the effectiveness of their work.</p>
                            <p><i>Naghahanap at gumagamit ng bagong teknolohiya upang mapabuti ang pagiging epektibo ng kanilang trabaho.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-2b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 2c: </b> Actively looks for ways to improve processes.</p>
                            <p><i>Aktibong naghahanap ng mga paraan upang mapabuti ang mga proseso.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-2c')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 2d: </b> Responds positively to problems as opportunities for positive change.</p>
                            <p><i>Tumutugon nang positibo sa mga problema bilang mga pagkakataon para sa positibong pagbabago.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-2d')}</div>

                    <div class="card mb-3 gold-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 3 -  Personal Growth and Development</b></p>
                            <p><i>Paglago at Pag-unlad Personal</i>.</p>
                        </div>
                    </div>
            
                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 3b: </b> Proactively seeks opportunities for self development and continuous improvement.</p>
                            <p><i>Nangunguna sa paghahanap ng mga pagkakataon para sa sariling pag-unlad at patuloy na pagpapabuti.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-3b')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 4 -  Iniative and independence</b></p>
                            <p><i>Inisyatiba at Kalayaan</i>.</p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 4b: </b>Takes action when needed, without waiting to be asked by a manager.</p>
                            <p><i>Kumikilos kapag kinakailangan, nang walang hinihintay na utos mula sa isang manager.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-4b')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 5 -  Adaptability</b>Kakayahang Mag-angkop</p>
                            <p><b>Rolling with the punches</b>A now common diagnosis of the world is that it is VUCA -- volatile, unpredictable, complex, and ambiguous. At Mary Grace, we understand that the food and beverage space is likely doubly VUCA. While our systems and procedures are designed to create predictability and reliability across our operations, we welcome the unexpected, adapting, re-organizing, not complaining. We thrive in crises, welcoming them as the accelerators of improvement and innovation.</p>
                            <p><b>Rolling with the punches (sumasabay tayo sa agos ng _______ panahon/buhay.... pag nag punch ng negative balikan natin ng positive! </b>Isang karaniwang pagsusuri ngayon sa mundo ay ito ay VUCA -- pabagu-bago, hindi mahulaan, kumplikado, at malabo. Sa Mary Grace, nauunawaan namin na ang industriya ng pagkain at inumin ay malamang na doble ang VUCA. Habang ang aming mga sistema at pamamaraan ay idinisenyo upang lumikha ng katiyakan at pagiging maaasahan sa buong operasyon, tinatanggap namin ang hindi inaasahan, nag-aangkop, nag-aayos muli, hindi nagrereklamo. Nagbubunga kami sa gitna ng krisis, tinatanggap ito bilang mga tagapagpabilis ng pagpapabuti at inobasyon.</p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 5a: </b>Anticipates risks and plans for contingencies.</p>
                            <p><i>Inaasahan ang mga panganib at nagplaplano para sa mga kapalit na plano.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-5a')}</div>
                    
                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 5b: </b>Willing to accept and embrace new directives from management, giving new directives a chance and offering constructive feedback.</p>
                            <p><i>Handang tanggapin at tumangap ang bagong mga direktiba mula sa pamamahala, binibigyan ng mga bagong direktiba ng pagkakataon at nag-aalok ng konstruktibong feedback.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-5b')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 6 -  Critical thinking</b>Mapanuri o Mapanuring Pag-iisip</p>
                            <p><b>Critical Thinking and Data Driven</b>“Littleness on the journey to excellence” also means prizing raw critical thinking and data driven decision making over showy qualifications and titles. From the lowest ranking employee to the highest, all can be trained and thus expected to think logically, check assumptions against data, and make sound decisions.</p>
                            <p><b>Kritikal na Pag-iisip at Pagpapasya Batay sa Datos</b>Ang "maliit na pagbabago sa paglalakbay tungo sa kahusayan" ay nangangahulugang binibigyan natin ng halaga ang tapat na pagsusuri at paggawa ng desisyon batay sa mga konkretong ebidensya kaysa sa mga magagarang kwalipikasyon at ranggo. Mula sa pinakamababang ranggo hanggang sa pinakamataas, inaasahan natin ang bawat isa na mag-isip nang maayos, suriin ang mga opinyon batay sa mga ebidensya, at gumawa ng mga desisyon na may batayan.</p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 6a: </b>Considers profitability when evaluating alternative solutions to existing problems Iniisip ang kikitain sa pag-evaluate ng alternatibong solusyon sa mga umiiral na problema.</p>
                            <p><i>Handang tanggapin at tumangap ang bagong mga direktiba mula sa pamamahala, binibigyan ng mga bagong direktiba ng pagkakataon at nag-aalok ng konstruktibong feedback.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-6a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 6b: </b>Evaluates and chooses methods on the basis of their suitablitly to achieving objectives.</p>
                            <p><i>Pinag-iisipang mabuti at pinipili ang mga paraan batay sa kanilang kahusayan sa pagkakamit ng mga layunin.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-6b')}</div>

                    <div class="card mb-3 red-border"> 
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 7 -  Data Driven</b>Batay sa Datos</p>
                            <p><b>Critical Thinking and Data Driven</b>“Littleness on the journey to excellence” also means prizing raw critical thinking and data driven decision making over showy qualifications and titles. From the lowest ranking employee to the highest, all can be trained and thus expected to think logically, check assumptions against data, and make sound decisions.</p>
                            <p><b>Kritikal na Pag-iisip at Pagpapasya Batay sa Datos</b>Ang "maliit na pagbabago sa paglalakbay tungo sa kahusayan" ay nangangahulugang pinahahalagahan ang tapat na kritikal na pag-iisip at pagpapasya batay sa datos kaysa sa mga palabas na kwalipikasyon at titulo. Mula sa pinakamababang ranggo na empleyado hanggang sa pinakamataas, lahat ay maaaring sanayin at kaya inaasahang mag-isip nang lohikal, suriin ang mga palagay batay sa datos, at gumawa ng matalinong desisyon.</p>
                        </div>
                    </div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 7a: </b>Executes the plan according to defined strategy and processes.</p>
                            <p><i>Isinasagawa ang plano ayon sa tinukoy na estratehiya at proseso.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-7a')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 7b: </b>Executes the plan according to defined strategy and processes.</p>
                            <p><i>Isinasagawa ang plano ayon sa tinukoy na estratehiya at proseso.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-7b')}</div>

                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p><b>Thriving Through Excellence 7c: </b>Executes the plan according to defined strategy and processes.</p>
                            <p><i>Isinasagawa ang plano ayon sa tinukoy na estratehiya at proseso.</i></p>
                        </div>
                    </div>
                    <div>${onGenerateAnswer('thriving-through-excellence-7c')}</div>
                    `
                },
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
                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <div class="form-check ${name}">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-champion" value="5">
                                <label class="form-check-label" for="${name}-champion">🤩 <b>CHAMPION</b> - There are STAND-OUT instances when he or she has demonstrated this trait in a way that is worth commending (May mga natatanging pagkakataon na ipinakita niya o siya ang katangiang ito na karapat-dapat purihin).</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-positive" value="1">
                                <label class="form-check-label" for="${name}-positive">😌 <b>POSITIVE IMPRESSION</b> - As far as I can tell, he/she generally demonstrates the trait BUT NOTHING STANDS OUT for commendation (Sa abot ng aking kaalaman, karaniwang ipinapakita niya o siya ang katangiang ito PERO WALANG NAKIKITA NA NAKATANGI para sa pagkilala).</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-noComment" value="0">
                                <label class="form-check-label" for="${name}-noComment">🤷‍♂️ <b>NO COMMENT</b> - NOTHING COMES TO MIND when I think of the person and this trait (neither positive nor negative) (WALANG NAG-UUSAP sa aking isip kapag iniisip ko ang tao at ang katangiang ito (ni positibo o negatibo)).</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-negative" value="-5">
                                <label class="form-check-label" for="${name}-negative">😕 <b>NEGATIVE IMPRESSION</b>  - As far as I can tell, he/she tends to do the OPPOSITE of this trait BUT NO INSTANCE STANDS OUT worth citing (Sa abot ng aking kaalaman, parang ginagawa niya o siya ang KABALIKTARAN ng katangiang ito PERO WALANG NAKIKITA NA NAKATANGI na dapat banggitin).</label>
                            </div>
                            <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="${name}" id="${name}-drag" value="-1">
                                <label class="form-check-label" for="${name}-drag">😡 <b>DRAG</b>  - There are STAND-OUT instances when the person has VIOLATED the value. I can recount the instance to have a conversation regarding it ( May mga natatanging pagkakataon na nilabag ng tao ang halaga. Maaari kong ikwento ang pagkakataon upang magkaroon ng pag-uusap tungkol dito).</label>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 gold-border">
                        <div class="card-body">
                            <p>If your answer to the previous was either 🤩 <b>CHAMPION</b> or 😡  <b>DRAG</b> please provide a short written account of the SPECIFIC instance(s) that demonstrate this. As much as possible provide details such as date, place, and if anyone else can confirm the instance.</p>
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