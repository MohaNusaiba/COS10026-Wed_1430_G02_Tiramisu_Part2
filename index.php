<?php
  $title       = "Home - EcoCity Co.";
  $description = "Company logo, description, services";
  $keywords    = "HTML5,image, smart city, digital solutions, sustainability";
  $author      = "Ruby Telford, 105916092";
  $pageCSS     = "";
?>
<?php include 'header.inc'; ?>
<style>
</style>
  <body class="index_page">
    <main>
      <form
        action="https://mercury.swin.edu.au/it000000/formtest.php"
        method="post"
      >
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

      <a>
        <h1>
          <img
            src="images/navlogo.png"
            alt="EcoCity Logo"
            title="Ecocity Logo"
          />EcoCity Smart City
        </h1>
      </a>

      <h2>Where Technology Meets Sustainability</h2>
      <h3>
        EcoCity Co. is a smart city consultancy focused on creating sustainable
        and technology-driven urban environments. We combine innovative digital
        solutions with eco-friendly design to help cities become more efficient,
        connected, and environmentally responsible.
        <!--generated using ChatGPT promt "can you please create a description paragraph for a company called Eco-City Co"-->
      </h3>

      <img
        src="images/sustainable_city.png"
        alt="Sustainable city created by EcoCity Co"
        title="Sustainable City"
      />
      <!--Sustainable_city.png was generated using ChatGPT and the prompt "can you please generate me a landscape image of a sustainable city"-->

      <h2>Services Offered</h2>
      <h3>Basic Services</h3>
      <table>
        <tr>
          <th>Basic</th>
          <th>Standard</th>
          <th>Premium</th>
        </tr>
        <tr>
          <!--Basic-->
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
          <!--Standard-->
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
          <!--Premium-->
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
                <td>Enviromental Impact Assessment</td>
                <td>$4,000</td>
              </tr>
              <tr>
                <td>Sensor Network Design</td>
                <td>$5,200</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </main>
  </body>
  <?php include 'footer.inc'; ?>
</html>
