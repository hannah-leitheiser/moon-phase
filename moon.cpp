   
   // Displays the moon phase based on the system clock.
   // g++ moon.cpp
   
   #include <iostream>      
   #include <time.h>
   #include <math.h>

   using namespace std;

   int main () {
     
      // Get the current time
      time_t timer;
      time(&timer);
      // Aug 21, 2017  2:30 p.m. Central Time - New Mooni
      // Converted to UNIX time stamp: 1503343800

      // Periods after the Aug 21st new moon.
      double lunar_month = (timer - 1503343800) /  (29.530587981 * 86400);
      // Remove the whole number portion (or take 1 - the whole number portion
      // for negative numbers.
      double lunar_phase = lunar_month - floor(lunar_month);

     cout << "The moon is ";
     switch( int(lunar_phase * 16) ) {
      case 0:
         cout << "New moon.\n";
         break;
      case 1:
      case 2:
      case 3:
         cout << "Waxing crescent.\n";
         break;
      case 4:
         cout << "First Quarter.\n";
         break;
      case 5:
      case 6:
      case 7:
         cout << "Waxing Gibbous.\n";
         break;
      case 8:
         cout << "Full Moon.\n";
         break;
      case 9:
      case 10:
      case 11:
         cout << "Waining Gibbous.\n";
         break;
      case 12:
         cout << "Last Quarter..\n";
         break;
      case 13:
      case 14:
      case 15:
         cout << "Waining Crescent.\n";
         break;
      };
     return 0;
   }


   
