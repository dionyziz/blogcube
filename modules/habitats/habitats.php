<?php
    /*
    Module: Habitats
    File: /modules/habitats/habitats.php
    Developer: Indy
    */
    
    include 'modules/module.php';
    include 'modules/habitats/map.php';
    
    function CreateHabitat( $Name , $Description , $x , $y , $UserId = 0 ) {
        
        global $habitats;
        global $user;
        
        bc_assert( ( $x > 0 || $x === 0 ) && ( $y > 0 || $y === 0 ) );
        $Name = bcsql_escape( $Name );
        $Description = bcsql_escape( $Description );
        $Date = NowDate();
        $UserId = $UserId or $user->Id();
        $Query = "INSERT INTO `$habitats` 
                  ( `hab_id` , `hab_name` , `hab_description` , `hab_userid` , `hab_date` , `hab_x`, `hab_y` )
                  VALUES 
                  ( '' , '$Name' , '$Description' , '$UserId' , '$Date' , '$x' , '$y' );";
        bcsql_query( $Query );
        
        return bcsql_insert_id();
    }

    class Habitat {
        
        private $mId;
        private $mName;
        private $mDescription;
        private $mBlogList;
        private $mUserId;
        private $mDate;
        private $mX;
        private $mY;
        private $mPopularity;
        private $mPopularities;
        private $mSize; // number of blogs that it contains
        
        public function Habitat( $Constructor , $HabitatInfo = false ) {
            // New Habitat( $habitatid )
            // New Habitat( $fetched_array )
            
            global $habitats;
            global $habitatsblogs;
            global $blogs;
        
            if ( is_array( $Constructor ) ) {
                $HabitatDetails = $Constructor;
                $Id = $HabitatDetails[ 'hab_id' ];
            }
            else {
                bc_assert( ValidId( $Constructor ) );
                $Id = $Constructor;
                $Query = "SELECT * FROM `$habitats` WHERE `hab_id` = '$Id' LIMIT 1;";
                $Sqlr = bcsql_query( $Query );
                $HabitatDetails = $Sqlr->FetchArray();
            }
            $Sqlr = bcsql_query( $Query );
            while ( $Row = $Sqlr->FetchArray() ) {
                $this->mBlogList[ $Row[ 'hb_blogid' ] ] = true;
            }
            $this->mBlogList = Array();
            $this->mPopularities = Array();
            if ( $HabitatInfo === false ) {
                $Query = "SELECT
                        `blog_id` , `blog_popularity`
                    FROM     
                        `$blogs` , `$habitatsblogs`
                    WHERE
                        `$blogs`.`blog_id` = `$habitatsblogs`.`hb_blogid`
                    AND
                        `$habitatsblogs`.`hb_habid` = '$Id';";
                $Sqlr = bcsql_query( $Query );
                while ( $Pair = $Sqlr->FetchArray() ) {
                    $this->mPopularities[ $Pair[ 'blog_id' ] ] = $Pair[ 'blog_popularity' ];
                    $this->mBlogList[ $Row[ 'blog_id' ] ] = true;
                }
            }
            else {
                foreach ( $HabitatInfo as $Blog ) {
                    $this->mBlogList[ $Blog[ 'blog_id' ] ] = true;
                    $this->mPopularities[ $Blog[ 'blog_id' ] ] = $Blog[ 'blog_popularity' ];
                }
            }
            uksort( $this->mBlogList , array( $this , 'ComparePopularities' ) );
            $this->mSize = count( $this->mBlogList );
            $this->mId = $HabitatDetails[ 'hab_id' ];
            $this->mName = $HabitatDetails[ 'hab_name' ];
            $this->mDescription = $HabitatDetails[ 'hab_description' ];
            $this->mUserId = $HabitatDetails[ 'hab_userid' ];
            $this->mDate = $HabitatDetails[ 'hab_date' ];
            $this->mX = $HabitatDetails[ 'hab_x' ];
            $this->mY = $HabitatDetails[ 'hab_y' ];
            $this->mPopularity = 0;
            if ( isset( $HabitatDetails[ 'hab_size' ] ) ) {
                $this->mSize = $HabitatDetails[ 'hab_size' ];
            }
            for ( $i = 0; $i < count( $this->mBlogList ); $i++ ) {
                $CurrentBlog = New Blog( $this->mBlogList[ $i ] );
                $this->mPopularity += $CurrentBlog->Popularity();
            }
            $this->mPopularity = int( $this->mPopularity / count( $this->mBlogList ) );
        }
        
        public function AddToBlogList( $BlogId ) {
            
            global $habitatsblogs;
            
            $Id = $this->mId;
            bc_assert( ValidId( $BlogId ) );
            $this->mBlogList[ $BlogId ] = true;
            $Query = "INSERT INTO `$habitatsblogs` ( `hb_id` , `hab_id` , `blog_id` ) VALUES ( '' , '$Id' , '$BlogId' );";
            bcsql_query( $Query );
        }
        
        public function RemoveFromBlogList( $BlogId ) {
        
            global $habitatsblogs;
                            $this->mBlogList[ $Row[ 'hb_blogid' ] ] = true;
            $Id = $this->mId;
            bc_assert( ValidId( $BlogId ) );
            unset( $this->mBlogList[ $BlogId ] );
            $Query = "DELETE FROM `$habitatsblogs` WHERE `hab_id` = '$Id' AND `blog_id` = '$BlogId' LIMIT 1;";
            bcsql_query( $Query );
        }
        
        public function ComparePopularities( $FirstPopularity , $SecondPopularity ) {
            if ( $FirstPopularity < $SecondPopularity )
                return 1;
            else if ( $FirstPopularity == $SecondPopularity )
                return 0;
            else
                return - 1;
        }
        
        public function MostPopularBlog() {
            return New Blog( $this->mBlogList[ 0 ] );
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function Name() {
            return $this->mName;
        }
        
        public function Description() {
            return $this->mDescription;
        }
        
        public function BlogList() {
            return $this->mBlogList;
        }
        
        public function UserId() {
            return $this->mUserId;
        }
        
        public function Date() {
            return $this->mDate;
        }
        
        public function X() {
            return $this->mX;
        }
        
        public function Y() {
            return $this->mY;
        }
        
        public function Popularity() {
            return $this->mPopularity;
        }
        
        public function Size() {
            return $this->mSize;
        }
        
    }
    
    function Habitat_AddBlog( $habitatId , $blogId ) {
        $habitat = New Habitat( $habitatId );
        $habitat->AddToBlogList( $blogId );
    }
    
    function Habitat_RemoveBlog( $habitatId , $blogId ) {
        $habitat = New Habitat( $habitatId );
        $habitat->RemoveFromBlogList( $blogId );
    }
    
?>
