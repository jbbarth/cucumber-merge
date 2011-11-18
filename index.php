<?php
// http://github.com/jbbarth/cucumber-merge
// Jean-Baptiste Barth <jeanbaptiste.barth@gmail.com>

// CONFIG VARIABLES
//
//the title of your page
$title = 'My Cucumber Portal';
//the following string will be added to the top-right 
$additional_info = '<a href="http://dokuwiki.intranet/doku.php?id=nagios:nagios_cucumber">Documentation</a>';
//the locale of your '.json' files ; if not 'UTF-8', the script will try to convert them with iconv() php function
//this is needed since php's json_decode() function only works with UTF-8 strings
$locale = 'ISO-8859-1';
//the directory where your <cucumber_results>.json live ; can be relative or absolute ; no need for a trailing "/"
$json_dir = ".json";
//the directory where your <cucumber_results>.html reports live ; can be relative or absolute ; no need for a trailing "/"
$html_dir = ".html";
//a function returning the nagios url for a site
//examples:
//  nothing:
//    function nagios_url_for($site) { return ""; }
//  nagios host = $site and nagios service = HTTP_SCENARIO
//    function nagios_url_for($site) { return " | <a href=\"../cgi-bin/extinfo.cgi?type=2&host=$site&service=HTTP_SCENARIO\">nagios</a>"; }
function nagios_url_for($site) { return " &nbsp; | &nbsp; <a href=\"../cgi-bin/extinfo.cgi?type=2&host=URL_$site&service=HTTP_SCENARIO\" style=\"color:#fff\">nagios</a>"; }
?>
<!DOCTYPE html>
<html>
<head>
  <meta content="text/html;charset=utf-8"/>
  <meta http-equiv="refresh" content="60">
  <title><?php echo $title; ?></title>
  <script src="./assets/jquery.js" type="text/javascript"></script> 
  <link href="./assets/cucumber.css" media="screen" rel="stylesheet" type="text/css" /> 
  <style>
  /* styles added manually ; cucumber.css should stay coherent with cucumber's default stylesheet */
  a, a:hover { color:#fff !important; }
  .cucumber ul { font-size:12px; font-weight:bold; margin-top:40px; }
  .cucumber li.site { padding:3px 10px; cursor:pointer; width:50em; margin:2px; color:white; }
  .cucumber li.passed, #cucumber-header.passed { background-color:#65C400; }
  .cucumber li.failed, #cucumber-header.failed { background-color:#c20000; }
  .cucumber li.site span.details { float:right; font-size:90%;color:#fff; font-weight:normal; }
  </style>
</head>
<body>
  <div class="cucumber">
    <div id="cucumber-header">
      <div id="label">
      <h1><?php echo $title; ?></h1>
      </div>
      <div id="summary">
        <p id="totals"></p>
      </div>
    </div>
    <ul>
    <?php
      function cucumber_status($item, $key, $status) { if ($key == "status") { $status[$item]++; } }
      $global_status = array("passed" => 0, "failed" => 0);
      foreach (scandir($json_dir) as $file) {
        if (preg_match('!\.json$!',$file)) {
          $site = preg_replace('!\.json$!', '', $file);
          //json parsing
          $raw = file_get_contents("$json_dir/$file");
          if ($locale != "" && $locale != "UTF-8") { $raw = iconv($locale, "UTF-8", $raw); }
          $json = json_decode($raw,true);
          //next one if json is not an array
          if (!is_array($json)) { continue; }
          //status for this site
          $statuses = array("passed" => 0, "failed" => 0);
          array_walk_recursive($json, 'cucumber_status', &$statuses);
          //update global status
          $global_status["passed"] += $statuses["passed"];
          $global_status["failed"] += $statuses["failed"];
          //html output for this site
          echo '<li class="site '.($statuses["failed"] == 0 ? "passed" : "failed").'">';
          echo '<span class="details">passed: '.$statuses["passed"].", failed: ".$statuses["failed"];
          echo nagios_url_for($site);
          echo "</span>";
          echo "<a href=\"$html_dir/$site.html\">$site</a> ";
          echo "</li>";
        }
      }
    ?>
    </ul>
    <script type="text/javascript">
      $(function() {
        var msg = "";
        msg = msg + $('li').length + " sites, ";
        msg = msg + <?php echo array_sum(array_values($global_status)); ?> + " tests<br />";
        msg = msg + <?php echo $global_status["passed"] ?> + " passed, ";
        msg = msg + <?php echo $global_status["failed"] ?> + " failed<br />";
        msg = msg + '<?php echo $additional_info; ?>';
        $('#totals').html(msg); //"TODO-1 scenario (1 passed)<br />11 steps (11 passed)";
        $('#cucumber-header').addClass('<?php echo ($global_status["failed"] == 0 ? "passed" : "failed"); ?>');
        $('li').live('click', function() {
          window.location = $(this).children('a').attr('href');
        });
      });
    </script>
  </div>
</body>
</html>
