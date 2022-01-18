<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Airport;
use App\Metar;

class SampleController extends Controller
{

    // Main
    //-------------------------------------------------------------
    public function index()
    {
        // Get METAR of all Japan airport from NOAA API
        $sql = new Metar();
        $url = "https://www.aviationweather.gov/adds/dataserver_current/httpparam?datasource=metars&requestType=retrieve&format=xml&mostRecentForEachStation=constraint&hoursBeforeNow=12&stationString=~jp";
        $metar_list = simplexml_load_file($url);
        if ($metar_list === FALSE) {
            return;
        } else {
            foreach ($metar_list->data->METAR as $metar) {
                DB::table('metars')->updateOrInsert(
                    ['icao_metars' => $metar->station_id],
                    [
                        'icao_metars' => $metar->station_id,
                        'wind_dir'   => $metar->wind_dir_degrees,
                        'wind_speed' => $metar->wind_speed_kt,
                        'altim'      => $metar->altim_in_hg,
                        'category'   => $metar->flight_category,
                        'raw_text'   => $metar->raw_text
                    ]
                );
            }
            
            // *****************************************************************************
            // SQL: SELECT * FROM airports left join metars using(icao) GROUP BY icao, name ORDER BY order;
            $lists = \DB::table('airports')
            ->leftJoin('metars','airports.icao','=','metars.icao_metars')
            ->groupby(['airports.icao','airports.name'])
            ->orderBy('airports.order', 'asc')
            ->get();

            $lists_coordinate = \DB::table('airports')
            ->leftJoin('metars','airports.icao','=','metars.icao_metars')
            ->groupby(['airports.icao','airports.name'])
            ->get();
            // Get coordinate of airport for the image
            $locations = \App\Airport::select('x', 'y')->get();
            
            // Export the image
            // *****************************************************************************
            $file = "img\japan.png";
            $newfile = "img\japan_moji.png";
            $size = 10;
            $angle = 0;
            $fontfile = "C:\bin\laravel_project\public\RictyDiminished-Regular.ttf";

            $image = imagecreatefrompng($file);

            $i = 0;
            $color_array = [0, 0, 0];

            // Exporting weather information to images in order
            foreach ($locations as $key_location => $value_location) {
                // 1. Put ICAO code to the image
                // Set color profile to change the color of the ICAO code text for each flight category
                switch ($lists_coordinate[$i]->category) {
                    case 'VFR':
                        $color_array = [0, 168, 22];    // Green for VFR
                        break;
                    case 'MVFR':
                        $color_array = [0, 0, 255];     // Blue for MVFR
                        break;
                    case 'LIFR':
                        $color_array = [255, 111, 0];   // Orange for LIFR
                        break;
                    case 'IFR':
                        $color_array = [255, 0, 0];     // Red for IFR
                        break;
                }
                // Set text color of ICAO code
                $color = imagecolorallocate($image, $color_array[0], $color_array[1], $color_array[2]);
                //Array of text coordinate
                $array_icao = [
                    $lists_coordinate[$i]->icao => [
                        $lists_coordinate[$i]->icao,
                        $value_location['x'],
                        $value_location['y']
                    ]
                ];
                // Add ICAO code in the image
                foreach ($array_icao as $key => $value) {
                imagettftext(
            	    $image,
            	    $size,
            	    $angle,
            	    $value[1],
            	    $value[2],
            	    $color,
            	    $fontfile,
            	    $value[0]);
                }

                // Set text color of wind/QNH
                $color = imagecolorallocate($image, 0, 0, 0);
                //Array of text coordinate
                $array_metar = [
                    $lists[$i]->icao => [
                        str_pad($lists_coordinate[$i]->wind_dir, 3, 0, STR_PAD_LEFT).'\\'.str_pad($lists_coordinate[$i]->wind_speed, 2, 0, STR_PAD_LEFT).' '.number_format(round($lists_coordinate[$i]->altim, 2), 2),
                        $value_location['x'],
                        $value_location['y']
                    ]
                ];
                // Add wind/QNH text in the image
                foreach ($array_metar as $key => $value) {
                imagettftext(
            	    $image,
            	    $size,
            	    $angle,
            	    $value[1] + 35,
            	    $value[2],
            	    $color,
            	    $fontfile,
            	    $value[0]);
                }
                // Increment(Next airport)
                $i++;
            }
            // Export the image
            imagejpeg($image, $newfile);

            return view('sample', [
                "lists" => $lists
            ]);
        }
    }
    
    // Add airports to database
    //-------------------------------------------------------------
    public function addAirport()
    {
        // Get all name of airports from NOAA API
        $url = "https://www.aviationweather.gov/adds/dataserver_current/httpparam?dataSource=stations&requestType=retrieve&format=xml&stationString=~jp";
        $airport_list = simplexml_load_file($url);
        if ($airport_list === FALSE) {
            return "FAIL";
        } else {
            foreach ($airport_list->data->Station as $station) {
                $sql = new Airport();
                $sql->icao = $station->station_id;
                $sql->name = $station->site;
                $sql->x = 0;
                $sql->y = 0;
                $sql->save();
            }
            return view('addAirport', [
                "airport_list" => $airport_list
            ]);
        }
        
    }
}
