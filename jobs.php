<?php
  $title       = "Careers - EcoCity Co.";
  $description = "Job posting description, Essential and preferable requirements, Key responsibilities";
  $keywords    = "job vacancy, careers, job roles";
  $author      = "Nusaiba Mohammed, 104649533";
  $pageCSS     = "";
?>
<?php include 'header.inc'; ?>
<style>
          .job aside {
          float:right;
          width: 25%;
          margin-right: 1.0em;
          padding: 0 0.5em 0 0.5em;
          border: 1px dotted black;
          border-radius: 10px;
          color: black;
          background-color: rgb(193, 255, 193);
          font-size: 14px;
          font-style: italic;
          font-weight: bold;
          box-shadow: 2px 5px 10px rgba(133, 255, 52, 0.7); 
      }

      .job aside:hover {
          transform: scale(1.1);
      }
</style>
    <main>
      <!-- Section for all job Pictures and link to Job Posting Info -->
      <section class="jobCards">
        <h1 class="jobsTitle" style="text-align: center; padding: 0;color:#0c5401; font-style:italic;">Jobs at Eco City Co.</h1>
        <p>
          At EcoCity Co., we work with councils and industry partners to deliver
          smart, sustainable urban solutions that make a real impact. We value
          innovation, collaboration, and continuous learning, and provide
          opportunities to work on meaningful projects that shape the future of
          cities while supporting your professional growth.
        </p>
        <div id="card-area">
          <div class="wrapper">
            <div class="box-area">
              <div class="box">
                <img
                  alt="Two professionals analysing code and data on multiple computer screens in an office environment"
                  src="images/analyst.png">
                <div class="overlay">
                  <h2>Smart Transport Systems Analyst</h2>
                  <p>Drive smarter mobility solutions for connected cities.</p>
                  <a href="#systemAnalyst">View Job</a>
                </div>
              </div>
              <div class="box">
                <img
                  alt="Engineers in safety helmets reviewing plans on a construction site for infrastructure development"
                  src="images/engineer.jpg">
                <div class="overlay">
                  <h3>Energy Monitoring Solutions Engineer</h3>
                  <p>
                    Power urban sustainability with innovative energy insights.
                  </p>
                  <a href="#engineer">View Job</a>
                </div>
              </div>
              <div class="box">
                <img
                  alt="Professional presenting data charts and graphs on a board during a business analysis meeting"
                  src="images/manager.jpg">
                <div class="overlay">
                  <h3>Smart City Project Manager</h3>
                  <p>Lead projects that transform cities for the future.</p>
                  <a href="#projManager">View Job</a>
                </div>
              </div>
              <div class="box">
                <img
                  alt="Confident business consultant standing in a modern office with arms crossed"
                  src="images/HRjob.jpg">
                <div class="overlay">
                  <h3>Human Resources & Talent Specialist</h3>
                  <p>Build the teams that make smart cities happen.</p>
                  <a href="#hresources">View Job</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section>
        <h1 id="careers">Current Opportunities</h1>

        <!-- Job 1 -->
        <section class="job" id="systemAnalyst">
          <aside>
            <p>
              Quaterly: May require site visits and collaboration with local
              councils.
            </p>
          </aside>

          <h2>Reference: SC123</h2>
          <h3>Smart Transport Systems Analyst</h3>
          <p class="job-desc">
            Design and optimise intelligent transport systems using real-time
            data and digital platforms to improve urban mobility outcomes.
            <br>We welcome and encourage applications from Aboriginal and
            Torres Strait Islander peoples, as well as individuals from all
            cultural and diverse backgrounds.
          </p>

          <p><strong>Salary:</strong> $95,000 – $115,000 AUD</p>
          <p><strong>Reports to:</strong> Senior Infrastructure Manager</p>

          <details>
            <summary>Key Responsibilities</summary>
            <ul>
              <li>
                Analyse transport data from IoT sensors and traffic systems
              </li>
              <li>
                Develop models to improve traffic flow and reduce congestion
              </li>
              <li>Collaborate with councils on smart mobility strategies</li>
              <li>Support deployment of integrated transport platforms</li>
            </ul>
          </details>

          <details>
            <summary>Essential Requirements</summary>
            <ol>
              <li>Bachelor’s degree in Engineering, IT, or related field</li>
              <li>Experience with data analytics and transport systems</li>
              <li>Proficiency in Python or similar tools</li>
            </ol>
          </details>

          <details>
            <summary>Preferable Skills</summary>
            <ul>
              <li>Experience in smart city or government projects</li>
              <li>Knowledge of GIS platforms</li>
              <li>Strong stakeholder communication skills</li>
            </ul>
          </details>
        </section>

        <!-- Job 2 -->
        <section class="job" id="engineer">
          <aside>
            <p>Relocation: Company accomodation available at site.</p>
          </aside>

          <h2>Reference: EN456</h2>
          <h3>Energy Monitoring Solutions Engineer</h3>
          <p class="job-desc">
            Develop and implement digital energy monitoring platforms to enhance
            sustainability and efficiency across urban infrastructure.
            <br>We welcome and encourage applications from Aboriginal and
            Torres Strait Islander peoples, as well as individuals from all
            cultural and diverse backgrounds.
          </p>

          <p><strong>Salary:</strong> $105,000 – $130,000 AUD</p>
          <p><strong>Reports to:</strong> Head of Smart Energy Solutions</p>

          <details>
            <summary>Key Responsibilities</summary>
            <ul>
              <li>Design energy monitoring dashboards and reporting tools</li>
              <li>Integrate IoT devices for real-time energy tracking</li>
              <li>
                Work with stakeholders to identify optimisation opportunities
              </li>
              <li>Ensure compliance with energy regulations</li>
            </ul>
          </details>

          <details>
            <summary>Essential Requirements</summary>
            <ol>
              <li>Degree in Electrical Engineering or similar</li>
              <li>Experience with IoT platforms and cloud systems</li>
              <li>Strong analytical skills</li>
            </ol>
          </details>

          <details>
            <summary>Preferable Skills</summary>
            <ul>
              <li>Renewable energy project experience</li>
              <li>Data visualisation tools knowledge</li>
              <li>Understanding of smart grids</li>
            </ul>
          </details>
        </section>

        <!-- Job 3 -->
        <section class="job" id="projManager">
          <aside>
            <p>Paid certification and promotion opportunities open.</p>
          </aside>

          <h2>Reference: PM789</h2>
          <h3>Smart City Project Manager</h3>
          <p class="job-desc">
            Lead and coordinate smart city initiatives, ensuring successful
            delivery across multiple stakeholders.
            <br>We welcome and encourage applications from Aboriginal and
            Torres Strait Islander peoples, as well as individuals from all
            cultural and diverse backgrounds.
          </p>

          <p><strong>Salary:</strong> $110,000 – $125,000 AUD</p>
          <p><strong>Reports to:</strong> Director of Operations</p>

          <details>
            <summary>Key Responsibilities</summary>
            <ul>
              <li>Manage project timelines, budgets, and risks</li>
              <li>Coordinate cross-functional teams</li>
              <li>Liaise with councils and partners</li>
              <li>Ensure project governance standards are met</li>
            </ul>
          </details>

          <details>
            <summary>Essential Requirements</summary>
            <ol>
              <li>Degree in Project Management or related field</li>
              <li>Experience managing complex projects</li>
              <li>Strong leadership skills</li>
            </ol>
          </details>

          <details>
            <summary>Preferable Skills</summary>
            <ul>
              <li>PRINCE2 or PMP certification</li>
              <li>Experience in infrastructure or government projects</li>
              <li>Agile methodology knowledge</li>
            </ul>
          </details>
        </section>

        <!-- Job 4 -->
        <section class="job" id="hresources">
          <aside>
            <p>This position is also open to entry level Legal Assistants.</p>
          </aside>

          <h2>Reference: HR654</h2>
          <h3>Human Resources & Talent Specialist</h3>
          <p class="job-desc">
            Support recruitment, employee engagement, and HR operations in a
            dynamic consultancy environment.
            <br>We welcome and encourage applications from Aboriginal and
            Torres Strait Islander peoples, as well as individuals from all
            cultural and diverse backgrounds.
          </p>

          <p><strong>Salary:</strong> $85,000 – $100,000 AUD</p>
          <p><strong>Reports to:</strong> Head of People & Culture</p>

          <details>
            <summary>Key Responsibilities</summary>
            <ul>
              <li>Manage recruitment and onboarding processes</li>
              <li>Develop engagement and training programs</li>
              <li>Ensure HR compliance and policies</li>
              <li>Support performance management</li>
            </ul>
          </details>

          <details>
            <summary>Essential Requirements</summary>
            <ol>
              <li>Degree in Human Resources or related field</li>
              <li>Experience in recruitment and employee relations</li>
              <li>Strong interpersonal skills</li>
            </ol>
          </details>

          <details>
            <summary>Preferable Skills</summary>
            <ul>
              <li>Experience in consultancy or tech sector</li>
              <li>Knowledge of HR systems</li>
              <li>Interest in workplace culture development</li>
            </ul>
          </details>
        </section>
      </section>
    </main>

<?php include 'footer.inc'; ?>