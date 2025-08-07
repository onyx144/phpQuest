<?php

/**
 * Pagination class
 */
class Pagination
{

    public $total = 0;
    public $page = 1;
    public $limit = 20;
    public $num_links = 8;
    public $url = '';
    public $text_first = 'First';
    public $text_last = 'Last';
    public $text_next = '<i class="fas fa-angle-right"></i>';
    public $text_prev = '<i class="fas fa-angle-left"></i>';

    /**
     * 
     *
     * @return  text
     */
    public function render()
    {
        $total = $this->total;

        if ($this->page < 1) {
            $page = 1;
        } else {
            $page = $this->page;
        }

        if (!(int) $this->limit) {
            $limit = 10;
        } else {
            $limit = $this->limit;
        }

        $num_links = $this->num_links;
        $num_pages = ceil($total / $limit);

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = '';

        if ($page > 1) {
            $output .= '<div class="first_page page-item"><a href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '" class="page-link">' . $this->text_first . '</a></div>';

            if ($page - 1 === 1) {
                $output .= '<div class="prev_page page-item"><a href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '" class="page-link">' . $this->text_prev . '</a></div>';
            } else {
                $output .= '<div class="prev_page page-item"><a href="' . str_replace('{page}', $page - 1, $this->url) . '" class="page-link">' . $this->text_prev . '</a></div>';
            }
        }

        if ($num_pages > 1) {
            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - floor($num_links / 2);
                $end = $page + floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }

//            for ($i = $start; $i <= $end; $i++) {
//                if ($page == $i) {
//                    $output .= '<li class="active"><span>' . $i . '</span></li>';
//                } else {
//                    if ($i === 1) {
//                        $output .= '<li><a href="' . str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url) . '">' . $i . '</a></li>';
//                    } else {
//                        $output .= '<li><a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a></li>';
//                    }
//                }
//            }
            $output .= '<div class="active_page page-item page-link">' . $page . '</div>';
        }

        if ($page < $num_pages) {
            $output .= '<div class="next_page page-item"><a href="' . str_replace('{page}', $page + 1, $this->url) . '" class="page-link">' . $this->text_next . '</a></div>';
            $output .= '<div class="last_page page-item"><a href="' . str_replace('{page}', $num_pages, $this->url) . '" class="page-link">' . $this->text_last . '</a></div>';
        }

        if ($num_pages > 1) {
            return $output;
        } else {
            return '';
        }
    }

}
