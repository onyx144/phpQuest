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
        $num_pages = $total > 0 ? max(1, (int) ceil($total / $limit)) : 0;

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $urlFirst = str_replace(array('&amp;page={page}', '?page={page}', '&page={page}'), '', $this->url);

        $output = '';

        if ($num_pages > 1) {
            if ($page > 1) {
                $output .= '<li class="page-item first_page"><a href="' . htmlspecialchars($urlFirst, ENT_QUOTES, 'UTF-8') . '" class="page-link">' . $this->text_first . '</a></li>';

                if ($page - 1 === 1) {
                    $output .= '<li class="page-item prev_page"><a href="' . htmlspecialchars($urlFirst, ENT_QUOTES, 'UTF-8') . '" class="page-link">' . $this->text_prev . '</a></li>';
                } else {
                    $output .= '<li class="page-item prev_page"><a href="' . htmlspecialchars(str_replace('{page}', (string) ($page - 1), $this->url), ENT_QUOTES, 'UTF-8') . '" class="page-link">' . $this->text_prev . '</a></li>';
                }
            }

            if ($num_pages <= $num_links) {
                $start = 1;
                $end = $num_pages;
            } else {
                $start = $page - (int) floor($num_links / 2);
                $end = $page + (int) floor($num_links / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $num_pages) {
                    $start -= ($end - $num_pages);
                    $end = $num_pages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= '<li class="page-item active active_page" aria-current="page"><span class="page-link">' . (int) $i . '</span></li>';
                } else {
                    if ($i === 1) {
                        $output .= '<li class="page-item"><a href="' . htmlspecialchars($urlFirst, ENT_QUOTES, 'UTF-8') . '" class="page-link">' . (int) $i . '</a></li>';
                    } else {
                        $output .= '<li class="page-item"><a href="' . htmlspecialchars(str_replace('{page}', (string) $i, $this->url), ENT_QUOTES, 'UTF-8') . '" class="page-link">' . (int) $i . '</a></li>';
                    }
                }
            }

            if ($page < $num_pages) {
                $output .= '<li class="page-item next_page"><a href="' . htmlspecialchars(str_replace('{page}', (string) ($page + 1), $this->url), ENT_QUOTES, 'UTF-8') . '" class="page-link">' . $this->text_next . '</a></li>';
                $output .= '<li class="page-item last_page"><a href="' . htmlspecialchars(str_replace('{page}', (string) $num_pages, $this->url), ENT_QUOTES, 'UTF-8') . '" class="page-link">' . $this->text_last . '</a></li>';
            }
        }

        if ($num_pages > 1) {
            return '<ul class="pagination pagination-sm mb-0 flex-wrap justify-content-center">' . $output . '</ul>';
        }

        return '';
    }

}
