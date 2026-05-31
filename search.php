<?php
  /*
   * search.php - Job Search Page
   * Separated from jobs.php as recommended by tutor.
   * Accepts GET parameter 'search' and queries the jobs table
   * using LIKE wildcards to match title or reference number.
   * GET is used instead of POST so results are bookmarkable
   * and thumbnail links from jobs.php can pre-fill the search.
   */
  $title       = "Search Jobs - EcoCity Co.";
  $description = "Search for job listings at EcoCity Co. by title or reference number";
  $keywords    = "job search, careers, job listings, EcoCity";
  $author      = "Nusaiba Mohammed, 104649533";
  $pageCSS     = "styles/jobs.css"; // Shares external CSS with jobs.php for consistent styling
?>
<?php include 'header.inc'; // Outputs <!DOCTYPE>, <html>, <head>, <body> and shared nav ?>
<?php include 'settings.php'; // Loads $host, $user, $pwd, $sql_db for DB connection ?>

<!--
  Embedded CSS - search page specific styles for the search bar,
  results count, no-results message and aside element.
  These only apply to search.php so they live here not in the external sheet.
-->
<style>
  /* Search bar container - flex layout keeps input, button and
     clear link on the same row with consistent spacing */
  .search-bar {
    display: flex;
    gap: 10px;
    margin: 1.5rem 0;
    align-items: center;
    flex-wrap: wrap; /* wraps to new line on small screens */
  }

  /* Search text input - wider than default to fit typical search terms
     border matches site green brand colour */
  .search-bar input {
    padding: 8px 14px;
    border: 1.5px solid #0c5401;
    border-radius: 6px;
    font-size: 14px;
    width: 300px;
    outline: none;
    transition: box-shadow 0.2s ease;
  }

  /* Focus ring using box-shadow instead of outline
     gives softer visual feedback when user clicks the field */
  .search-bar input:focus {
    box-shadow: 0 0 0 3px rgba(12, 84, 1, 0.15);
  }

  /* Search submit button - green matching the jobs page brand colour */
  .search-bar button {
    padding: 8px 20px;
    background: #0c5401;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .search-bar button:hover {
    background: #094001;
  }

  /* Clear link only rendered when $search is not empty
     gives user a quick way back to all jobs */
  .search-bar a {
    font-size: 13px;
    color: #0c5401;
    text-decoration: none;
    padding: 8px 12px;
  }

  .search-bar a:hover {
    text-decoration: underline;
  }

  /* Results count line - muted colour so it does not
     compete visually with the job listings below */
  .results-count {
    font-size: 14px;
    color: #666;
    margin-bottom: 1rem;
  }

  /* Shown when LIKE query returns zero matching rows
     centered and muted to avoid alarming the user */
  .no-results {
    padding: 2rem;
    text-align: center;
    color: #666;
    font-size: 15px;
  }

  /* Aside floated right inside each job result card
     same styling as jobs.php for visual consistency */
  .job aside {
    float: right;
    width: 25%;
    margin-right: 1.0em;
    padding: 0 0.5em;
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

<?php
// Connect to DB using credentials from settings.php
$conn = mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Read the search term from the URL GET parameter
// trim() removes any accidental whitespace the user might type
// ?? '' provides a safe empty string default if no search param exists
$search = trim($_GET['search'] ?? '');

// Build the appropriate query based on whether a search term was provided
if (!empty($search)) {
    // LIKE query with % wildcards on both sides
    // % means "anything can be here" so %transport% matches
    // anything containing the word "transport" anywhere in the field
    // Prepared statement used to prevent SQL injection on the search input
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM jobs
         WHERE title LIKE ? OR reference LIKE ?
         ORDER BY id ASC");

    // Build the LIKE value once and reuse for both placeholders
    // 'ss' = two string parameters, one for title, one for reference
    $like = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $like, $like);
} else {
    // No search term - fetch all jobs so the page still shows content
    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs ORDER BY id ASC");
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// fetch_all returns every matching row at once as associative arrays
$jobs = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Separate query for total count - unaffected by search filter
// Used to display "Showing X of Y jobs" in the results count line
$total = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM jobs"));
?>

<main>
  <section>

    <!-- Page heading with inline CSS - matches jobs.php green colour
         inline used here since it is a one-off style on this element -->
    <h1
      id="careers"
      style="text-align:center; color:#0c5401;"
    >
      Search Jobs
    </h1>

    <!--
      Search form - GET method appends term to URL as ?search=yourterm
      This is intentional - GET makes results shareable and bookmarkable
      POST would lose the search term on refresh which is bad UX for search
      action="search.php" posts back to this same page
    -->
    <form method="get" action="search.php" class="search-bar">

      <!-- Input pre-filled with $search so term stays visible after submission
           htmlspecialchars() prevents XSS if someone puts HTML in the search box -->
      <input
        type="text"
        name="search"
        placeholder="Search jobs by title or reference..."
        value="<?php echo htmlspecialchars($search); ?>"
      >
      <button type="submit">Search</button>

      <!-- Clear link only rendered when a search is active
           links back to search.php with no parameters = show all -->
      <?php if (!empty($search)): ?>
        <a href="search.php">Clear</a>
      <?php endif; ?>

    </form>

    <!--
      Results count line shows different messages depending on state:
      - Active search: "Showing X of Y jobs for 'keyword'"
      - No search: "Showing all X jobs"
      count($jobs) counts the filtered results
      $total is the unfiltered DB count from the separate query above
    -->
    <p class="results-count">
      <?php if (!empty($search)): ?>
        Showing <?php echo count($jobs); ?> of <?php echo $total; ?> jobs for
        "<strong><?php echo htmlspecialchars($search); ?></strong>"
      <?php else: ?>
        Showing all <?php echo count($jobs); ?> jobs
      <?php endif; ?>
    </p>

    <!-- Back link to jobs.php - inline style keeps it subtle and small -->
    <p style="font-size:13px; margin-bottom:1rem;">
      <a href="jobs.php" style="color:#0c5401;">← Back to all jobs</a>
    </p>

    <?php if (empty($jobs)): ?>
      <!-- empty() returns true if $jobs array has no elements
           meaning the LIKE query found no matching rows -->
      <p class="no-results">
        No jobs found for "<?php echo htmlspecialchars($search); ?>".
        Try a different keyword or <a href="search.php">view all jobs</a>.
      </p>

    <?php else: ?>

      <?php foreach ($jobs as $job): ?>
      <!--
        foreach stamps out one job card per row in the $jobs array
        Same HTML template used for every job - only the data changes
        This is the core benefit of dynamic rendering over hardcoded HTML
      -->
      <section class="job" id="<?php echo htmlspecialchars($job['reference']); ?>">

        <!-- Aside floated right - additional_info column from DB
             shows visit requirements or relocation notes per job -->
        <aside>
          <p><?php echo htmlspecialchars($job['additional_info']); ?></p>
        </aside>

        <!-- Reference number and title from DB columns -->
        <h2>Reference: <?php echo htmlspecialchars($job['reference']); ?></h2>
        <h3><?php echo htmlspecialchars($job['title']); ?></h3>

        <!-- Description - htmlspecialchars() sanitises first
             then nl2br() converts \n line breaks from DB into <br> tags
             Order matters - always sanitise before converting -->
        <p class="job-desc">
          <?php echo nl2br(htmlspecialchars($job['description'])); ?>
        </p>

        <!-- Salary and reporting line from DB -->
        <p><strong>Salary:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
        <p><strong>Reports to:</strong> <?php echo htmlspecialchars($job['reports_to']); ?></p>

        <!--
          Native HTML5 expandable sections - no JavaScript needed
          Responsibilities, requirements and preferable_skills are stored
          as newline-separated text in the DB
          explode("\n", text) splits into array → foreach renders each as <li>
          trim($item) cleans whitespace → if(trim($item)) skips empty lines
        -->

        <!-- Key responsibilities as unordered bullet list -->
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

        <!-- Essential requirements as ordered numbered list
             ol chosen deliberately to match numbered format from Part 1 -->
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

        <!-- Preferable skills as unordered bullet list -->
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
  mysqli_close($conn); // Release DB connection when done
  include 'footer.inc'; // Outputs closing </body> and </html>
?>