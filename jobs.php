<?php
  /*
   * jobs.php - Careers Page
   * Displays job thumbnail cards and all current job listings
   * loaded dynamically from the jobs table in the database.
   * Search functionality is handled separately in search.php
   */
  $title       = "Careers - EcoCity Co.";
  $description = "Job posting description, Essential and preferable requirements, Key responsibilities";
  $keywords    = "job vacancy, careers, job roles";
  $author      = "Nusaiba Mohammed, 104649533";
  $pageCSS     = "styles/jobs.css"; // External CSS - job cards, overlay transitions and accordion styles
?>
<?php include 'header.inc'; // Outputs <!DOCTYPE>, <html>, <head>, <body> and shared nav ?>
<?php include 'settings.php'; // Loads $host, $user, $pwd, $sql_db for DB connection ?>

<!--
  Embedded CSS - styles specific to jobs.php that are not needed globally.
  Kept here rather than in the external sheet since they only apply to this page.
-->
<style>
  /* Aside floated right inside each job card
     displays visit requirements or relocation notes from the DB.
     25% width leaves room for the job content on the left. */
  .job aside {
    float: right;
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

  /* Scale effect on aside hover draws the manager's
     attention to the visit/relocation requirement */
  .job aside:hover {
    transform: scale(1.1);
  }

  /* Search prompt banner sitting between the thumbnails
     and job listings - guides users to search.php */
  .search-prompt {
    text-align: center;
    padding: 1rem;
    background: #f0faf0;
    border: 1px solid #c1f0c1;
    border-radius: 8px;
    margin: 1rem auto;
    width: 90%;
    font-size: 0.95rem;
    color: #0c5401;
  }

  /* Button inside the search prompt styled to match
     the site's green brand colour */
  .search-prompt a {
    display: inline-block;
    margin-left: 10px;
    padding: 6px 16px;
    background: #0c5401;
    color: #fff;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    font-size: 0.9rem;
  }

  .search-prompt a:hover {
    background: #094001;
  }
</style>

<?php
// Connect to DB using credentials loaded from settings.php
// die() stops execution completely if connection fails
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch ALL jobs from DB ordered by id for consistent display order
// No search filtering here - that lives in search.php
$stmt = mysqli_prepare($conn, "SELECT * FROM jobs ORDER BY id ASC");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// fetch_all returns every row as an associative array at once
// stored in $jobs array for use in the foreach loop below
$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<main>

  <!-- ===== Thumbnail Cards Section =====
       Four image cards with hover overlays linking to search.php
       with a pre-filled keyword matching the job category -->
  <section class="jobCards">

    <!-- Inline CSS on h1 - colour and alignment specific to this heading only
         using the site's dark green brand colour -->
    <h1
      class="jobsTitle"
      style="text-align:center; padding:0; color:#0c5401; font-style:italic;"
    >
      Jobs at Eco City Co.
    </h1>

    <!-- Company introduction paragraph -->
    <p>
      At EcoCity Co., we work with councils and industry partners to deliver
      smart, sustainable urban solutions that make a real impact. We value
      innovation, collaboration, and continuous learning, and provide
      opportunities to work on meaningful projects that shape the future of
      cities while supporting your professional growth.
    </p>

    <!-- Card grid - CSS grid layout defined in jobs.css
         Each card has an image and a hidden overlay that slides up on hover -->
    <div id="card-area">
      <div class="wrapper">
        <div class="box-area">

          <!-- Card 1 - clicking View Jobs sends ?search=transport to search.php
               which pre-fills the search bar and filters matching jobs -->
          <div class="box">
            <img
              alt="Two professionals analysing code and data on multiple computer screens in an office environment"
              src="images/analyst.png"
            >
            <div class="overlay">
              <h2>Smart Transport Systems Analyst</h2>
              <p>Drive smarter mobility solutions for connected cities.</p>
              <a href="search.php?search=transport">View Jobs</a>
            </div>
          </div>

          <!-- Card 2 - pre-fills search with 'energy' on search.php -->
          <div class="box">
            <img
              alt="Engineers in safety helmets reviewing plans on a construction site for infrastructure development"
              src="images/engineer.jpg"
            >
            <div class="overlay">
              <h3>Energy Monitoring Solutions Engineer</h3>
              <p>Power urban sustainability with innovative energy insights.</p>
              <a href="search.php?search=energy">View Jobs</a>
            </div>
          </div>

          <!-- Card 3 - pre-fills search with 'manager' on search.php -->
          <div class="box">
            <img
              alt="Professional presenting data charts and graphs on a board during a business analysis meeting"
              src="images/manager.jpg"
            >
            <div class="overlay">
              <h3>Smart City Project Manager</h3>
              <p>Lead projects that transform cities for the future.</p>
              <a href="search.php?search=manager">View Jobs</a>
            </div>
          </div>

          <!-- Card 4 - pre-fills search with 'human resources' on search.php
               + encodes the space as + in the URL -->
          <div class="box">
            <img
              alt="Confident business consultant standing in a modern office with arms crossed"
              src="images/hr_job.jpg"
            >
            <div class="overlay">
              <h3>Human Resources &amp; Talent Specialist</h3>
              <p>Build the teams that make smart cities happen.</p>
              <a href="search.php?search=human+resources">View Jobs</a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- ===== Current Opportunities Section =====
       Lists all jobs fetched from DB dynamically.
       Adding a new row to the jobs table makes it appear here automatically. -->
  <section>
    <h1 id="careers">Current Opportunities</h1>

    <!-- Prompt directing users to search.php for filtered results
         search functionality is intentionally separated to search.php -->
    <div class="search-prompt">
      Looking for something specific?
      <a href="search.php">Search Jobs</a>
    </div>

    <!-- Total job count - count() counts elements in the $jobs array -->
    <p class="results-count" style="font-size:14px; color:#666; margin: 0.5rem 1rem;">
      Showing all <?php echo count($jobs); ?> jobs
    </p>

    <?php if (empty($jobs)): ?>
      <!-- Shown if the jobs table exists but has no records yet -->
      <p style="text-align:center; color:#666; padding:2rem;">
        No job listings available at this time. Please check back soon.
      </p>

    <?php else: ?>
      <?php foreach ($jobs as $job): ?>
      <!--
        foreach loops through every row in $jobs array
        Each iteration renders one complete job card using
        the same HTML template - data changes, structure stays the same
      -->
      <section class="job" id="<?php echo htmlspecialchars($job['reference']); ?>">
        <!--
          id set to the job reference e.g. id="SC123"
          htmlspecialchars() prevents XSS on all DB output
        -->

        <!-- Aside floated right - visit_requirement or relocation note from DB -->
        <aside>
          <p><?php echo htmlspecialchars($job['additional_info']); ?></p>
        </aside>

        <!-- Reference number and job title pulled from DB columns -->
        <h2>Reference: <?php echo htmlspecialchars($job['reference']); ?></h2>
        <h3><?php echo htmlspecialchars($job['title']); ?></h3>

        <!-- Description from DB - nl2br() converts \n line breaks stored
             in the DB text into HTML <br> tags so they render correctly
             htmlspecialchars() applied first to sanitise before nl2br converts -->
        <p class="job-desc">
          <?php echo nl2br(htmlspecialchars($job['description'])); ?>
        </p>

        <!-- Salary range and reporting line from DB -->
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
        <p><strong>Reports to:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></p>

        <!--
          HTML5 <details> and <summary> create native expandable sections
          No JavaScript needed - the browser handles open/close behaviour built in
          Each section's content is stored as newline-separated text in the DB
          explode("\n", ...) splits that text into an array of individual items
          foreach then renders each item as its own <li>
          if (trim($item)) skips any accidental empty lines in the DB text
        -->

        <!-- Key responsibilities rendered as unordered bullet list -->
        <details>
          <summary>Key Responsibilities</summary>
          <ul>
            <?php foreach (explode("\n", $job['responsibilities']) as $item): ?>
              <?php if (trim($item)): ?>
                <li><?php echo htmlspecialchars(trim($item)); ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </details>

        <!-- Essential requirements rendered as ordered numbered list
             ol used deliberately to match the numbered format from Part 1 -->
        <details>
          <summary>Essential Requirements</summary>
          <ol>
            <?php foreach (explode("\n", $job['requirements']) as $item): ?>
              <?php if (trim($item)): ?>
                <li><?php echo htmlspecialchars(trim($item)); ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ol>
        </details>

        <!-- Preferable skills rendered as unordered bullet list -->
        <details>
          <summary>Preferable Skills</summary>
          <ul>
            <?php foreach (explode("\n", $job['preferable_skills']) as $item): ?>
              <?php if (trim($item)): ?>
                <li><?php echo htmlspecialchars(trim($item)); ?></li>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </details>

      </section>
      <?php endforeach; ?>

    <?php endif; ?>
  </section>
</main>

<?php
  mysqli_close($conn); // Explicitly close DB connection to free resources
  include 'footer.inc'; // Outputs </body> and </html>
?>