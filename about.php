<?php
  /*
   * about.php - Group Information Page
   * Displays acknowledgment of country, group details,
   * member contributions loaded from DB, group photo and fun facts table
   */
  $title       = "About - G02 Tiramisu";
  $description = "Group and class timetable, member contributions, fun facts, group photo";
  $keywords    = "HTML5, caption table, native language quotes, acknowledgment";
  $author      = "Ruby Telford, 105916092";
  $pageCSS     = "styles/about.css"; // External CSS handles about page layout and animations
?>
<?php include 'header.inc'; ?>

<!--
  Embedded CSS - about page specific styles for the group photo section
  and DB loaded member contribution cards. Complements the external about.css.
-->
<style>
  /* Group photo container - centered with top spacing */
  .group-photo {
    margin-top: 20px;
    text-align: center;
  }

  /* Group photo image - constrained width with blue border frame */
  .group-photo img {
    max-width: 60%;
    border: 4px solid #05368f;
    border-radius: 6px;
  }

  /* Caption displayed below the group photo in italic */
  .group-photo figcaption {
    margin-top: 8px;
    font-style: italic;
    color: #475569;
  }

  /* Part label above each contribution block e.g. Part 1 Contribution */
  .contrib-label {
    font-weight: bold;
    color: #05368f;
    margin-top: 10px;
    display: block;
  }

  /* Fun facts table caption styling */
  .fun-facts caption {
    caption-side: top;
    font-weight: bold;
    margin-bottom: 10px;
    color: #05368f;
    font-size: 1rem;
  }
</style>

<main>
  <!-- Aboriginal flag image with fade-in animation defined in about.css -->
  <img
    id="flag"
    src="images/aborginal_flag.png"
    alt="Aboriginal flags with Australian Flag"
    style="display: block; margin: 2em auto 0;"
  >

  <article>

    <!-- Acknowledgment of Country heading centered with bottom border -->
    <h1 class="acknowledge">Acknowledgment of Country</h1>

    <!-- Acknowledgment statement in a styled aside container -->
    <aside id="statement">
      <p>
        We respectfully acknowledge the Wurundjeri People of the Kulin
        Nation, who are the Traditional Owners of the land on which
        Swinburne's Australian campuses are located in Melbourne's east and
        outer-east, and pay our respect to their Elders past, present and
        emerging. We are honoured to recognise our connection to Wurundjeri
        Country, history, culture and spirituality through these locations,
        and strive to ensure that we operate in a manner that respects and
        honours the Elders and Ancestors of these lands. We also
        respectfully acknowledge Swinburne's Aboriginal and Torres Strait
        Islander staff, students, alumni, partners and visitors. We also
        acknowledge and respect the Traditional Owners of lands across
        Australia, their Elders, Ancestors, cultures and heritage, and
        recognise the continuing sovereignties of all Aboriginal and Torres
        Strait Islander Nations.
      </p>
    </aside>

    <!-- Main section containing group info, contributions and fun facts -->
    <section class="group-simple">

      <!-- Group identification details -->
      <h2>Group Name: Tiramisu</h2>
      <h3>Group Num: G02</h3>

      <!-- Class schedule as an unordered list with square bullet points -->
      <p><strong>Class Details:</strong></p>
      <ul class="unorder">
        <li>Day: Wednesday</li>
        <li>Time: 2:30 pm</li>
        <li>Group Member Number: 3</li>
      </ul>

      <!-- Project purpose statement -->
      <p>
        <strong>Purpose:</strong> Build Job portal for a Smart City
        Infrastructure Consultancy called Eco City Co.
      </p>

      <hr>

      <!-- Member contributions heading -->
      <h4>The Wall of Member Contributions &amp; Quotes</h4>

      <?php
      // Load DB credentials from settings.php
      require_once("settings.php");

      // Connect to the database
      $conn = mysqli_connect($host, $user, $pwd, $sql_db);

      if (!$conn) {
          // Graceful fallback if DB is unavailable
          echo "<p>Database connection failed. Please try again later.</p>";
      } else {
          // Fetch all members ordered by member_id for consistent display
          $query  = "SELECT * FROM about ORDER BY member_id";
          $result = mysqli_query($conn, $query);

          if ($result && mysqli_num_rows($result) > 0) {
              // Definition list - dt for member name, dd for contributions and details
              echo "<dl class='members'>";

              while ($member = mysqli_fetch_assoc($result)) {
                  $member_id = $member["member_id"];

                  // Each member gets a unique student ID highlight colour
                  // Inline CSS applied dynamically based on member_id from DB
                  if ($member_id == 1) {
                      $text_colour       = "#000000";
                      $background_colour = "#f3a806";
                  } elseif ($member_id == 2) {
                      $text_colour       = "#ffffff";
                      $background_colour = "#9900ff";
                  } else {
                      $text_colour       = "#000000";
                      $background_colour = "#f96565";
                  }

                  // Member name and highlighted student ID in dt
                  echo "<dt>";
                  echo "Member " . htmlspecialchars($member_id) . " - ";
                  echo htmlspecialchars($member["member_name"]) . ", Student ID: ";
                  // Inline CSS - unique colour per member for visual distinction
                  echo "<span style='color: " . $text_colour . ";
                        background-color: " . $background_colour . ";
                        font-weight: bold;
                        padding: 2px 6px;
                        border-radius: 4px;'>";
                  echo htmlspecialchars($member["student_id"]);
                  echo "</span>";
                  echo "</dt>";

                  echo "<dd>";

                  // Part 1 contributions - split by comma into individual tags
                  echo "<span class='contrib-label'>Part 1 Contribution:</span>";
                  echo "<div class='contrib'>";
                  $part1_items = explode(",", $member["project1_contribution"]);
                  foreach ($part1_items as $item) {
                      echo "<span>" . htmlspecialchars(trim($item)) . "</span>";
                  }
                  echo "</div>";

                  // Part 2 contributions - split by comma into individual tags
                  echo "<span class='contrib-label'>Part 2 Contribution:</span>";
                  echo "<div class='contrib'>";
                  $part2_items = explode(",", $member["project2_contribution"]);
                  foreach ($part2_items as $item) {
                      echo "<span>" . htmlspecialchars(trim($item)) . "</span>";
                  }
                  echo "</div>";

                  // Native language quote and translation
                  echo "<em>\"" . htmlspecialchars($member["quote"]) . "\"</em><br>";
                  echo "Translation: \"" . htmlspecialchars($member["quote_translation"]) . "\"";

                  // Fun facts from the DB
                  echo "<br><br>";
                  echo "<strong>Interest Area:</strong> " . htmlspecialchars($member["interest_area"]) . "<br>";
                  echo "<strong>Coding Snack:</strong> "  . htmlspecialchars($member["coding_snack"])  . "<br>";
                  echo "<strong>Dream Travel Destination:</strong> " . htmlspecialchars($member["dream_travel"]);

                  echo "</dd>";
              }

              echo "</dl>";
          } else {
              // Fallback message if no records found in about table
              echo "<p>No member contribution records found.</p>";
          }

          mysqli_close($conn);
      }
      ?>

      <!-- Group photo with semantic figure and figcaption tags -->
      <figure class="group-photo">
        <img src="images/g02_team.jpeg" alt="Group photo of G02 Tiramisu team">
        <figcaption>G02 Team on Coding Interview Day</figcaption>
      </figure>

      <!-- Fun Facts Table with caption, thead and tbody for semantic structure -->
      <table class="fun-facts">
        <caption>Fun Facts About Group Members</caption>
        <!-- Table headers describing each column -->
        <thead>
          <tr>
            <th>Member</th>
            <th>Interest Area</th>
            <th>Coding Snack</th>
            <th>Dream Travel Destination</th>
          </tr>
        </thead>
        <!-- Row data for each member -->
        <tbody>
          <tr>
            <td>Nusaiba Mohammed</td>
            <td>Cloud &amp; SDN Networking Security, SOC roles</td>
            <td>Loukamades</td>
            <td>Norway or Switzerland</td>
          </tr>
          <tr>
            <td>Ruby Telford</td>
            <td>Creating Personalised Things for Friends and Family</td>
            <td>Pretzels</td>
            <td>Europe</td>
          </tr>
          <tr>
            <td>Harpreet Kour</td>
            <td>Travelling</td>
            <td>Egg wrap</td>
            <td>India</td>
          </tr>
        </tbody>
      </table>

    </section>
  </article>
</main>

<?php include 'footer.inc'; ?>