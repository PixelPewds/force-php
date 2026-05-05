<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a STEAM-X Scholar</title>
    <meta name="description" content="Apply to the FORCE Career Exploration program for high-school students.">
    <!-- <link rel="stylesheet" href="./style.css"> -->
  <link href="{{asset('cohortregistration/style.css')}}" rel="stylesheet" />

</head>
<body style="overflow-x: hidden;">
    <header class="site-header">
        <div class="header-banner" role="img" aria-label="FORCE header artwork"></div>
    </header>

    <div class="brand-strip">
        <nav class="brand-nav" aria-label="Primary">
            <a class="header-logo" href="https://forcescholar.com/" aria-label="FORCE home">
                <img src="{{asset('cohortregistration/2026 FORCE Logo_V6 1 1-Photoroom.png')}}" alt="FORCE logo">
            </a>
            <div class="nav-links">
                <a href="https://forcescholar.com/">Home</a>
            </div>
        </nav>
    </div>

    <main class="page-content">
        <form id="applicationForm" class="container" action="{{ route('stripe.create') }}" method="post">
            @csrf
            <header class="hero">
                <h1>Become a STEAM-X Scholar</h1>
                <p class="sub-text">A guided program for high-school students to explore 21st-century interdisciplinary careers, build real-world projects, and develop the confidence to make better career choices.</p>
                <p class="sub-text"><em>Best for students ready for deeper career exploration, projects, mentoring, and skill-building.</em></p>
            </header>

            <section class="form-section" aria-labelledby="parent-information-title">
                <h2 id="parent-information-title">Parent Information</h2>

                <div class="field">
                    <label for="parentName">Parent Name</label>
                    <input id="parentName" name="parentName" placeholder="Enter parent name" autocomplete="name" required>
                    <input type="hidden" name="type" value="{{ $type }}">
                </div>

                <div class="field">
                    <label for="relation">Relation with Student</label>
                    <select id="relation" name="relation" required>
                        <option value="" disabled selected>Select</option>
                        <option value="Mother">Mother</option>
                        <option value="Father">Father</option>
                        <option value="Guardian">Guardian</option>
                    </select>
                </div>

                <div class="field">
                    <label for="education">Highest Education</label>
                    <select id="education" name="education" required>
                        <option value="" disabled selected>Select</option>
                        <option value="High School">High School</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's Degree">Master's Degree</option>
                        <option value="PhD">PhD</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </section>

            <section class="form-section" aria-labelledby="contact-information-title">
                <h2 id="contact-information-title">Contact Information</h2>

                <div class="row row--phone">
                    <div class="field field--code">
                        <label for="countryCode">Country Code</label>
                        <select id="countryCode" name="countryCode" required>
                            <option value="" disabled selected>Select code</option>
                            <option value="+91">India (+91)</option>
                            <option value="+971">UAE (+971)</option>
                            <option value="+966">Saudi Arabia (+966)</option>
                            <option value="+974">Qatar (+974)</option>
                            <option value="+965">Kuwait (+965)</option>
                            <option value="+973">Bahrain (+973)</option>
                            <option value="+968">Oman (+968)</option>
                            <option value="+1">USA / Canada (+1)</option>
                            <option value="+44">UK (+44)</option>
                            <option value="+61">Australia (+61)</option>
                            <option value="+65">Singapore (+65)</option>
                            <option value="+60">Malaysia (+60)</option>
                            <option value="+880">Bangladesh (+880)</option>
                            <option value="+94">Sri Lanka (+94)</option>
                            <option value="+977">Nepal (+977)</option>
                            <option value="+92">Pakistan (+92)</option>
                            <option value="+62">Indonesia (+62)</option>
                            <option value="+63">Philippines (+63)</option>
                            <option value="+66">Thailand (+66)</option>
                            <option value="+49">Germany (+49)</option>
                            <option value="+33">France (+33)</option>
                            <option value="+39">Italy (+39)</option>
                            <option value="+34">Spain (+34)</option>
                            <option value="+31">Netherlands (+31)</option>
                            <option value="+27">South Africa (+27)</option>
                            <option value="+254">Kenya (+254)</option>
                            <option value="+20">Egypt (+20)</option>
                            <option value="+234">Nigeria (+234)</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="field field--phone">
                        <label for="whatsapp">WhatsApp Number</label>
                        <input type="tel" id="whatsapp" name="whatsapp" placeholder="Phone number" maxlength="10" inputmode="numeric" autocomplete="tel" required>
                    </div>
                </div>

                <div class="field">
                    <label for="email">Email Address</label>
                    <input id="email" name="email" type="email" placeholder="Enter email" autocomplete="email" required>
                </div>

                <div class="field">
                    <label for="address1">Street Address</label>
                    <input id="address1" name="address1" placeholder="Address Line 1" autocomplete="address-line1" required>
                </div>

                <div class="field">
                    <label for="address2">Apartment / Suite (optional)</label>
                    <input id="address2" name="address2" placeholder="Address Line 2" autocomplete="address-line2">
                </div>

                <div class="field">
                    <label for="city">City</label>
                    <input id="city" name="city" placeholder="City" autocomplete="address-level2" required>
                </div>

                <div class="field">
                    <label for="state">State / Province</label>
                    <input id="state" name="state" placeholder="State / Province" autocomplete="address-level1" required>
                </div>

                <div class="field">
                    <label for="zip">Postal Code</label>
                    <input id="zip" name="zip" placeholder="000 000" autocomplete="postal-code" required>
                </div>

                <div class="field">
                    <label for="country">Country</label>
                    <select id="country" name="country" required>
                        <option value="" disabled selected>Select a country</option>
                    </select>
                </div>
            </section>

            <section class="form-section" aria-labelledby="student-details-title">
                <h2 id="student-details-title">Student Details</h2>

                <div class="field">
                    <label for="studentName">Student Name</label>
                    <input id="studentName" name="studentName" placeholder="Enter student name" autocomplete="name" required>
                </div>

                <div class="row">
                    <div class="field">
                        <label for="grade">Grade</label>
                        <select id="grade" name="grade" required>
                            <option value="" disabled selected>Select</option>
                            <option value="Grade 8">Grade 8</option>
                            <option value="Grade 9">Grade 9</option>
                            <option value="Grade 10">Grade 10</option>
                            <option value="Grade 11">Grade 11</option>
                            <option value="Grade 12">Grade 12</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="" disabled selected>Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non-binary / Other">Non-binary / Other</option>
                            <option value="Prefer not to say">Prefer not to say</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label for="school">School Name</label>
                        <input id="school" name="school" placeholder="Enter school name" required>
                    </div>

                    <div class="field">
                        <label for="board">School Board</label>
                        <select id="board" name="board" required>
                            <option value="" disabled selected>Select</option>
                            <option value="CBSE">CBSE</option>
                            <option value="ICSE">ICSE</option>
                            <option value="IB">IB</option>
                            <option value="IGCSE">IGCSE</option>
                            <option value="State Board">State Board</option>
                            <option value="NIOS">NIOS</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="form-section" aria-labelledby="career-goals-title">
                <h2 id="career-goals-title">Career and Goals</h2>

                <div class="field">
                    <label for="careerClarity">Career Clarity</label>
                    <select id="careerClarity" name="careerClarity" required>
                        <option value="" disabled selected>Select</option>
                        <option value="No idea yet">No idea yet</option>
                        <option value="Some ideas, not sure">Some ideas, not sure</option>
                        <option value="Few options in mind">Few options in mind</option>
                        <option value="Mostly clear">Mostly clear</option>
                        <option value="Very clear">Very clear</option>
                    </select>
                </div>

                <fieldset class="field goals-field">
                    <legend>What do you want to gain from FORCE?</legend>

                    <label class="checkbox-item" for="goal-career-clarity">
                        <input type="checkbox" id="goal-career-clarity" value="Career clarity" name="goals[]">
                        <span class="custom-box" aria-hidden="true"></span>
                        <span>Career clarity</span>
                    </label>

                    <label class="checkbox-item" for="goal-confidence">
                        <input type="checkbox" id="goal-confidence" value="Confidence" name="goals[]">
                        <span class="custom-box" aria-hidden="true"></span>
                        <span>Confidence</span>
                    </label>

                    <label class="checkbox-item" for="goal-real-world-skills">
                        <input type="checkbox" id="goal-real-world-skills" value="Real-world skills" name="goals[]">
                        <span class="custom-box" aria-hidden="true"></span>
                        <span>Real-world skills</span>
                    </label>

                    <label class="checkbox-item" for="goal-exposure">
                        <input type="checkbox" id="goal-exposure" value="Exposure to industries" name="goals[]">
                        <span class="custom-box" aria-hidden="true"></span>
                        <span>Exposure to industries</span>
                    </label>

                    <label class="checkbox-item" for="goal-college-applications">
                        <input type="checkbox" id="goal-college-applications" value="Strong college applications" name="goals[]">
                        <span class="custom-box" aria-hidden="true"></span>
                        <span>Strong college applications</span>
                    </label>
                </fieldset>

                <div class="field">
                    <label for="comments">Anything else you'd like us to know?</label>
                    <textarea id="comments" name="comments" rows="4" placeholder="Optional..."></textarea>
                </div>
            </section>

            <button id="submitBtn" type="submit">
                <span class="button__text">Apply for the Lab</span>
                <span class="button__filler" aria-hidden="true"></span>
            </button>
        </form>
    </main>
    <script src="{{asset('cohortregistration/script.js')}}" defer></script>
</body>
</html>
