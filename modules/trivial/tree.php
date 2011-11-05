<?php
    /*
        Developer: Dionyziz
    */

    class Tree {
        // defines an OOP structure similar to an array (or linked-list, if you prefer), 
        // having several children which can be read sequentially
        private $mChildren;
        private $mChildrenCount;
        private $mActiveCount;
        private $mChildPtr;
        
        public function Tree() {
            $this->mActiveCount = $this->mChildrenCount = $this->mChildPtr = 0;
        }
        // add an entry
        public function AttachChild( $child ) {
            $this->mChildren[ $this->mChildrenCount ] = $child;
            $this->mChildrenCount++;
            $this->mActiveCount++;
        }
        public function UnattachChild( $index ) {
            $this->mChildren[ $index ] = false;
            $this->mActiveCount--;
        }
        public function UnattachLastChild() {
            $this->UnattachChild( $this->LastIndex() );
        }
        public function LastIndex() {
            return $this->mChildPtr - 1;
        }
        // true if our structure contains items
        public function HasChildren() {
            return $this->mChildrenCount > 0;
        }
        // returns the number of items our structure contains
        public function ChildrenCount() {
            return $this->mActiveCount;
        }
        public function AllChildrenCount() {
            return $this->mChildrenCount;
        }
        // returns the next child (in sequential order) or false if there are no more children to return
        // if you keep querying the ->Child() method, it will start returning children again from the beginning
        public function Child() {
            if ( !$this->mActiveCount ) {
                return false;
            }
            if ( $this->mChildPtr >= $this->mChildrenCount ) {
                $this->mChildPtr = 0;
                return false;
            }
            $this->mChildPtr++;
            $ret = $this->mChildren[ $this->mChildPtr - 1 ];
            if ( $ret === false ) { // unattached child
                return $this->Child();
            }
            return $ret;
        }
    }
?>