<?php 


if (!is_dir('downloads')) {
	mkdir('downloads' , 0777);
}

if (!is_dir('images')) {
	mkdir('images' , 0777);
}

$url = $_POST['website_url'];;
// initializing curl session

$ch = curl_init();


// setting options
// curl_setopt — Set an option for a cURL transfer

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


// execute

$source_code = curl_exec($ch);

// echo $result;

// close curl session
curl_close($ch);

// matches <img src='anything.jpg/png'>
$pattern = "/<img[^>]+>/";
$matches = "";
preg_match_all($pattern, $source_code , $matches);


// echo "<pre>";
// print_r($matches);



// fopen creates a file , here in write mode
$file = fopen('downloads/scrapped.txt', 'w');
foreach ($matches[0] as $img) {
	fwrite($file, $img."\n");
}

fclose($file);


// pattern for extracting img src value from scrapped.txt
$scrapped = file_get_contents('downloads/scrapped.txt');

// echo $scrapped;
$pattern_src = '/src="([^"]+)"/';
$matches_new = '';
preg_match_all($pattern_src, $scrapped, $matches_new);

// echo "<pre>";
// print_r ($matches_new[1]);

foreach ($matches_new[1] as $img_url) {

$ch_new = curl_init();
curl_setopt($ch_new, CURLOPT_URL, $img_url);
curl_setopt($ch_new, CURLOPT_RETURNTRANSFER, true);
$img = curl_exec($ch_new);
curl_close($ch_new);

$img_name = explode("/", $img_url);
$img_name = $img_name[count($img_name)-1];



file_put_contents('images/'.$img_name, $img);




// print_r($img_name);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>WP-Eligibility Checker</title>
</head>

<body>
    <div class="jumbotron jumbotron-fluid">
        <div class="container text-center">
            <h1 class="display-4">Website Image Downloader</h1>
            <p class="lead">Download all images from a specific site</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <form method="post" action="<?php echo $_SERVER['$PHP_SELF'];?>">
                <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offest-2 col-sm-12 col-xs-12 input-group">
                    <span class="input-group-addon">URL</span>
                    <input type="text" class="form-control" name="website_url" id="website_url" placeholder="Enter Website Address" required autofocus>
                </div>
				<br>
                <div class="text-center" id="website_address">
				  <strong><?php echo $_POST['website_url']; ?></strong>
				</div>
                <br>
                <div id="loading" class="text-center" style="visibility:hidden;display: none;">
                    <i class="fas fa-spinner fa-pulse" style="font-size: 20px;margin: 0 0 20px 0"></i>
                </div>
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-success" id="submit" onclick="return show();">Check Eligibility</button>
                </div>
            </form>
            <br><br><br>
        </div>
    </div>


</body>

</html>

</html>
