<?php
  $title       = "Apply now at EcoCity Co.";
  $description = "job application form, application, validation, regex";
  $keywords    = "HTML5, apply portal, applicant details";
  $author      = "Ruby Telford, 105916092";
  $pageCSS     = "";
?>
<?php include 'header.inc'; ?>
<style>
</style>
  <main>
      <article>
        <h1>EcoCity Co Job Application Form</h1>
        <form
          action="process_eoi.php"
          method="post"
          novalidate
        >
          <fieldset>
            <legend>Job Reference Number</legend>
            <p>
              <input
                type="text"
                name="job_ref_num"
                id="job_ref_num"
                size="10"
              />
            </p>
          </fieldset>

          <fieldset>
            <legend>Personal Information</legend>
            <p>
              <label for="first_name"> First Name </label>
              <input
                type="text"
                name="first_name"
                id="first_name"
                size="10"
              />
            </p>
            <p>
              <label for="last_name"> Last Name </label>
              <input
                type="text"
                name="last_name"
                id="last_name"
                size="10"
              />
            </p>
            <p>
              <label for="dob"> Date of Birth </label>
              <input
                type="text"
                name="dob"
                id="dob"
                placeholder="dd/mm/yyyy"
              />
            </p>
            <fieldset>
              <legend>Gender</legend>
              <label for="female">Female</label>
              <input
                type="radio"
                id="female"
                name="gender"
                value="female"
              />
              &emsp;
              <label for="male">Male</label>
              <input type="radio" id="male" name="gender" value="male" />
              &emsp;
              <label for="other">Other</label>
              <input type="radio" id="other" name="gender" value="other" />
            </fieldset>
          </fieldset>

          <fieldset>
            <legend>Address Details</legend>
            <p>
              <label for="street_address">Street Address</label>
              <input
                type="text"
                name="street_address"
                id="street_address"
                size="10"
              />
            </p>
            <p>
              <label for="suburb_town">Suburb/Town</label>
              <input
                type="text"
                name="suburb_town"
                id="suburb_town"
                size="10"
              />
            </p>
            <p>
              <label for="state">State</label>
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
              <label for="postcode">Postcode</label>
              <input
                type="text"
                name="postcode"
                id="postcode"
                size="10"

              />
            </p>
          </fieldset>
          <fieldset>
            <legend>Contact Details</legend>
            <p>
              <label for="email">Email</label>
              <input
                type="text"
                name="email"
                id="email"
                size="10"
              />
            </p>
            <p>
              <label for="phone">Phone Number</label>
              <input
                type="text"
                name="phone"
                id="phone"
                size="10"
              />
            </p>
          </fieldset>
          <fieldset>
            <legend>Skills</legend>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="iot"
                id="iot"
              />
              <label for="iot">Internet Of Things</label>
            </p>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="data"
                id="data"
              /><label for="data">Data Analysis</label>
            </p>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="urban"
                id="urban"
              /><label for="urban">Urban Planning</label>
            </p>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="renewable"
                id="renewable"
              /><label for="renewable">Renewable Energy</label>
            </p>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="problem"
                id="problem"
              /><label for="problem">Problem Solving</label>
            </p>
            <p>
              <input
                type="checkbox"
                name="skill[]"
                value="teamwork"
                id="teamwork"
              /><label for="teamwork">Teamwork</label>
            </p>
            <p></p>
            <p>
              <textarea
                id="other_skills"
                name="other_skills"
                placeholder="Please Add Other Skills Here"
                cols="40"
                rows="6"
              ></textarea>
            </p>
          </fieldset>
          <input type="submit" value="Apply" />
          <input type="reset" value="Reset Form" />
        </form>
      </article>
    </main>
    
  
<?php include 'footer.inc'; ?>
