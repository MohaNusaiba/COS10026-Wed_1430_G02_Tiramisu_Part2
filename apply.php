<?php
  /*
   * apply.php - Job Application Form
   * Collects applicant details and submits to process_eoi.php
   * All validation is handled server-side - novalidate disables browser checks
   */
  $title       = "Apply now at EcoCity Co.";
  $description = "job application form, application, validation, regex";
  $keywords    = "HTML5, apply portal, applicant details";
  $author      = "Ruby Telford, 105916092";
  $pageCSS     = "styles/apply.css"; // External CSS - apply page specific styles
?>
<?php include 'header.inc'; ?>
 
<!-- 
  Embedded CSS - page specific styles that complement the external apply.css
  These styles are unique to the apply page and not needed globally
-->
<style>
  /* Apply page heading - uppercase with decorative underline accent */
  article h1 {
    text-align: center;
    color: #05368f;
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    margin-bottom: 0.5rem;
  }
 
  /* Blue decorative line rendered below the heading using a pseudo element */
  article h1::after {
    content: '';
    display: block;
    width: 60px;
    height: 3px;
    background: #05368f;
    margin: 10px auto 2rem auto;
    border-radius: 2px;
  }
 
  /* Required field indicator - shown next to labels using ::after */
  .required::after {
    content: ' *';
    color: #c0392b;
    font-weight: bold;
  }
 
  /* Instruction text below the heading to guide the applicant */
  .form-intro {
    text-align: center;
    color: #475569;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
  }
</style>
 
<main>
  <article>
    <h1>Application Form</h1>
    <p class="form-intro">Fields marked with <strong>*</strong> are required. All validation is performed on submission.</p>
 
    <!--
      Form posts to process_eoi.php for server-side processing.
      novalidate disables browser HTML5 validation so all checks
      happen in process_eoi.php using PHP regex and filter_var.
    -->
    <form action="process_eoi.php" method="post" novalidate>
 
      <!-- ===== Job Reference ===== -->
      <fieldset>
        <legend>Job Reference Number</legend>
        <p>
          <!-- Label linked to input via matching for/id attributes -->
          <label for="job_ref_num" class="required">Job Reference</label>
          <!-- 5 character alphanumeric e.g. SC123 - validated server side with regex -->
          <input
            type="text"
            name="job_ref_num"
            id="job_ref_num"
            maxlength="5"
            placeholder="e.g. SC123"
          />
        </p>
      </fieldset>
 
      <!-- ===== Personal Information ===== -->
      <fieldset>
        <legend>Personal Information</legend>
 
        <p>
          <label for="first_name" class="required">First Name</label>
          <!-- Letters only, max 20 characters - server side regex: /^[A-Za-z]{1,20}$/ -->
          <input
            type="text"
            name="first_name"
            id="first_name"
            maxlength="20"
            placeholder="Enter your first name"
          />
        </p>
 
        <p>
          <label for="last_name" class="required">Last Name</label>
          <!-- Letters only, max 20 characters - server side regex: /^[A-Za-z]{1,20}$/ -->
          <input
            type="text"
            name="last_name"
            id="last_name"
            maxlength="20"
            placeholder="Enter your last name"
          />
        </p>
 
        <p>
          <label for="dob" class="required">Date of Birth</label>
          <!-- Must match dd/mm/yyyy format - validated server side with regex -->
          <input
            type="text"
            name="dob"
            id="dob"
            placeholder="dd/mm/yyyy"
          />
        </p>
 
        <!-- Gender radio buttons - one must be selected, validated server side -->
        <fieldset>
          <legend>Gender <span style="color:#c0392b;">*</span></legend>
          <p>
            <input type="radio" id="female" name="gender" value="female" />
            <label for="female">Female</label>
          </p>
          <p>
            <input type="radio" id="male" name="gender" value="male" />
            <label for="male">Male</label>
          </p>
          <p>
            <input type="radio" id="other" name="gender" value="other" />
            <label for="other">Other</label>
          </p>
        </fieldset>
      </fieldset>
 
      <!-- ===== Address Details ===== -->
      <fieldset>
        <legend>Address Details</legend>
 
        <p>
          <label for="street_address" class="required">Street Address</label>
          <!-- maxlength=40 matches DB column VARCHAR(40) as per assignment spec -->
          <input
            type="text"
            name="street_address"
            id="street_address"
            maxlength="40"
            placeholder="Enter your street address"
          />
        </p>
 
        <p>
          <label for="suburb_town" class="required">Suburb / Town</label>
          <input
            type="text"
            name="suburb_town"
            id="suburb_town"
            maxlength="40"
            placeholder="Enter your suburb or town"
          />
        </p>
 
        <p>
          <label for="state" class="required">State</label>
          <!-- Dropdown values match $valid_states array in process_eoi.php -->
          <select name="state" id="state">
            <option value="">Please Select</option>
            <option value="VIC">VIC</option>
            <option value="NSW">NSW</option>
            <option value="QLD">QLD</option>
            <option value="NT">NT</option>
            <option value="WA">WA</option>
            <option value="SA">SA</option>
            <option value="TAS">TAS</option>
            <option value="ACT">ACT</option>
          </select>
        </p>
 
        <p>
          <label for="postcode" class="required">Postcode</label>
          <!-- Exactly 4 digits - validated server side with regex /^\d{4}$/ -->
          <input
            type="text"
            name="postcode"
            id="postcode"
            maxlength="4"
            placeholder="e.g. 3122"
          />
        </p>
      </fieldset>
 
      <!-- ===== Contact Details ===== -->
      <fieldset>
        <legend>Contact Details</legend>
 
        <p>
          <label for="email" class="required">Email Address</label>
          <!-- Validated server side using PHP filter_var with FILTER_VALIDATE_EMAIL -->
          <input
            type="text"
            name="email"
            id="email"
            placeholder="Enter your email address"
          />
        </p>
 
        <p>
          <label for="phone" class="required">Phone Number</label>
          <!-- 8 to 12 digits or spaces - validated server side with regex -->
          <input
            type="text"
            name="phone"
            id="phone"
            maxlength="12"
            placeholder="e.g. 0412345678"
          />
        </p>
      </fieldset>
 
      <!-- ===== Skills ===== -->
      <fieldset>
        <legend>Skills</legend>
        <!--
          Checkboxes submitted as skill[] array to process_eoi.php
          At least one checkbox OR other_skills text must be provided
        -->
        <p>
          <input type="checkbox" name="skill[]" value="iot" id="iot" />
          <label for="iot">Internet of Things</label>
        </p>
        <p>
          <input type="checkbox" name="skill[]" value="data" id="data" />
          <label for="data">Data Analysis</label>
        </p>
        <p>
          <input type="checkbox" name="skill[]" value="urban" id="urban" />
          <label for="urban">Urban Planning</label>
        </p>
        <p>
          <input type="checkbox" name="skill[]" value="renewable" id="renewable" />
          <label for="renewable">Renewable Energy</label>
        </p>
        <p>
          <input type="checkbox" name="skill[]" value="problem" id="problem" />
          <label for="problem">Problem Solving</label>
        </p>
        <p>
          <input type="checkbox" name="skill[]" value="teamwork" id="teamwork" />
          <label for="teamwork">Teamwork</label>
        </p>
 
        <!-- Optional free text field for any additional skills not listed above -->
        <p>
          <!-- Label linked to textarea via matching for/id attributes -->
          <label for="other_skills">Other Skills</label>
          <textarea
            id="other_skills"
            name="other_skills"
            placeholder="Add any other relevant skills here..."
            cols="40"
            rows="5"
          ></textarea>
        </p>
      </fieldset>
 
      <!--
        Submit and Reset buttons
        Inline CSS on submit draws attention to the primary action
        Reset styled via apply.css
      -->
      <input
        type="submit"
        value="Submit Application"
        style="background: linear-gradient(135deg, #05368f, #0a4dbf); color: white; padding: 12px 32px; font-size: 1rem; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; margin-top: 0.5rem; margin-right: 12px; box-shadow: 0 4px 12px rgba(5,54,143,0.25);"
      />
      <input type="reset" value="Reset Form" />
 
    </form>
  </article>
</main>
 
<?php include 'footer.inc'; ?>