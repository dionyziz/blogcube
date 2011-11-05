<?php
    /* 
    Module: Math
    File: /modules/math/math.php
    Developer: Makis
    */

    include "modules/module.php";    

    if ($debug_version) { 
        $generated_math_path = "/home/media/math/beta/"; 
    } else { 
        $generated_math_path = "/home/media/math/stable/"; 
    }
    
    class Formula { // blame dionyziz
        private $mId;
        private $mFormula;
        private $mBinary;
        private $mValid;
        private $mFilename;
        
        public function Formula( $formula = "" ) {
            global $formulas;
            
            if( $formula == "" ) {
                // attribute query
                return $this->mFormula;
            }
            // constructor
            $this->mFormula = $formula;
            $formula = bcsql_escape( $formula );
            $sql = "SELECT `formula_id` FROM `$formulas` WHERE `formula_formula`='$formula' LIMIT 1;";
            $sqlr = bcsql_query( $sql );
            if( $sqlr->NumRows() ) {
                $thisformula = bcsql_fetch_array( $sqlr );
                $this->mSetId( $thisformula[ "formula_id" ] );
                if( !file_exists( $this->mFilename ) ) {
                    $this->mBinary = $this->mMathParse();
                    $this->mGenerate();
                }
            }
            else {
                $this->mBinary = $this->mMathParse();
                if( $this->mBinary !== false ) {
                    $sql = "INSERT INTO `$formulas` ( `formula_id` , `formula_formula` ) VALUES( '' , '$formula' );";
                    bcsql_query( $sql );
                    $this->mSetId( bcsql_insert_id() );
                    $this->mGenerate();
                }
                else {
                    $this->mValid = false;
                }
            }
        }
        
        private function mSetId( $id ) {
            global $generated_math_path;
            
            $this->mId = $id;
            $this->mFilename = $generated_math_path . $id . ".png";
        }
        
        private function mGenerate() {
            $fh = fopen( $this->mFilename , "wb" );
            fwrite( $fh , $this->mBinary );
            fclose( $fh );
            return true;
        }
        
        public function Id() {
            return $this->mId;
        }
        public function PNG() {
            return $this->mId;
        }
        public function Valid() {
            return $this->mValid;
        }
        
        private function mMathParse() {
            global $user;
            $header = '\magnification=1500' . "\n" . ' ${\displaystyle {';
            $footer = '}}$' . "\n" . '\bye';
            $workdir = trim(shell_exec('pwd')) . "/";
            $title = 'math_'. 'user' . $user->Id() . 'd' . UniqueTimeStamp() . 'p'; //math_user23d12345678p*.png, where *===%d
            $TeX = $workdir . $title . '.tex';
            $dvi = $workdir . $title . '.dvi';
            $png = $workdir . $title . '1.png'; //png of 1st page (at least that must exist)
            $texExec = 'tex --interaction=nonstopmode ' . $TeX;
            
            $fh = fopen( $TeX , "w" );
            fputs( $fh , $header . $this->mFormula . $footer );
            fclose( $fh );
    
            /* TeX --> dvi */
            shell_exec($texExec); //TODO: scan TeX response for minor syntax errors that were supposedly ignored - if found, we must return false; instead
            unlink($TeX);
            unlink($workdir . $title . '.log');
            if (file_exists($workdir . 'texput.log')) { //exists only in case of a big TeX syntax error
                unlink($workdir . 'texput.log'); 
                bc_die('Critical TeX syntax error!');
            }
            if (!file_exists($dvi)) { bc_die('TeX error - no dvi file exists!'); } //no dvi came out
    
            /* dvi --> png */
            $dviExec = 'dvipng -gamma 1.5 -T tight ' . $dvi; //may produce more than 1 png
            shell_exec($dviExec);
            unlink($workdir . $title . '.dvi');
            $pageno=1;
    
            /* LOOP through pages */
            while (file_exists($png)) { 
                $crop = 'mogrify -crop +0-24! ' . $png;
                $trim = 'mogrify -trim ' . $png;
        
                shell_exec($crop);
                shell_exec($trim);
        
                $buffer = imagecreatefrompng($png);
                if (!$buffer) { bc_die('Page:' . $pageno . '\'s png is not a valid image!'); }
                unlink($png);
    
                $ImageArr[] = $buffer;
    
                //fetch new png ($png++)
                $png = $workdir . $title . ++$pageno . '.png';
            }
            if (!is_array($ImageArr)) {
                bc_die('Check dvipng setup - even the first page failed.');
            }
    
            //no more pages found
            $count = count($ImageArr);
            $mx = 0;
            $my = 0;
            for ($i=0; $i<$count; $i++) {
                $tx = imageSX($ImageArr[$i]);
                $ty = imageSY($ImageArr[$i]);
                if ($tx>$mx) { $mx = $tx; }
                $my += $ty;
            }
            
            $img = imagecreatetruecolor($mx, $my);
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagecolortransparent($img, $white);
            
            $ty=0;
            foreach($ImageArr as $buffer) {
                imagecopy ($img, $buffer, 0, $ty, 0, 0, imageSX($buffer), imageSY($buffer)); //centered: (imageSX($img) - imageSX($buffer)) / 2
                $ty += imageSY($buffer);
            }
            bc_ob_section();
            imagepng( $img );
            $imgbin = bc_ob_fallback();
    
            return $imgbin;
        }
    }
    
    /* Example Usage:
        $formula = New Formula( "3 + 4 = 7" );
        img( $formula->PNG() );
    */
?>