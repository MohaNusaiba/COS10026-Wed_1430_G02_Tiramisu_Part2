<?php
  $title       = "Careers - EcoCity Co.";
  $description = "Job posting description, Essential and preferable requirements, Key responsibilities";
  $keywords    = "job vacancy, careers, job roles";
  $author      = "Nusaiba Mohammed, 104649533";
  $pageCSS     = "";
?>
<?php include 'header.inc'; ?>
<?php include 'settings.php'; ?>
<style>
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
  .job aside:hover { transform: scale(1.1); }
 
  .search-bar {
    display: flex;
    gap: 10px;
    margin: 1.5rem 0;
    align-items: center;
  }
  .search-bar input {
    padding: 8px 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    width: 300px;
  }
  .search-bar button {
    padding: 8px 20px;
    background: #0c5401;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
  }
  .search-bar button:hover { background: #094001; }
  .search-bar a {
    font-size: 13px;
    color: #0c5401;
    text-decoration: none;
    padding: 8px 12px;
  }
  .search-bar a:hover { text-decoration: underline; }
  .results-count {
    font-size: 14px;
    color: #666;
    margin-bottom: 1rem;
  }
  .no-results {
    padding: 2rem;
    text-align: center;
    color: #666;
    font-size: 15px;
  }
</style>
 
<?php
// DB connection
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
 
// Get search term
$search = trim($_GET['search'] ?? '');
 
// Build query — search across title, and reference
if (!empty($search)) {
    $stmt = mysqli_prepare($conn, 
        "SELECT * FROM jobs 
         WHERE title LIKE ? OR reference LIKE ?
         ORDER BY id ASC");
    $like = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
} else {
    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs ORDER BY id ASC");
}
 
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jobs   = mysqli_fetch_all($result, MYSQLI_ASSOC);
$total  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM jobs"));
?>
 
<main>
  <!-- Job thumbnails section -->
  <section class="jobCards">
    <h1 class="jobsTitle" style="text-align:center; padding:0; color:#0c5401; font-style:italic;">
      Jobs at Eco City Co.
    </h1>
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
            <img alt="Two professionals analysing code and data on multiple computer screens in an office environment" src="images/analyst.png">
            <div class="overlay">
              <h2>Smart Transport Systems Analyst</h2>
              <p>Drive smarter mobility solutions for connected cities.</p>
              <a href="jobs.php?search=transport">View Jobs</a>
            </div>
          </div>
          <div class="box">
            <img alt="Engineers in safety helmets reviewing plans on a construction site for infrastructure development" src="images/engineer.jpg">
            <div class="overlay">
              <h3>Energy Monitoring Solutions Engineer</h3>
              <p>Power urban sustainability with innovative energy insights.</p>
              <a href="jobs.php?search=energy">View Jobs</a>
            </div>
          </div>
          <div class="box">
            <img alt="Professional presenting data charts and graphs on a board during a business analysis meeting" src="images/manager.jpg">
            <div class="overlay">
              <h3>Smart City Project Manager</h3>
              <p>Lead projects that transform cities for the future.</p>
              <a href="jobs.php?search=manager">View Jobs</a>
            </div>
          </div>
          <div class="box">
            <img alt="Confident business consultant standing in a modern office with arms crossed" src="images/HRjob.jpg">
            <div class="overlay">
              <h3>Human Resources & Talent Specialist</h3>
              <p>Build the teams that make smart cities happen.</p>
              <a href="jobs.php?search=human+resources">View Jobs</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
 
  <section>
    <h1 id="careers">Current Opportunities</h1>
 
    <!-- Search Bar -->
    <form method="get" action="jobs.php" class="search-bar">
      <input 
        type="text" 
        name="search" 
        placeholder="Search jobs by title or reference..."
        value="<?php echo htmlspecialchars($search); ?>">
      <button type="submit">Search</button>
      <?php if (!empty($search)): ?>
        <a href="jobs.php">Clear</a>
      <?php endif; ?>
    </form>
 
    <!-- Results count -->
    <p class="results-count">
      <?php if (!empty($search)): ?>
        Showing <?php echo count($jobs); ?> of <?php echo $total; ?> jobs for 
        "<strong><?php echo htmlspecialchars($search); ?></strong>"
      <?php else: ?>
        Showing all <?php echo count($jobs); ?> jobs
      <?php endif; ?>
    </p>
 
    <?php if (empty($jobs)): ?>
      <p class="no-results">
        No jobs found for "<?php echo htmlspecialchars($search); ?>". 
        Try a different keyword or <a href="jobs.php">view all jobs</a>.
      </p>
    <?php else: ?>
 
      <?php foreach ($jobs as $job): ?>
      <section class="job" id="<?php echo htmlspecialchars($job['reference']); ?>">
 
        <aside>
          <p><?php echo htmlspecialchars($job['additional_info']); ?></p>
        </aside>
 
        <h2>Reference: <?php echo htmlspecialchars($job['reference']); ?></h2>
        <h3><?php echo htmlspecialchars($job['title']); ?></h3>
 
        <p class="job-desc">
          <?php 
            // preserve line breaks from DB
            echo nl2br(htmlspecialchars($job['description'])); 
          ?>
        </p>
 
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
        <p><strong>Reports to:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></p>
 
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
  mysqli_close($conn);
  include 'footer.inc'; 
?>