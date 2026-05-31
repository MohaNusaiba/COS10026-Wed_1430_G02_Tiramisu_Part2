<?php
  /*
   * index.php - EcoCity Co. Homepage
   * Landing page introducing the company, services and search functionality
   */
  $title       = "Home - EcoCity Co.";
  $description = "EcoCity Co. provides smart city consulting, sustainability planning and innovative urban technology solutions.";
  $keywords    = "HTML5, smart city, digital solutions, sustainability, consulting";
  $author      = "Ruby Telford, 105916092";
  $pageCSS     = "styles/styles.css"; // External CSS handles global and index page styles
?>
<?php include 'header.inc'; ?>

<!--
  Embedded CSS - index page specific styles not needed globally.
  Complements the external stylesheet with homepage only rules.
-->
<style>
  /* Centers all main content on the homepage */
  .index_page main {
    text-align: center;
    padding: 20px;
  }

  /* Primary site heading - flex layout to align logo image with text */
  .index_page h1 {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    margin: 25px 0 1px 0;
    color: #05368f;
  }

  /* Subheading with extra letter spacing for visual emphasis */
  .index_page h2 {
    letter-spacing: 1px;
    color: #02173d;
    margin-bottom: 10px;
  }

  /* Company description paragraph - constrained width for readability */
  .index_page h3 {
    max-width: 80%;
    margin: 20px auto;
    font-size: 1.1em;
    line-height: 1.6;
    color: #02173d;
  }

  /* Main promotional image - rounded with shadow */
  .index_page main > img {
    display: block;
    max-width: 85%;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 4px 4px 12px rgba(0,0,0,0.2);
  }

  /* Search form centred with spacing below */
  .index_page form {
    margin: 20px auto 30px auto;
  }

  /* Search fieldset - styled border matching brand colour */
  .index_page fieldset {
    border: 2px solid #05368f;
    border-radius: 10px;
    background-color: #f8fbff;
    padding: 10px 20px;
    display: inline-block;
  }

  .index_page legend {
    color: #05368f;
    font-weight: bold;
  }

  /* Search input field */
  .index_page #search_bar {
    width: 250px;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #262626;
    margin-right: 10px;
  }

  /* Search submit button with brand blue gradient */
  .index_page input[type="submit"] {
    background: linear-gradient(to top, #05368f, #02173d);
    color: white;
    border: none;
    border-radius: 6px;
    padding: 10px 16px;
    cursor: pointer;
  }

  .index_page input[type="submit"]:hover {
    background: linear-gradient(to top, #05368f, #22ff00);
    color: black;
  }

  /* Services table - full width centered with collapsed borders */
  .index_page table {
    width: 90%;
    margin: 0 auto;
    border-collapse: collapse;
  }

  /* Outer table header row - merged cell using colspan */
  .index_page table > tbody > tr:first-child th {
    background-color: #05368f;
    color: white;
    padding: 12px;
    font-size: 15px;
  }

  /* Inner nested tables for each service package */
  .index_page td table {
    width: 100%;
    border-collapse: collapse;
  }

  .index_page td table th {
    background-color: #e6f0ff;
    padding: 8px;
    border: 1px solid #ccc;
  }

  .index_page td table td {
    padding: 8px;
    border: 1px solid #ccc;
    text-align: center;
  }

  /* Alternating row colours for readability */
  .index_page td table tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .index_page td table tr:nth-child(odd) {
    background-color: white;
  }
</style>

<!-- index_page class applied to body via a wrapper div
     since body tag is opened in header.inc -->
<div class="index_page">
  <main>

    <!-- Search form - posts to external Swinburne form test endpoint -->
    <form action="https://mercury.swin.edu.au/it000000/formtest.php" method="post">
      <fieldset>
        <legend>Search Bar</legend>
        <input
          type="text"
          id="search_bar"
          name="search_bar"
          placeholder="Search Here"
        />
        <input type="submit" value="Search" />
      </fieldset>
    </form>

    <!-- Company logo and primary site heading -->
    <h1>
      <img
        src="images/navlogo.png"
        alt="EcoCity Logo"
        title="EcoCity Logo"
      />
      EcoCity Smart City
    </h1>

    <!--
      Inline CSS on h2 satisfies the inline CSS requirement.
      Underline decoration draws attention to the company tagline.
    -->
    <h2 style="text-decoration: underline; font-style: italic;">
      Where Technology Meets Sustainability
    </h2>

    <!-- Company description paragraph -->
    <h3>
      EcoCity Co. is a smart city consultancy focused on creating sustainable
      and technology-driven urban environments. We combine innovative digital
      solutions with eco-friendly design to help cities become more efficient,
      connected, and environmentally responsible.
    </h3>

    <!-- Main promotional image showing a sustainable smart city -->
    <img
      src="images/sustainable_city.png"
      alt="Sustainable city created by EcoCity Co"
      title="Sustainable City"
    />

    <h2>Services Offered</h2>

    <!--
      Services table demonstrating colspan and nested tables.
      colspan="3" merges the header cell across all three package columns.
    -->
    <table>
      <tbody>
        <!-- Merged header spanning all 3 package columns using colspan -->
        <tr>
          <th colspan="3">EcoCity Co. Service Packages</th>
        </tr>

        <!-- Package name headers -->
        <tr>
          <th>Basic</th>
          <th>Standard</th>
          <th>Premium</th>
        </tr>

        <!-- Nested tables inside each cell for package details -->
        <tr>

          <!-- Basic Service Package -->
          <td>
            <table>
              <tr>
                <th>Service</th>
                <th>Price</th>
              </tr>
              <tr>
                <td>Site Assessment</td>
                <td>$800</td>
              </tr>
              <tr>
                <td>Sustainability Consultation</td>
                <td>$1,200</td>
              </tr>
              <tr>
                <td>Smart Tech Report</td>
                <td>$1,500</td>
              </tr>
            </table>
          </td>

          <!-- Standard Service Package -->
          <td>
            <table>
              <tr>
                <th>Service</th>
                <th>Price</th>
              </tr>
              <tr>
                <td>Traffic Flow Analysis</td>
                <td>$3,200</td>
              </tr>
              <tr>
                <td>Smart Lighting Design</td>
                <td>$2,500</td>
              </tr>
              <tr>
                <td>Waste Management Planning</td>
                <td>$2,900</td>
              </tr>
            </table>
          </td>

          <!-- Premium Service Package -->
          <td>
            <table>
              <tr>
                <th>Service</th>
                <th>Price</th>
              </tr>
              <tr>
                <td>Full Smart City Plan</td>
                <td>$6,500</td>
              </tr>
              <tr>
                <td>Environmental Impact Assessment</td>
                <td>$4,000</td>
              </tr>
              <tr>
                <td>Sensor Network Design</td>
                <td>$5,200</td>
              </tr>
            </table>
          </td>

        </tr>
      </tbody>
    </table>

  </main>
</div>

<?php include 'footer.inc'; ?>