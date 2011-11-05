<?php
    
    /*
    Module: EmailBlog
    File: /modules/emailblog/emailblog.php
    Developer: Makis
    */
    
    include "modules/module.php";
    
    function EmailBlogRaw( $data , $sender ) {
        $emailblog = new EmailBlog (stripslashes($data));
        return $emailblog->GetResponse();
    }
    
    Class EmailBlog{
        private $mSender;
        private $mUserId;
        private $mTitle;
        private $mBlogId;
        private $mHash;
        private $mPost;
        private $mResult; //debug info - contains full report
        private $mDebugMode;
        
        public function GetResponse() {
            //$this->mAbandon(''); // logging of all responses, not only errors
            return $this->mResult;
        }

        public function GetSender() {
            return $this->mSender;
        }

        public function GetUserId() {
            return $this->mUserId;
        }

        public function GetTitle() {
            return $this->mTitle;
        }

        public function GetBlogId() {
            return $this->mBlogId;
        }

        public function GetHash() {
            return $this->mHash;
        }

        public function GetPost() {
            return $this->mPost;
        }


        //constructor
        public function EmailBlog($emaildata) {
            $debug = $_GET['debug'];
            if (isset($debug)) {
                if (!($debug>=0 && $debug <=5))
                    $debug = 0;
            }
            else {
                    $debug = 0; //debug mode - verbose reporting (numeric - 0:errors only, 1:basic, 2:+message, 5:mime analysis)
            }
            $this->mDebugMode = $debug;
            
            if ($debug>0) $this->mResult=<<<ENDHEAD
<html><head>
<title>Mail</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head><body>
ENDHEAD;
            
            $this->mParse($emaildata);
            if (!( $emailbloguser = GetUserByEmail( $this->mSender ) )) {
                $this->mAbandon( 'User information could not be retrieved. Maybe you have not given us your correct email address?' );
            }
            if (!( $emailbloguser->CheckPin( $this->mHash ) )) {
                $this->mAbandon( 'Wrong credentials!' );
            }    
            $this->mUserId = $emailbloguser->Id();
            $emailbloguser->Su();
            if ($debug>0) $this->mResult .= 'Identified as <strong>' . $emailbloguser->Username() . ' (' . $this->mUserId . ')</strong><br />';
            
            if (!($theblog = GetBlogByName ($this->mBlogId))) {
                $this->mAbandon( 'This blog does not exist!' );
            }
            if (!( $theblog->IsMine() )) {
                $this->mAbandon( 'Sorry, but this blog is not yours!' );
            }
            if ( $theblog->CreatePost( $this->mTitle, $this->mPost ) ) {
                $this->mResult .= "\nOkay\n";
            } else { $this->mResult .= 'Post failed!'; }

            if ($debug>0) $this->mResult .= '</body></html>';
        }
        
        private function mParse($email) {
            $debug = $this->mDebugMode;
            $return = '';
            if (strlen($email) < 1) { bc_die("Don't mess with me"); }
            list($header, $body) = $this->mSplitBodyHeader($email) or bc_die( 'Not a valid mail!' . (($debug>0)?"\n$email":'') );
            $headers_all = $this->mParseHeaders($header) or bc_die('No headers retrieved');
            $headers = $this->mFilterHeaders($headers_all);
            if (!preg_match('/^[^<]*?<?([A-Za-z0-9._+-]*)@([A-Za-z0-9._-]*)>?$/', $this->mFetchHeader($headers, 'from'), $match)) {
                bc_die("Sorry, but I can't figure out who sent this...\nMaybe your mailer is badly configured?");
            }
            $this->mSender = $sender = $match[1] . '@' . $match[2];
            if ($debug>0) $return .= '<h2>' . $sender . '</h2>';
            
            if (!preg_match('/^(.*?)\[([a-z0-9-]*)\/([A-Za-z0-9]*)\](.*?)$/', $this->mFetchHeader($headers, 'subject'), $match)) {
                if ($debug>0) { //inform but do not stop
                    $return .= 'The Subject is not well-formed! Missing [ID/hash]<br />'; 
                } else { //not in debug mode - critical, abandon the process
                    $this->mAbandon('The Subject is not well-formed! Missing [ID/hash]');
                }
            }
            $this->mTitle = $topic = $match[1] . $match[4];
            $this->mBlogId = $id = $match[2];
            $this->mHash = $hash = $match[3];
            if ($debug>0) $return .= '<strong>ID#' . $id . '</strong><br />';
            if ($debug>0) $return .= 'Topic: <strong>' . $topic . '</strong><br /><br />';
            
            if (list($ctype, $mtype, $boundary, $maincharset) = $this->mFetchType($this->mFetchHeader($headers, 'Content-Type'))) {
                if ($debug>4) $return .= '<strong>' . $ctype . '/' . $mtype . '</strong> (as ' . $this->mFetchHeader($headers, 'Content-Transfer-Encoding') . ') ' . ( (strlen($boundary)>1) ? 'up to <strong>' . $boundary . '</strong><br />' : '<br />' );
                if (strlen($boundary)>1 && ($ctype == 'multipart' || $ctype == 'message')) {
                    $pos=0;
                    $i=0;
            
                    while($i<20 && $pos<strlen($body)) {
                        $i++;
                        if ($debug>4) $return .= '<hr />';
                        if (!($newpos = strpos($body, $boundary, $pos+1))) break;
                        $mime[$i]['raw']=substr($body, $pos, $newpos - $pos + 1);
                        if ($debug>4) $return .= $pos . '~' . $newpos . ' (' . strlen($boundary) . '): ' . $this->mHtmlEncode($mime[$i]['raw']) . '<br />';
                        $mime[$i]['raw']=substr($mime[$i]['raw'], 0, strrpos($mime[$i]['raw'], "\n"));
                        list($mime[$i]['header'], $mime[$i]['body']) = $this->mSplitBodyHeader($mime[$i]['raw']);
                        //if ($debug>5) echo '(' . $mime[$i]['body'] . ')<br />';
                        $mime[$i]['headers_all'] = $this->mParseHeaders($mime[$i]['header']);
                        $mime[$i]['headers'] = $this->mFilterHeaders($mime[$i]['headers_all']);
                        if ((list ($mime[$i]['ctype'], $mime[$i]['mtype'], $c, $charset) = $this->mFetchType($this->mFetchHeader($mime[$i]['headers'], 'Content-Type')))) {
                            $ctype=$mime[$i]['ctype'];
                            $mtype=$mime[$i]['mtype'];
                            if (strlen($c)>1) { 
                                $boundary=$c;
                                if (!($newpos = strpos($body, $boundary, $pos+1))) break;
                                $mime[$i]['raw']=substr($mime[$i]['raw'], 0, $newpos);
                                if (strrpos($mime[$i]['raw'], "\n") > $newpos) { 
                                    $newpos = strrpos($mime[$i]['raw'], "\n");
                                    $mime[$i]['raw']=substr($mime[$i]['raw'], 0, $newpos);
                                } else { $pos = $newpos + strlen($boundary) + 2; }
                                if ($debug>4) $return .= 'New position: {$newpos}!<br />';
                                list($sameheader, $mime[$i]['body']) = $this->mSplitBodyHeader($mime[$i]['raw']);
                                if ($debug>4) $return .= $pos . '~' . $newpos . ' (' . strlen($boundary) . '): ' . $this->mHtmlEncode($mime[$i]['raw']) . '<br />';
                            }
                        } else { //our last part contained no damn headers at all, and we've met a new boundary - that's (as far as I know) only met on multipart/alternative parts which are usually empty (supposed to be alternative plaintext for attachments)
                            if ($i>2) { 
                                if ($mime[$i-1]['mtype']!='alternative' && $mime[$i-1]['ctype']!='multipart') {
                                    $return .= ('<h2>MIME ERR</h2>'); //if it's not multipart/alternative or even multipart/* or */alternative, then I don't know what the heck happened
                                    break; 
                                }
                            }
                        }
                        if ($debug>4) $return .= '#' . $i .', ' . $pos. '. <strong>' . $ctype . '/' . $mtype . '</strong> up to <strong>' . $boundary . '</strong><br />';
                        if ($ctype == 'text' && $mtype == 'html') {    //case HTML
                            //if ($debug>5) echo '<hr />' . $mime[$i]['body'] . '<hr />';
                            if ( strlen($charset) < 1 ) { $charset = $maincharset; }
                            $this->mPost = trim($this->mHtmlEncode($this->mDecodeBody($mime[$i]['body'], $this->mFetchHeader($mime[$i]['headers'], 'Content-Transfer-Encoding')), 'html', $charset));
                            if ($debug>1) $return .= $this->mPost;
                            break;
                        }
                        if (strrpos($mime[$i]['raw'], "\n") > $newpos) { 
                            $newpos = strrpos($mime[$i]['raw'], "\n");
                            $mime[$i]['raw']=substr($mime[$i]['raw'], 0, $newpos);
                        } else { $pos = $newpos + strlen($boundary) + 2; }
            
                    }
                    
                }
                
                if ( strlen($charset) < 1 ) { $charset = $maincharset; }
                if ($mtype != 'html') {    //No HTML found;
                    if ($ctype != 'text' ) { //Case Multipart --> TEXT:(plain or whatever)
                        for ($i=1; $i<=count($mime); $i++) {
                            if ($mime[$i]['ctype']=='text') { 
                                $this->mPost = trim($this->mHtmlEncode($this->mDecodeBody($mime[$i]['body'], $this->mFetchHeader($mime[$i]['headers'], 'Content-Transfer-Encoding')), 'plain', $charset));
                                if ($debug>1) $return .= $this->mPost;
                                break;
                            }
                        }
                    } else { //Case TEXT only - assume the whole body is our post (decode as quoted-printable if not otherwise specified)
                        $this->mPost = trim($this->mHtmlEncode($this->mDecodeBody($body, $this->mFetchHeader($headers, 'Content-Transfer-Encoding')), 'plain', $charset));
                        if ($debug>1) $return .= $this->mPost;
                    }
                }
            } else { //message is probably HANDMADE (custom, no Content-Type header), so no Content-Transfer-Encoding either, I guess...
                $this->mPost = trim($this->mHtmlEncode($this->mDecodeBody($body, $this->mFetchHeader($headers, 'Content-Transfer-Encoding')), 'plain'));
                if ($debug>1) $return .= $this->mPost;
            }
            if ($debug>0) $return .= '<br /><br />';
    
            //return $return; //'Okay';
            $this->mResult = $return;
        }
    
        private function mAbandon($message) {
            $dead = $this->mResult . $message . (($this->mDebugMode>0)?'</body></html>':"\n");
            /*if ($fh = fopen( '/var/www/vhosts/blogcube.net/httpdocs/beta/etc/test/uploads/emailblog.txt' , "w" )) {
                fputs( $fh , $dead );
                fclose( $fh );
            }    //custom temporary error log */
            die ( $dead );
        }
    
        private function mFetchType ($type) {    //Content-Type: text/html; boundary=""
            if (preg_match('/^(\w*)\/(\w*).*?$/', $type, $match)) {
                $ctype = $match[1]; 
                $mtype = $match[2]; 
                $boundary='';
                if (preg_match('/^.*boundary.*?"(.*)"$/', $type, $match)) {
                    $boundary = $match[1];
                }
                if (preg_match('/^.*charset.([A-Za-z0-9-]*).*$/', $type, $match)) {
                    $charset = $match[1];
                }
                
                return array($ctype, $mtype, $boundary, $charset);
            }
            return false;
        }
        
        private function mFetchHeader($input, $name) {
            $name = strtolower($name);
            foreach ($input as $n => $v) {
                if (strtolower($n) == $name && isset($v)) {
                    return $v;
                }
            }
            return false;
        }
        
        private function mFilterHeaders($input) {    //this actually reads all the headers in the array and returns only the first 
                                            //occurence of the assingment (to prevent spoofing), eg. the first From: field.
            $return = Array();
            $count = count($input);
            for ($i=0; $i<$count; $i++) {
                $key = $input[$i]['name'];
                $val = $input[$i]['value'];
                if (!isset($return[$key])) { 
                    $return[$key] = $val; 
                } else { //field already assigned - detected second+ occurence of an assignment
                    //flag the message as potentially unsafe (to be discussed)
                }
            }
            return $return;
        }
        
        private function mSplitBodyHeader($input) {
            if (preg_match("/^(.*?)\r?\n\r?\n(.*)/s", $input, $match)) {
                return array($match[1], $match[2]);
            }
            return false;
        }
        
        private function mParseHeaders($input) {
            if ($input !== '') {
                // Unfold the input
                $input   = preg_replace("/\r?\n/", "\r\n", $input);
                $input   = preg_replace("/\r\n(\t| )+/", ' ', $input);
                $headers = explode("\r\n", trim($input));
    
                foreach ($headers as $value) {
                    $hdr_name = substr($value, 0, $pos = strpos($value, ':'));
                    $hdr_value = substr($value, $pos+1);
                    if($hdr_value[0] == ' ')
                        $hdr_value = substr($hdr_value, 1);
    /*                    $return[$hdr_name] = $hdr_value;*/
                    $return[] = array(
                                      'name'  => $hdr_name,
                                      'value' => $this->mDecodeHeader($hdr_value)
                                     );
                }
            } else {
                return false; //$return = array();
            }
    
            return $return;
        }
        
        private function mDecodeHeader($input) {
            // Remove white space between encoded-words
            $input = preg_replace('/(=\?[^?]+\?(q|b)\?[^?]*\?=)(\s)+=\?/i', '\1=?', $input);
    
            // For each encoded-word...
            while (preg_match('/(=\?([^?]+)\?(q|b)\?([^?]*)\?=)/i', $input, $matches)) {
    
                $encoded  = $matches[1];
                $charset  = $matches[2];
                $encoding = $matches[3];
                $text     = $matches[4];
    
                switch (strtolower($encoding)) {
                    case 'b':
                        $text = base64_decode($text);
                        break;
    
                    case 'q':
                        $text = str_replace('_', ' ', $text);
                        preg_match_all('/=([a-f0-9]{2})/i', $text, $matches);
                        foreach($matches[1] as $value)
                            $text = str_replace('='.$value, chr(hexdec($value)), $text);
                        break;
                }
    
                $input = str_replace($encoded, $text, $input);
                $utfConverter = new utf8($charset);
                $input = $utfConverter->strToUtf8($input);

            }
    
            return $input;
        }
        
        private function mDecodeBody($input, $encoding = 'quoted-printable') {
            switch (strtolower($encoding)) {
                case '7bit':
                case '8bit':
                    return $input;
                    break;
    
                case 'quoted-printable':
                    return $this->mQuotedPrintableDecode($input);
                    break;
    
                case 'base64':
                    return base64_decode($input);
                    break;
    
                default:
                    return $input;
            }
        }
        
        private function mQuotedPrintableDecode($input) {
            // Remove soft line breaks
            $input = preg_replace("/=\r?\n/", '', $input);
    
            // Replace encoded characters
            $input = preg_replace('/=([a-f0-9]{2})/ie', "chr(hexdec('\\1'))", $input);
    
            return $input;
        }
        
        private function mHtmlEncode( $input, $contenttype = 'plain', $charset = 'iso8859-1' ) {
            $utfConverter = new utf8($charset);
            $input = $utfConverter->strToUtf8($input);
            switch( strtolower( $contenttype ) ) {
            case 'html':
                $input = preg_replace( array( '@<head[^>]*?>.*?</head>@si', '@</?(html|body|!doctype)[^>]*?>@i', '@<script[^>]*?>.*?</script>@si' ), '', $input );
                return $input;
                break;
            case 'plain':
            default:
                $input =  htmlentities( $input, ENT_COMPAT, 'UTF-8' );
                return nl2br( $input );
                //(preg_replace(array( '@<@', '@>@', '@&@', '@"@' ), array ( '&lt;', '&gt;', '&amp;', '&quot;' ), $input));
            }
        }
    }

?>