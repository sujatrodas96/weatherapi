<?php
$cache_file = 'data.json';

//The file_exists() function checks whether a file or directory exists.
if(file_exists($cache_file)){
  $data = json_decode(file_get_contents($cache_file));
}
else
{
  $api_url = 'https://content.api.nytimes.com/svc/weather/v2/current-and-seven-day-forecast.json';

  //The file_get_contents() reads a file into a string.This function is the preferred way to read the contents of a file into a string. It will use memory mapping techniques, if this is supported by the server, to enhance performance.
  $data = file_get_contents($api_url);
  
  //The file_put_contents() writes data to a file.
  //If FILE_USE_INCLUDE_PATH is set, check the include path for a copy of filename
  //Create the file if it does not exist
  //Open the file
  //Lock the file if LOCK_EX is set
  //If FILE_APPEND is set, move to the end of the file. Otherwise, clear the file content
  //Write the data into the file
  //Close the file and release any locks
  file_put_contents($cache_file, $data);

  //The json_decode() function is used to decode or convert a JSON object to a PHP object.
  $data = json_decode($data);
}
$current = $data->results->current[0];
$forecast = $data->results->seven_day_forecast;
?>
<?php
  function convert2cen($value,$unit)
  {
    if($unit=='C')
    {
      return $value;
    }
    else if($unit=='F')
    {
      $cen = ($value - 32) / 1.8;
        return round($cen,2);
    }
  }
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha256-YLGeXaapI0/5IgZopewRJcFXomhRMlYYjugPLSyNjTY=" crossorigin="anonymous" />
<link rel="stylesheet" type="text/css" href="css/custom.css">
<div class="container wrapper">
  <br>
  
  <div class="row">
    <h3 class="title text-center bordered">Weather Report for <?php echo $current->city.','.$current->state.'('.$current->country.')';?></h3>
    <div class="col-md-12" style="padding-left:0px;padding-right:0px;">
      <div class="single bordered" style="padding-bottom:25px;background:url('image/back3.jpg') no-repeat ;border-top:0px;background-size: cover;">
        <div class="row">
          <div class="col-sm-9" style="font-size:20px;text-align:left;padding-left:70px;">
            <p class="aqi-value"><?php echo convert2cen($current->temp,$current->temp_unit);?> °C</p>
            <p class="weather-icon">
              <img style="margin-left:-10px;" src="<?php echo $current->image;?>">
              <?php echo $current->description;?>
            </p>
            <div class="weather-icon">
              <p><strong>Wind Speed : </strong><?php echo $current->windspeed;?> <?php echo $current->windspeed_unit;?></p>
              <p><strong>Pressue : </strong><?php echo $current->pressure;?> <?php echo $current->pressure_unit;?></p>
              <p><strong>Visibility : </strong><?php echo $current->visibility;?> <?php echo $current->visibility_unit;?></p>
            </div>
          </div>
        </div>
          </div>
    </div>
  </div>
  <br><br>
  <div class="row">
    <h3 class="title text-center bordered">5 Days Weather Forecast for <?php echo $current->city.','.$current->state.'('.$current->country.')';?></h3>
    <?php $loop=0; foreach($forecast as $f){ $loop++;?>
      <div class="single forecast-block bordered">
        <h3><?php echo $f->day;?></h3>
        <p style="font-size:1em;" class="aqi-value"><?php echo convert2cen($f->low,$f->low_unit);?> °C - <?php echo convert2cen($f->high,$f->high_unit);?> °C</p>
        <hr style="border-bottom:1px solid #fff;">
        <img src="<?php echo $f->image;?>">
        <p><?php echo $f->phrase;?></p>
      </div>
    <?php } ?>
  </div>
</div>