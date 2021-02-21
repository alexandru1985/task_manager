<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
        $clientDetails = $this->clientDetails();
        $this->details['message'] = $clientDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $details = $this->details;
        return $this->subject($details['subject'])->view('mail', compact('details'));
    }

    function clientDetails()
        {
            if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
            {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
            {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
            $ip=$_SERVER['REMOTE_ADDR'];
            }
            $xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=". $ip);

            $result = '';
            $result .= 'IP: '.$xml->geoplugin_request . '<br>';
            $result .= 'Country: '.$xml->geoplugin_countryName . '<br>';
            $result .= 'City: '.$xml->geoplugin_city. '<br>';
            $result .= 'Latitude: '.$xml->geoplugin_latitude. '<br>';
            $result .= 'Longitude: '.$xml->geoplugin_longitude. '<br>';
            $result .= 'Timezone: '.$xml->geoplugin_timezone. '<br>';
            return $result;
        }

}
