<?php
    class IRCtoHTML {
    
        var $WorkString;                    // IRC-Text to convert
        var $ReturnString;                    // HTML Result-String
        var $Foregroundcolor;                    // Is any foreground color set?
        var $Backgroundcolor;                    // Is any background color sett?
        var $BoldFont;                    // Is bold mode set?
        var $Underlined;                    // is underlined mode set?
        var $LastBColor;                    // Last Backgroundcolor
        var $Colortable = array (     0  => "#FFFFFF",    // Colortable
                        1  => "#000000",
                        2  => "#0033CC",
                        3  => "#008000",
                        4  => "#FF0000",
                        5  => "#800000",
                        6  => "#800080",
                        7  => "#FF9900",
                        8  => "#FFFF00",
                        9  => "#00FF00",
                        10 => "#008080",
                        11 => "#00FFFF",
                        12 => "#0000FF",
                        13 => "#FF00FF",
                        14 => "#808080",
                        15 => "lightgrey");
    
    
    // **************************************************
    // Constructor
    // **************************************************
    
        function IRCtoHTML ($IRCString) {
            $this->WorkString    = $IRCString;
            $this->ReturnString    = "";
            $this->Foregroundcolor = false;
            $this->Backgroundcolor = false;
            $this->BoldFont      = false;
            $this->Underlined    = false;
            $this->LastBColor     = "";
        }
    
    
        function HTMLColorTagsClose ($SetBStatus) {
            if($this->Backgroundcolor) { 
                  $this->ReturnString    .= "</span>"; 
                  if($SetBStatus) $this->Backgroundcolor = false; 
            }
            if($this->Foregroundcolor) { 
                  $this->ReturnString    .= "</font>"; 
                  $this->Foregroundcolor = false; 
            }
        }
    
        function SetBack($Color) {
            $this->ReturnString    .= "<span style=\"background-color: ".$this->Colortable[$Color]."\">";
        }
        
    
    // **************************************************
    // Check which Colorsettings are made
    // **************************************************
    
        function ColorSettings ( $SubString ) {
            
            $ReturnProgress = 0;
    
            if(is_numeric($SubString[0])) {                                        // Is there a colornumber?
                $Color = $SubString[0];
                $ReturnProgress++;
                if(is_numeric($SubString[1])) {                                    // Has it 2 digits?
                      $Color .= $SubString[1];
                      $ReturnProgress++;
                }
                $this->HTMLColorTagsClose(false);
                $this->ReturnString .= "<font color=\"".$this->Colortable[$Color]."\">";  // create HTML Code
                $this->Foregroundcolor = true;
            } else {
                $this->HTMLColorTagsClose(true);
            }
                     
            // Is there a backgroundcolor?        
            if($SubString[$ReturnProgress]==",") {                                        
                $ReturnProgress++;
                if(is_numeric($SubString[$ReturnProgress])) {        // Really a color code?
                    $Background = $SubString[$ReturnProgress];
                    $ReturnProgress++;
                    if(is_numeric($SubString[$ReturnProgress])) {    // 2 digits?
                        $Background .= $SubString[$ReturnProgress];
                        $ReturnProgress++;
                    }
                    $this->SetBack($Background);
                    $this->LastBColor = $Background;
                    $this->Backgroundcolor = true;
                }
    
            } else {
                if($this->Backgroundcolor) 
                    $this->SetBack($this->LastBColor);     
            }
            return $ReturnProgress;
        }
    
    
        function Bold () {
            if(!$this->BoldFont) {
                $this->ReturnString .= "<b>";
                $this->BoldFont = true;
            } else {
                $this->ReturnString .= "</b>";
                $this->BoldFont = false;
            }
        }
    
        function Underlined () {
            if(!$this->Underlined) {
                $this->ReturnString .= "<u>";
                $this->Underlined = true;
            } else {
                $this->ReturnString .= "</u>";
                $this->Underlined = false;
            }
        }
    
    // **************************************************
    // Main Function for gerating the HTML-Strings
    // **************************************************
    
        function GetHTML () {
    
            // parse IRC-Text
            for ($i=0; $i<strlen($this->WorkString); $i++) {
    
                $Character = $this->WorkString[$i];
    
                switch($Character) {
    
                    case chr(2):                // Bold
                        $this->Bold();
                        break;
    
                    case chr(3):                // Colorsetting
                        $Progress = $this->ColorSettings(substr($this->WorkString, $i+1, 5));
                        $i = $i + $Progress;     
                        break;
    
                    case chr(15):                // Reset Fontsettings
                        $this->HTMLColorTagsClose(true);
                        if($this->BoldFont)   $this->Bold();
                        if($this->Underlined) $this->Underlined();
                        break;
    
                    case chr(31):                // Underlined
                        $this->Underlined();
                        break;
    
                    case chr(32):                // Space
                        $this->ReturnString .= "&nbsp;";
                        break;
    
                    default:
                        $this->ReturnString .= htmlentities($this->WorkString[$i]);
                }
            }
    
            $this->HTMLColorTagsClose(true);
            if($this->BoldFont)   $this->Bold();
            if($this->Underlined) $this->Underlined();
            return $this->ReturnString;
        }
    }
?>