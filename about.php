<?php
  $title       = "About - G02 Tiramisu";
  $description = "Group and class timetable, member contributions, fun facts, group photo";
  $keywords    = "HTML5, caption table, native language quotes, acknowledgment";
  $author      = "Nusaiba Mohammed, 104649533";
  $pageCSS     = "";
?>
<?php include 'header.inc'; ?>
<style>
      .group-photo {
      margin-top: 20px;
      text-align: center;
    }

    .group-photo img {
      max-width: 60%;
      border: 4px solid blue;
      border-radius: 6px;
    }

    .group-photo figcaption {
      margin-top: 8px;
      font-style: italic;
    }
</style>
    <main>
      <!-- Visual image of flags, unique ID for CSS of this image -->
      <img
        id="flag"
        src="images/aborginalFlag.png"
        alt="Aboriginal flags with Australian Flag">

      <article>
        <h1 class="acknowledge">Acknowledgment of Country</h1>
        <!-- Acknowledgement of Country by Team -->
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

        <!-- Section container for group details, member contribution, quotes and fun facts -->
        <section class="group-simple">
          <h2>Group Name: Tiramisu</h2>
          <h3>Group Num: G02</h3>
          <!-- Usage of semantic tags and nested list -->
          <p><strong>Class Details:</strong></p>
          <ul class="unorder">
            <li>Day: Wednesday</li>
            <li>Time: 2:30 pm</li>
            <li>Group Member Number: 3</li>
          </ul>

          <p>
            <strong>Purpose:</strong> Build Job portal for a Smart City
            Infrastructure Consultancy called Eco City Co.
          </p>

          <hr>
<h4>The Wall of Member Contributions & Quotes</h4>

<?php
require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    echo "<p>Database connection failed.</p>";
} else {
    $query = "SELECT * FROM about ORDER BY member_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<dl class='members'>";

        while ($member = mysqli_fetch_assoc($result)) {
            $member_id = $member["member_id"];

            if ($member_id == 1) {
                $text_colour = "#000000";
                $background_colour = "#f3a806";
            } elseif ($member_id == 2) {
                $text_colour = "#ffffff";
                $background_colour = "#9900ff";
            } else {
                $text_colour = "#000000";
                $background_colour = "#f96565";
            }

            echo "<dt>";
            echo "Member " . htmlspecialchars($member_id) . " - ";
            echo htmlspecialchars($member["member_name"]) . ", Student ID: ";
            echo "<span style='color: " . $text_colour . "; background-color: " . $background_colour . "; font-weight: bold;'>";
            echo htmlspecialchars($member["student_id"]);
            echo "</span>";
            echo "</dt>";

            echo "<dd>";

            echo "<strong>Part 1 Contribution:</strong>";
            echo "<div class='contrib'>";

            $part1_items = explode(",", $member["project1_contribution"]);

            foreach ($part1_items as $item) {
                echo "<span>" . htmlspecialchars(trim($item)) . "</span>";
            }

            echo "</div>";

            echo "<strong>Part 2 Contribution:</strong>";
            echo "<div class='contrib'>";

            $part2_items = explode(",", $member["project2_contribution"]);

            foreach ($part2_items as $item) {
                echo "<span>" . htmlspecialchars(trim($item)) . "</span>";
            }

            echo "</div>";

            echo "<em>\"" . htmlspecialchars($member["quote"]) . "\"</em><br>";
            echo "Translation: \"" . htmlspecialchars($member["quote_translation"]) . "\"";

            echo "<br><br>";
            echo "<strong>Interest Area:</strong> " . htmlspecialchars($member["interest_area"]) . "<br>";
            echo "<strong>Coding Snack:</strong> " . htmlspecialchars($member["coding_snack"]) . "<br>";
            echo "<strong>Dream Travel Destination:</strong> " . htmlspecialchars($member["dream_travel"]);

            echo "</dd>";
        }

        echo "</dl>";
    } else {
        echo "<p>No member contribution records found.</p>";
    }

    mysqli_close($conn);
}
?>

          <!-- Group Photo figure caption -->
          <figure class="group-photo">
            <img src="images/G02Team.jpeg" alt="Group photo">
            <figcaption>G02 Team on Coding Interview Day</figcaption>
          </figure>
        </section>
      </article>
    </main>

<?php include 'footer.inc'; ?>
