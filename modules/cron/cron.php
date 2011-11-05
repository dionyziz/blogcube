<?php
    /*
        Module: Cron
        Developer: Dionyziz
    */
    
    class AbstractJobs {
        public function PrimaryJobs() {
        }
        public function Add( $functionname , $interval ) {
            switch( $interval ) {
                case 5:
                case 60:
                case 1440:
                    break;
                default:
                    bc_die( "Possible intervals for Cron module: 5, 60, 1440" );
            }
        }
    }
    
    if( $incron === true ) {
        final class CronJobs extends AbstractJobs { 
            private $mAllJobs;
            private $mAllJobsInterval;
            private $mAJPtr;
            
            public function CronJobs() {
                $this->mAllJobs = Array();
                $this->mAllJobsInterval = Array();
                $this->mAJPtr = 0;
            }
            public function Add( $functionname , $interval = 60 ) {
                switch( $interval ) {
                    case 5:
                    case 60:
                    case 1440:
                        break;
                    default:
                        bc_die( "Possible intervals for Cron module: 5, 60, 1440" );
                }
                $this->mAllJobs[] = $functionname;
                $this->mAllJobsInterval[] = $interval;
            }
            public function Count() {
                return count( $this->mAllJobs );
            }
            public function Job() {
                if( $this->mAJPtr < $this->Count() ) {
                    $this->mAJPtr++;
                    return Array( "job"            => $this->mAllJobs[ $this->mAJPtr - 1 ] , 
                                  "interval"    => $this->mAllJobsInterval[ $this->mAJPtr - 1 ] );
                }
                $this->mAJPtr = 0;
                return false;
            }
        }
        
        class Cron {
            private $croncount;
            
            public function Cron() {
                global $cronlogs;
                
                $sql = "SELECT
                            COUNT(*) AS croncount
                        FROM
                            `$cronlogs`;";
                $sqlr = bcsql_query( $sql );
                $crondata = bcsql_fetch_array( $sqlr );
                $this->croncount = $crondata[ "croncount" ];
            }
            public function Fire() {
                // should be called automatically by /cron.bc every 5 minutes
                global $cronlogs;
                
                $nowdate = NowDate();
                $sql = "INSERT INTO
                            `$cronlogs`
                        ( `cronlog_id` , `cronlog_date` )
                        VALUES( '' , '$nowdate' );";
                bcsql_query( $sql );
                
                $this->croncount++;
                $minutecount = $this->croncount * 5; // runs every five minutes
                $this->Cron5();
                if( $minutecount % 60 ) {
                    $this->Cron60();
                    if( $minutecount %1440 ) {
                        $this->Cron1440();
                    }
                }
            }
            private function Cron5() {
                // fires every 5 minutes
                global $jobs;
                
                while( $job = $jobs->Job() )
                    if( $job[ "interval" ] == 5 )
                        eval( $job[ "job" ] );
            }
            private function Cron60() {
                // fires every hour
                global $jobs;
            
                while( $job = $jobs->Job() )
                    if( $job[ "interval" ] == 60 )
                        eval( $job[ "job" ] );
            }
            private function Cron1440() {
                // fires every day
                global $jobs;
    
                while( $job = $jobs->Job() )
                    if( $job[ "interval" ] == 1440 )
                        eval( $job[ "job" ] );
            }
        }

        $jobs = New CronJobs();
    }
    else {
        $jobs = New AbstractJobs();
    }
?>