<?php
 
/*THIS IS STRICTLY EXAMPLE SOURCE CODE. IT IS ONLY MEANT TO
QUICKLY DEMONSTRATE THE CONCEPT AND THE USAGE OF THE ACCOUNT AUTHENTICATION SERVICE APIS. PLEASE NOTE THAT THIS IS *NOT* # PRODUCTION-QUALITY CODE AND SHOULD NOT BE USED AS SUCH.
 
 THIS EXAMPLE CODE IS PROVIDED TO YOU ONLY ON AN "AS IS"
 BASIS WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, EITHER
 EXPRESS OR IMPLIED, INCLUDING WITHOUT LIMITATION ANY WARRANTIES
 OR CONDITIONS OF TITLE, NON-INFRINGEMENT, MERCHANTABILITY OR
 FITNESS FOR A PARTICULAR PURPOSE. PAYPAL MAKES NO WARRANTY THAT
 THE SOFTWARE OR DOCUMENTATION WILL BE ERROR-FREE. IN NO EVENT
 SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY
 DIRECT, INDIRECT, INCIDENTAL, SPECIAL,  EXEMPLARY, OR
 CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
 OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF
 THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY
OF SUCH DAMAGE.
 
INSTRUCTIONS
1) Ensure that SSL and fopen() are enabled in the php.ini file
2) Written and Tested with PHP 5.3.0
 
 
IMPORTANT:
When you integrate this code look for TODO as an indication that 
you may need to provide a value or take action before executing this code.
*/
 
//turn php errors on
ini_set('track_errors', true);
 
//set APAPI URL
$url = trim('https://api-3t.sandbox.paypal.com/nvp');
 
 
//Create request body content
 
$body_data = array(	 'USER' => "arithmetic_api1.gmail.com", //TODO
                 		 'PWD' => "PR3C5H65FRZ4XFL5",
                 		 'SIGNATURE' => "AFcWxV21C7fd0v3bYYYRCpSSRl31As2SHZyURzO7SCHrSKjOz3FV4NzS", //TODO
            'RETURNURL' => "http://www.dailykos.com", //TODO
                  	 'CANCELURL' => "http://www.yahoo.com", //TODO
                  	 'LOGOUTURL' => "http://www.google.com", //TODO
                  	 'VERSION' => "3.300000",
            				 'METHOD' => "SetAuthFlowParam"
										);
 
//URL encode the request body content array
$body_data = http_build_query($body_data, '', chr(38));
 
try
{
 
    //create request and add headers
    $params = array('http' => array(
                  'method' => "POST",
                  'content' => $body_data,
                 ));
 
 
    //create stream context
     $ctx = stream_context_create($params);
 
    //open the stream and send request
     $fp = @fopen($url, 'r', false, $ctx);
 
   //get response
  $response = stream_get_contents($fp);
 
  //check to see if stream is open
         if ($response === false) {
        throw new Exception("php error message = " . "$php_errormsg");
     }
      //echo $response . "<br/>";
 
 
       //close the stream
       fclose($fp);
 
 
//parse key from the response
        $key = explode("&",$response);
  //print_r ($key);
         //set url to approve the transaction
 
 
        $payPalURL = "https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_account-authenticate-login&" . str_replace("TOKEN", "token", htmlspecialchars(urldecode($key[0])));
 
        //print the url to screen for testing purposes
         If ( $key[3] == 'ACK=Success')
            {
 
            echo $response . "<br/>";
 
            echo '<a href="' . $payPalURL . '" target="_blank">' . $payPalURL . '</a> <p/>' . htmlspecialchars(urldecode($key[0])) ;
 
 
            }
         else
         {
             echo 'ERROR Code: "' . str_replace("L_ERRORCODE0=", "", htmlspecialchars(urldecode($key[5]))) . '"<br/> Error Long Message: "' .  str_replace("L_LONGMESSAGE0=", "", htmlspecialchars(urldecode($key[7]))) . '"';
         }
}
 
catch(Exception $e)
  {
  echo 'Message: ||' .$e->getMessage().'||';
  }
 
?>