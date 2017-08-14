<?php

// moon.php - by Hannah Leitheiser
//   Computes the current phase of the moon, three future phases, and draws
//   the moon and that data on a PNG image to ouput a PNG image.
//   run: php moon.php
//   requires: php's gd library for image processing
//       moon.png   1280x720 background image
//       firstq.png 35x35 first quarter moon icon
//       full.png   35x35 full moon icon
//       lastq.png  35x35 last quarter moon icon
//       new.png    35x35 new moon icon


header('Content-Type: image/png');
 
// ------------------------------ calculations ---------------------------------

$lunarMonthDays=29.5306; // days
$lunarMonth=$lunarMonthDays * 86400; // seconds

// compute periods from a known new moon
$moonPeriods = -(1503343800 - time()) / (29.5306 * 86400);
// computer the phase as a fraction from zero to one.  Zero and one indicating new moon.  
// 0.5 indicating full moon.
$moonPhase = $moonPeriods - floor($moonPeriods);
// Use theta = moonPhase*2pi
// cos(theta) represents one coordinate of a unit circle, or 
// the only relevant coordinate of movement around a circle on edge.  
//The light at the moon's equator can be imagined as such a circle. 
$lightPortion=(1-cos($moonPhase*2*3.141592659))/2;


// Save the phase label.  Demarkations are somewhat arbitrary.  
// I gave 1/16 of the month -- about two days I suppose, to 
// momentary phases such as new moon and full moon.
switch( floor($moonPhase * 32) ) {
      case 0:
            $phase= "New moon";
         break;
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
         $phase = "Waxing crescent";
         break;
      case 7:
      case 8:
         $phase = "First Quarter";
         break;
      case 9:
      case 10:
      case 11:
      case 12:
      case 13:
      case 14:
         $phase = "Waxing Gibbous";
         break;
      case 15:
      case 16:
         $phase = "Full Moon";
         break;
      case 17:
      case 18:
      case 19:
      case 20:
      case 21:
      case 22:
         $phase = "Waining Gibbous";
         break;
      case 23:
      case 24:
         $phase = "Last Quarter";
         break;
      case 25:
      case 26:
      case 27:
      case 28:
      case 29:
      case 30:
         $phase = "Waining Crescent";
         break;
      case 31:
         $phase = "New Moon";
};


// background image.  Should contain a moon.

$image = imagecreatefrompng('moon.png');
$text_color = imagecolorallocate($image, 200, 200, 200);

// ------------------ print current date, phase, and ilumination percent to the image -------------

$currentDate = date("Y/m/d");
imagestring($image, 5, 1000, 70,  $currentDate, $text_color);

imagestring($image, 5, 1000, 100,  $phase, $text_color);

$ilumPercent=floor($lightPortion*100);
$text = "$ilumPercent%";
imagestring($image, 5, 1000, 130,  $text, $text_color);

// ----  draw the dark portion of the moon over the light portion already in moon.png -------------

$moonX = 665;
$moonY = 123;
$moonDrawSize=215;

$black = imagecolorallocate($image, 0, 0, 0); 

// Draw the moon, using the moonDrawSize as the radius
for($y=0; $y<$moonDrawSize;$y=$y+1) {
    // a represents vertical distance from the center.
    $a=($moonDrawSize/2)-($y);
    // total width on this line
    $width=floor(sqrt(($moonDrawSize/2)*($moonDrawSize/2) - $a*$a)*2);
    $dark=floor((1-$lightPortion)*$width);
   
    // whether the dark side is drawn from the left or right side depends on the phase.
    // Set up the variables appropriately.

    if($moonPhase < 0.5) {
      $start = $moonX-$width/2;
      $end = $start+$dark;
      }
   else {
      $end = $moonX+$width/2;
      $start = $end-$dark;
      }

   // Set the pixels
   $x=$start;
    for( ; $x < $end ;$x=$x+1) {
      imagesetpixel($image, $x,$y+$moonY, $black);
      }
   }

// ------------------- print the next three phases --------------------------------------

// There may have been more elegant ways to do this in PHP.
// But this prints the next three phases.
// A little icon of the phase prints first, then the date.

// Setup the variables.  The next phases, of course,
// Depend on the current phase.

if($moonPhase < 0.25) {
   $nextPhase1 = imagecreatefrompng('firstq.png');
   $nextPhase1time = time() + (0.25 - $moonPhase) * $lunarMonth;
   $nextPhase2 = imagecreatefrompng('full.png');
   $nextPhase2time = time() + (0.50 - $moonPhase) * $lunarMonth;
   $nextPhase3 = imagecreatefrompng('lastq.png');
   $nextPhase3time = time() + (0.75 - $moonPhase) * $lunarMonth;
   }
else {
   if($moonPhase < 0.50) {
      $nextPhase1 = imagecreatefrompng('full.png');
      $nextPhase1time = time() + (0.50 - $moonPhase) * $lunarMonth;
      $nextPhase2 = imagecreatefrompng('lastq.png');
      $nextPhase2time = time() + (0.75 - $moonPhase) * $lunarMonth;
      $nextPhase3 = imagecreatefrompng('new.png');
      $nextPhase3time = time() + (1.00 - $moonPhase) * $lunarMonth;
      }
   else {

      if($moonPhase < 0.75) {
         $nextPhase1 = imagecreatefrompng('lastq.png');
         $nextPhase1time = time() + (0.75 - $moonPhase) * $lunarMonth;
         $nextPhase2 = imagecreatefrompng('new.png');
         $nextPhase2time = time() + (1.00 - $moonPhase) * $lunarMonth;
         $nextPhase3 = imagecreatefrompng('firstq.png');
         $nextPhase3time = time() + (1.25 - $moonPhase) * $lunarMonth;
         }
      else {
            $nextPhase1 = imagecreatefrompng('new.png');
            $nextPhase1time = time() + (1.00 - $moonPhase) * $lunarMonth;
            $nextPhase2 = imagecreatefrompng('firstq.png');
            $nextPhase2time = time() + (1.25 - $moonPhase) * $lunarMonth;
            $nextPhase3 = imagecreatefrompng('full.png');
            $nextPhase3time = time() + (1.50 - $moonPhase) * $lunarMonth;
            }
         }
      }
   
// Do the printing.

$newPhaseX = 610;
$newPhaseY = 390;
$text = date("Y/m/d", $nextPhase1time);
$text_color = imagecolorallocate($image, 200, 200, 200);
imagestring($image, 5, $newPhaseX + 55, $newPhaseY+8,  $text, $text_color);
imagecopy($image, $nextPhase1, $newPhaseX,$newPhaseY,0,0,35,35);
$text = date("Y/m/d", $nextPhase2time);
$text_color = imagecolorallocate($image, 200, 200, 200);
imagestring($image, 5, $newPhaseX + 55+50, $newPhaseY+75+8,  $text, $text_color);
imagecopy($image, $nextPhase2, $newPhaseX+50,$newPhaseY+75,0,0,35,35);
$text = date("Y/m/d", $nextPhase3time);
$text_color = imagecolorallocate($image, 200, 200, 200);
imagestring($image, 5, $newPhaseX + 55+100, $newPhaseY+150+8,  $text, $text_color);
imagecopy($image, $nextPhase3, $newPhaseX+100,$newPhaseY+150,0,0,35,35);
 
// ---------------------- output the image -------------------------------------------0


imagepng($image);
?>
