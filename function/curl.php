<?php

/**
* OO cURL Class
* Object oriented wrapper for the cURL library.
* @author David Hopkins (semlabs.co.uk)
* @version 0.3.1
*/
class CURL
{

    public $sessions                 =    array();
    public $retry                    =    5; 
    public $timeout                  =    60;
    public $conn_timeout             =    10;
    public $curl_retry             =    TRUE;

    /**
    * Adds a cURL session to stack
    * @param $url string, session's URL
    * @param $opts array, optional array of cURL options and values
    */
    public function addSession( $url, $name, $opts = false )
    {
        $this->sessions[$name] = curl_init( $url );
        if(!isset($opts['19913'])){
            $opts['19913'] = 1;
        }
        if(!isset($opts['52'])){
            $opts['52'] = 1;
        }
        if(!isset($opts['13'])){
            $opts['13'] = $this->timeout;
        } 
        if(!isset($opts['78'])){
            $opts['78'] = $this->conn_timeout;
        } 
        $this->setOpts( $opts, $name );
    }

    /**
    * Sets an option to a cURL session
    * @param $option constant, cURL option
    * @param $value mixed, value of option
    * @param $key int, session key to set option for
    */
    public function setOpt( $option, $value, $key = 0 )
    {
        curl_setopt( $this->sessions[$key], $option, $value );
    }

    /**
    * Sets an array of options to a cURL session
    * @param $options array, array of cURL options and values
    * @param $key int, session key to set option for
    */
    public function setOpts( $options, $key = 0 )
    {
        curl_setopt_array( $this->sessions[$key], $options );
    }

    /**
    * Executes as cURL session
    * @param $key int, optional argument if you only want to execute one session
    */
    public function exec( $key = false )
    {
        $no = count( $this->sessions );
        $res = $this->execMulti();    
        if( $res )
            return $res;
    }

    /**
    * Executes a single cURL session
    * @param $key int, id of session to execute
    * @return array of content if CURLOPT_RETURNTRANSFER is set
    */

    public function execSingle_File_Get( $url )
    {
        $res = FALSE;
        if( $this->retry > 0 )
        {
            $retry = $this->retry;
            $code[0] = 0;
            while( $retry >= 0 && $res == FALSE) 
            {
                sleep(1);
                $ctx = stream_context_create(array('http'=>
                    array(
                        'timeout' => 15, // 1 200 Seconds = 20 Minutes
                    )
                ));

                $res = file_get_contents($url, false, $ctx);       
                $retry--;
            }
        }
        return $res;
    }

    public function execSingle( $id )
    {
        $res = '';
        if( $this->retry > 0 )
        {
            $retry = $this->retry;
            $code[0] = 0;
            while( $retry >= 0 && ($code[0] >= 400 || $code[0] == 0) ) 
            {
                sleep(1);
                $res = curl_exec( $this->sessions[$id] );
                $code = $this->info( $id, CURLINFO_HTTP_CODE );          
                $retry--;
            }
        }else{
            foreach ( $this->sessions as $i => $url ){
                if($id == $i){
                    $res = curl_exec( $this->sessions[$i] );
                }
            }
        }
        return $res;
    }

    /**
    * Executes a stack of sessions
    * @return array of content if CURLOPT_RETURNTRANSFER is set
    */
    public function execMulti()
    {
        $mh = curl_multi_init();
        $res = array();   
        #Add all sessions to multi handle
        foreach ( $this->sessions as $i => $url )
            curl_multi_add_handle( $mh, $this->sessions[$i] );

        do
            $mrc = curl_multi_exec( $mh, $active );
        while ( $mrc == CURLM_CALL_MULTI_PERFORM );

        while ( $active && $mrc == CURLM_OK )
        {
          /*
          https://bugs.php.net/bug.php?id=63411
          https://bugs.php.net/bug.php?id=63842
          curl_multi_select() для PHP 5.3.18 и выше всегда вернет -1

          От разработчиков libcurl http://curl.haxx.se/libcurl/c/curl_multi_fdset.html
          "When libcurl returns -1 in max_fd, it is because libcurl currently does something that isn't possible for your application to monitor
          with a socket and unfortunately you can then not know exactly when the current action is completed using select().
          When max_fd returns with -1, you need to wait a while and then proceed and call curl_multi_perform anyway.
          How long to wait? I would suggest 100 milliseconds at least, but you may want to test it out in your own particular conditions to find a suitable value. "

            Старый код:
            if ( curl_multi_select( $mh ) != -1 )
            {
                do
                    $mrc = curl_multi_exec( $mh, $active );
                while ( $mrc == CURLM_CALL_MULTI_PERFORM );
            }
          */
            if (curl_multi_select($mh) == -1) { usleep(150); }

            do
                $mrc = curl_multi_exec( $mh, $active );
            while ( $mrc == CURLM_CALL_MULTI_PERFORM );
        }

        if ( $mrc != CURLM_OK )
            echo "Curl multi read error $mrc\n";

        #Get content foreach session, retry if applied
        foreach ( $this->sessions as $i => $url )
        {
            $code = $this->info( $i, CURLINFO_HTTP_CODE );
            $url_code = $this->info( $i );
            if( $code[0] > 0 && $code[0] < 400 ){
                $res[$i] = curl_multi_getcontent( $this->sessions[$i] );
            }else{
                if( $this->retry > 0 )
                {
                    if($this->curl_retry == TRUE){
                        $eRes = $this->execSingle( $i );
                    }else{
                        $eRes = $this->execSingle_File_Get( $url_code[0]['url'] );    
                    }
                    $res[$i] = false;
                    if( $eRes ){
                        $res[$i] = $eRes;
                    }
                }
            }

            curl_multi_remove_handle( $mh, $this->sessions[$i] );
        }

        curl_multi_close( $mh );

        return $res;
    }

    /**
    * Closes cURL sessions
    * @param $key int, optional session to close
    */
    public function close( $key = false )
    {
        if( $key === false )
        {
            foreach( $this->sessions as $session )
                curl_close( $session );
        }
        else
            curl_close( $this->sessions[$key] );
    }

    /**
    * Remove all cURL sessions
    */
    public function clear()
    {
        foreach( $this->sessions as $session )
            curl_close( $session );
        unset( $this->sessions );
    }

    /**
    * Returns an array of session information
    * @param $key int, optional session key to return info on
    * @param $opt constant, optional option to return
    */
    public function info( $key = false, $opt = false )
    {
        if( $key === false )
        {
            foreach( $this->sessions as $key => $session )
            {
                if( $opt )
                    $info[] = curl_getinfo( $this->sessions[$key], $opt );
                else
                    $info[] = curl_getinfo( $this->sessions[$key] );
            }
        }
        else
        {
            if( $opt )
                $info[] = curl_getinfo( $this->sessions[$key], $opt );
            else
                $info[] = curl_getinfo( $this->sessions[$key] );
        }

        return $info;
    }

    /**
    * Returns an array of errors
    * @param $key int, optional session key to retun error on
    * @return array of error messages
    */
    public function error( $key = false )
    {
        if( $key === false )
        {
            foreach( $this->sessions as $session )
                $errors[] = curl_error( $session );
        }
        else
            $errors[] = curl_error( $this->sessions[$key] );

        return $errors;
    }

    /**
    * Returns an array of session error numbers
    * @param $key int, optional session key to retun error on
    * @return array of error codes
    */
    public function errorNo( $key = false )
    {
        if( $key === false )
        {
            foreach( $this->sessions as $session )
                $errors[] = curl_errno( $session );
        }
        else
            $errors[] = curl_errno( $this->sessions[$key] );

        return $errors;
    }

}

?>