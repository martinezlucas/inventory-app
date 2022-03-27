<?php

    class Pagination {

        private $rows_per_page;
        private $total_rows;
        private $index;
        private $total_pages;
        private $previous_page;
        private $next_page;
        private $div_style = "soft-border pagination";

        public function set_rows_per_page($rows_per_page) {
            $this->rows_per_page = $rows_per_page;
        }

        public function set_total_rows($total_rows) {
            $this->total_rows = $total_rows;
        }

        public function set_buttons_hidden($hidden) {
            if($hidden) {
                $this->div_style .= " hidden-block";
            }
        }

        public function set_pagination() {

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
                $this->total_pages = 1;
                
            } else {

                $this->total_pages = ceil($this->total_rows / $this->rows_per_page);

                if(isset($_GET['page_number'])) {
        
                    if(filter_var($_GET['page_number'], FILTER_VALIDATE_INT) === false || empty($_GET['page_number'])) {
                        header('location:../logs/error_connection.html');
                        die();
                    }
        
                    if($_GET['page_number'] == 1) {
            
                        $page = 1;
                        $this->previous_page = 1;
                        
                        if($this->total_pages > 1) {
                            $this->next_page = 2;
                        } else {
                            $this->next_page = 1;
                        }
                        
                    } else {
                
                        $page = $_GET['page_number'];
            
                        if($page == $this->total_pages) {
                            $this->next_page = $this->total_pages;
                        } 
            
                        if($page < $this->total_pages) {
                            $this->next_page = $page + 1;
                        }
            
                        if($page > 2) {
                            $this->previous_page = $page - 1;                
                        } else {
                            $this->previous_page = 1;
                        }
                    }
            
                } else {
                    
                    $page = 1;
                    $this->previous_page = 1;
        
                    if($this->total_pages > 1) {
                        $this->next_page = 2;
                    }
                }

                $this->index = ($page - 1) * $this->rows_per_page;                    
            }
        }

        public function get_index() {
            return $this->index;
        }

        public function show_buttons() {

            if($_SERVER['REQUEST_METHOD'] != 'POST' && $this->total_pages > 1) {
                
                echo "<div class='{$this->div_style}'>";
                echo "<a href='?page_number=1' rel='noreferrer noopener' class='button soft-border'><<</a>";
                echo "<a href='?page_number=" . $this->previous_page. "' rel='noreferrer noopener' class='button soft-border'><</a>";
                echo "<a href='?page_number=" . $this->next_page . "' rel='noreferrer noopener' class='button soft-border'>></a>";
                echo "<a href='?page_number=" . $this->total_pages . "' rel='noreferrer noopener' class='button soft-border'>>></a>";
                echo "</div>";
            }
        }
    }
?>