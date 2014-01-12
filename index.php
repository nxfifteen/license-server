<?php

date_default_timezone_set('Europe/London'); // stop php from whining

$format = 'html';
$cname = '';

$theme = 'default';
//$theme = 'double-windsor';

// use a match instead of preg_replace to ensure we got the cname
$domain = explode(".", $_SERVER['HTTP_HOST']);
preg_match('/^([a-z0-9\-]+)$/', $domain[0], $match);

if (count($match) == 2) {
  $cname = $match[1];
}

$user_file = 'users/' . $cname . '.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cname) {
  try {
    $data = json_decode(file_get_contents('php://input'));
    if (!property_exists($data, 'copyright')) {
      Throw new Exception('>>> JSON requires "copyright" property and value');
    }

    if (file_exists($user_file)) {
      Throw new Exception(wordwrap('>>> User already exists - to update values, please send a pull request on https://git.research.nxfifteen.me.uk/web-applications/license-server'));
    }

    if (!file_put_contents($user_file, json_encode($data))) {
      Throw new Exception(wordwrap('>>> Unable to create new user - please send a pull request on https://git.research.nxfifteen.me.uk/web-applications/license-server'));
    }

    echo '>>> MIT license page created: http://' . $_SERVER['HTTP_HOST'] . "\n\n";

    // try to add to github...!
    //exec('cd /WWW/mit-license && git add ' . $user_file . ' && git commit -m"automated creation of ' . $user_file . '"', $out, $r);
    //print_r($out); echo "\n"; print_r($r); echo "\n";
    //$out = array();
    //exec('cd /WWW/mit-license && git push origin develop -v 2>&1', $out, $r);
    //print_r($out); echo "\n"; print_r($r); echo "\n";

  } catch (Exception $e) {
    echo $e->getMessage() . "\n\n";
  }
  exit;
}

/**
 * Load up the user.json file and read properties in
 **/
if ($cname && file_exists($user_file)) {
  $user = json_decode(file_get_contents($user_file));
  
  $holder = htmlentities($user->yourname, ENT_COMPAT, 'UTF-8');
  
  if (property_exists($user, 'yourname') AND property_exists($user, 'url') AND property_exists($user, 'company') AND property_exists($user, 'companyurl')) {
        $copyright = '<a href="' . $user->url . '">' . $user->yourname . '</a> of <a href="' . $user->companyurl . '">' . $user->company . '</a>';
  } elseif (property_exists($user, 'yourname') AND !property_exists($user, 'url') AND property_exists($user, 'company') AND !property_exists($user, 'companyurl')) {
        $copyright = $user->yourname . ' of ' . $user->company;
  } elseif (property_exists($user, 'yourname') AND !property_exists($user, 'url') AND property_exists($user, 'company') AND property_exists($user, 'companyurl')) {
        $copyright = $user->yourname . ' of <a href="' . $user->companyurl . '">' . $user->company . '</a>';
  } elseif (property_exists($user, 'yourname') AND property_exists($user, 'url') AND property_exists($user, 'company') AND !property_exists($user, 'companyurl')) {
        $copyright = '<a href="' . $user->url . '">' . $user->yourname . '</a> of ' . $user->company;
  } elseif (property_exists($user, 'yourname') AND property_exists($user, 'url') AND !property_exists($user, 'company')) {
        $copyright = '<a href="' . $user->url . '">' . $user->yourname . '</a>';
  } elseif (property_exists($user, 'yourname') AND !property_exists($user, 'url') AND !property_exists($user, 'company')) {
        $copyright = $user->yourname;
  } elseif (!property_exists($user, 'yourname') AND property_exists($user, 'company') AND property_exists($user, 'companyurl')) {
        $copyright = '<a href="' . $user->companyurl . '">' . $user->company . '</a>';
  } elseif (!property_exists($user, 'yourname') AND property_exists($user, 'company') AND !property_exists($user, 'companyurl')) {
        $copyright = $user->companyurl;
  }
  
  if (property_exists($user, 'yourname')) {
    $yourname = $user->yourname;
  }
  
  if (property_exists($user, 'url')) {
    $url = '<a href="' . $user->url . '">' . $user->url . '</a>';
  }
  
  if (property_exists($user, 'email')) {
    $email = ' <a href="mailto:' . $user->email . '">Email Copyright Holder</a>';
  }
  
  if (property_exists($user, 'pgpkey')) {
    $pgpkey = $user->pgpkey;
  }
  
  if (property_exists($user, 'boilerplate')) {
    $boilerplate = $user->boilerplate;
  }
  
  if (property_exists($user, 'pgpid') AND property_exists($user, 'pgpurl')) {
    $pgpid = '<a href="' . $user->pgpurl . '">' . $user->pgpid . '</a>';
  } elseif (property_exists($user, 'pgpid') AND !property_exists($user, 'pgpurl')) {
    $pgpid = $user->pgpid;
  }
  
  if(property_exists($user, 'gravatar') && $user->gravatar === true){
    $gravatar = '<img id="gravatar" src="http://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '" />';
  }

  if (property_exists($user, 'format')) {
    if (strtolower($user->format) == 'txt') {
      $format = 'txt';
    } elseif (strtolower($user->format) == 'md') {
      $format = 'md';
    }
  }

  if (property_exists($user, 'theme')) {
    if (file_exists('themes/' . $user->theme . '.css')) {
      $theme = $user->theme;
    }
  }
} else {
  $holder = "&lt;copyright holders&gt;";
}

/**
 * Now process the request url. Optional parts of the url are (in order):
 * [sha]/[year|year-range]/license.[format]
 * eg. http://stuart.nx15.at/a526bf7ad1/2009-2010/license.txt
 **/

// grab sha from request uri
$request_uri = explode('/', $_SERVER["REQUEST_URI"]);

$request = array_pop($request_uri);
// in case there's a trailing slash (unlikely)
if ($request == '') $request = array_pop($request_uri);

// url file format overrides user preference
if (stripos($request, 'license') === 0) {
  if (array_pop(explode('.', strtolower($request))) == 'txt' OR
      array_pop(explode('.', strtolower($request))) == 'html' OR
      array_pop(explode('.', strtolower($request))) == 'md') {
    $format = array_pop(explode('.', strtolower($request)));
  } else {
    $format = 'html';
  }

  // move down to the next part of the request
  $request = array_pop($request_uri);
}

// check if we have a year or a year range up front
$year = date('Y');
preg_match('/^(\d{4})(?:(?:\-)(\d{4}))?$/', $request, $match);
if (count($match) > 1) {
  if ($match[2]) {
    $year = $match[2];
  }
  if ($match[1]) {
    $year = $match[1] == $year ? $year : $match[1] . '-' . $year;
  }
  $request = array_pop($request_uri);
}

// check if there's a SHA on the url and read this to switch license versions
$sha = '';
if ($request != "" && $request != "/" && $request != "/index.php") {
  $sha = preg_replace('/[^a-f0-9]/', '', $request);
} else if (isset($user) && property_exists($user, 'version')) {
  $sha = preg_replace('/[^a-f0-9]/', '', $user->version);
}

// if sha specified, use that revision of licence
$license = '';
if ($sha != "") {
  $out = array();
  // preg_replace should save us - but: please help me Obi Wan...
  exec("git show " . $sha . ":LICENSE.html", $out, $r);
  if ($r == 0) {
    $license = implode("\n", $out);
  } 
}

// if we didn't manage to read one in, use latest
if ($license == "") {
  $license = file_get_contents('LICENSE.html');
}

// replace info tag and display
$info = $year . ' ' . $holder;
$license = str_replace('{{info}}', $info, $license);
$license = str_replace('{{theme}}', $theme, $license);
$license = str_replace('{{email}}', $email, $license);
$license = str_replace('{{pgpkey}}', $pgpkey, $license);
$license = str_replace('{{pgpid}}', $pgpid, $license);
$license = str_replace('{{url}}', $url, $license);
$license = str_replace('{{copyright}}', $copyright, $license);
$license = str_replace('{{boilerplate}}', $boilerplate, $license);
$license = str_replace('{{yourname}}', $yourname, $license);
$license = str_replace('{{year}}', $year, $license);
if ($format != 'txt') { $license = str_replace('{{gravatar}}', $gravatar . "&nbsp;", $license); } else { $license = str_replace('{{gravatar}}', "", $license); }

// if we want text format, strip out the license from the article tag
// and then strip any other tags in the license.
if ($format == 'txt') {
  $license = array_shift(explode('</article>', array_pop(explode('<article>', $license))));
  $license = preg_replace('/<[^>]*>/', '', trim($license));
  $license = html_entity_decode($license);
  $license = str_replace("  ", "", $license);
  header('content-type: text/plain; charset=UTF-8');
}

echo $license;
