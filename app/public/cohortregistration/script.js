document.addEventListener("DOMContentLoaded", function () {
    "use strict";

    var allCountries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria",
        "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan",
        "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cote d'Ivoire", "Cabo Verde",
        "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo",
        "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", "Finland",
        "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea",
        "Guinea-Bissau", "Guyana", "Haiti", "Holy See", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran",
        "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati",
        "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania",
        "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius",
        "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar", "Namibia",
        "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway",
        "Oman", "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland",
        "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino",
        "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands",
        "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland",
        "Syria", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey",
        "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "Uzbekistan", "Vanuatu",
        "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    ];

    var form = document.getElementById("applicationForm");
    var countrySelect = document.getElementById("country");
    var submitButton = document.getElementById("submitBtn");

    function getFieldValue(id) {
        var element = document.getElementById(id);

        if (!element) {
            return "";
        }

        if (element.tagName === "SELECT") {
            if (!element.value) {
                return "";
            }

            var selectedOption = element.options[element.selectedIndex];

            if (!selectedOption || selectedOption.disabled) {
                return "";
            }
        }

        return element.value.trim();
    }

    function showError(message) {
        window.alert(message);
        return false;
    }

    function populateCountries() {
        if (!countrySelect) {
            return;
        }

        allCountries.forEach(function (country) {
            var option = document.createElement("option");
            option.value = country;
            option.textContent = country;
            countrySelect.appendChild(option);
        });
    }

    function setupInputRestrictions() {
        var phoneInput = document.getElementById("whatsapp");
        var zipInput = document.getElementById("zip");
        var emailInput = document.getElementById("email");

        if (phoneInput) {
            phoneInput.addEventListener("input", function () {
                this.value = this.value.replace(/\D/g, "").slice(0, 15);
            });
        }

        if (zipInput) {
            zipInput.addEventListener("input", function () {
                this.value = this.value.replace(/[^a-zA-Z0-9\s-]/g, "");
            });
        }

        if (emailInput) {
            emailInput.addEventListener("input", function () {
                this.value = this.value.replace(/\s/g, "");
            });
        }
    }

    function validate() {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        var phoneRegex = /^[0-9]{7,15}$/;
        var zipRegex = /^[a-zA-Z0-9\s-]{3,10}$/;
        var selectedGoals = document.querySelectorAll('input[name="goals"]:checked');

        if (!getFieldValue("parentName")) {
            return showError("Please enter the Parent Name.");
        }

        if (!getFieldValue("relation")) {
            return showError("Please select the Relation with Student.");
        }

        if (!getFieldValue("education")) {
            return showError("Please select the Highest Education.");
        }

        if (!getFieldValue("countryCode")) {
            return showError("Please select a Country Code.");
        }

        if (!phoneRegex.test(getFieldValue("whatsapp"))) {
            return showError("Enter a valid phone number with 7 to 15 digits.");
        }

        if (!emailRegex.test(getFieldValue("email"))) {
            return showError("Please enter a valid email address.");
        }

        if (!getFieldValue("address1")) {
            return showError("Please enter the Street Address.");
        }

        if (!getFieldValue("city")) {
            return showError("Please enter the City.");
        }

        if (!getFieldValue("state")) {
            return showError("Please enter the State or Province.");
        }

        if (!zipRegex.test(getFieldValue("zip"))) {
            return showError("Enter a valid postal code with 3 to 10 characters.");
        }

        if (!getFieldValue("country")) {
            return showError("Please select a Country.");
        }

        if (!getFieldValue("studentName")) {
            return showError("Please enter the Student Name.");
        }

        if (!getFieldValue("grade")) {
            return showError("Please select the Student Grade.");
        }

        if (!getFieldValue("gender")) {
            return showError("Please select the Student Gender.");
        }

        if (!getFieldValue("school")) {
            return showError("Please enter the School Name.");
        }

        if (!getFieldValue("board")) {
            return showError("Please select the School Board.");
        }

        if (!getFieldValue("careerClarity")) {
            return showError("Please select Career Clarity.");
        }

        if (selectedGoals.length === 0) {
            return showError("Please select at least one goal you want to gain from FORCE.");
        }

        return true;
    }

    function collectData() {
        var selectedGoals = Array.prototype.slice.call(document.querySelectorAll('input[name="goals"]:checked'));

        return {
            parentName: getFieldValue("parentName"),
            relation: getFieldValue("relation"),
            education: getFieldValue("education"),
            phone: getFieldValue("countryCode") + getFieldValue("whatsapp"),
            email: getFieldValue("email"),
            address: getFieldValue("address1"),
            address2: getFieldValue("address2"),
            city: getFieldValue("city"),
            state: getFieldValue("state"),
            zip: getFieldValue("zip"),
            country: getFieldValue("country"),
            studentName: getFieldValue("studentName"),
            grade: getFieldValue("grade"),
            gender: getFieldValue("gender"),
            school: getFieldValue("school"),
            board: getFieldValue("board"),
            careerClarity: getFieldValue("careerClarity"),
            goals: selectedGoals.map(function (goal) {
                return goal.value;
            }).join(", "),
            comments: getFieldValue("comments"),
            program: "cohort",
            timestamp: new Date().toISOString()
        };
    }

    function getRedirectUrl(data) {
        return "";        // India-specific payment link
        if (data && data.country === "India") {
            return "https://buy.stripe.com/14AaEYbigctq7Rr7ti4ZG01";
        }

        // Default global payment link
        return "https://buy.stripe.com/28EeVeaecctq0oZ28Y4ZG00";
    }

    function setButtonState(isSubmitting) {
        if (!submitButton) {
            return;
        }

        var text = submitButton.querySelector(".button__text");

        if (text) {
            text.textContent = isSubmitting ? "Processing..." : "Continue to Payment";
        }

        submitButton.disabled = isSubmitting;
    }

    function setupButtonAnimation() {
        if (!submitButton) {
            return;
        }

        submitButton.addEventListener("mouseenter", function () {
            submitButton.classList.remove("hover-out");
            submitButton.classList.add("hover-in");
        });

        submitButton.addEventListener("mouseleave", function () {
            submitButton.classList.remove("hover-in");
            submitButton.classList.add("hover-out");
        });
    }

    function handleSubmit(event) {
        var data;
        var webhookUrl = "https://hook.us2.make.com/3wuipz4f79h4baub9rc6r5gkdttoibne";

        event.preventDefault();

        if (!validate()) {
            return;
        }

        data = collectData();
        setButtonState(true);

        fetch(webhookUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        }).then(function (response) {
            if (!response.ok) {
                throw new Error("Failed to send to Make");
            }
            window.setTimeout(function () {
                form.submit();
            }, 1500);
        }).catch(function () {
            window.alert("Something went wrong. Please try again.");
            setButtonState(false);
        });
    }

    populateCountries();
    setupInputRestrictions();
    setupButtonAnimation();

    if (form) {
        form.addEventListener("submit", handleSubmit);
    }

    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            setButtonState(false);
        }
    });
});
